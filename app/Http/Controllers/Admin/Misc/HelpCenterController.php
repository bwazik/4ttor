<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ArticleContent;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Traits\DatabaseTransactionTrait;
use App\Services\Admin\Misc\HelpCenterService;
use App\Http\Requests\Admin\Misc\ArticlesRequest;
use App\Http\Requests\Admin\Misc\ContentsRequest;

class HelpCenterController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait, DatabaseTransactionTrait;

    protected $helpCenterService;

    public function __construct(HelpCenterService $helpCenterService)
    {
        $this->helpCenterService = $helpCenterService;
    }

    public function index()
    {
        $categories = Cache::remember('admin_help_center_categories', 1440, function () {
            return Category::with(['articles' => fn($q) => $q->orderBy('published_at', 'desc')])
                ->orderBy('order')
                ->get();
        });

        $categoryIds = Cache::remember('admin_faqs_category_ids', 1440, function () {
            return Category::select('id', 'name')
                ->orderBy('id')
                ->pluck('name', 'id')
                ->toArray();
        });

        $pinnedArticles = Cache::remember('admin_pinned_articles', 1440, function () {
            return Article::pinned()
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
        });

        return view('admin.misc.help-center.index', compact('categories', 'categoryIds', 'pinnedArticles'));
    }

    public function insertArticle(ArticlesRequest $request)
    {
        $result = $this->helpCenterService->insertArticle($request->validated());

        return $this->conrtollerJsonResponse(
            $result,
            [
                'admin_help_center_categories',
                'teacher_help_center_categories',
                'student_help_center_categories',
                'admin_faqs_category_ids',
                'admin_pinned_articles',
                'teacher_pinned_articles',
                'student_pinned_articles',
            ]
        );
    }

    public function updateArticle(ArticlesRequest $request)
    {
        $result = $this->helpCenterService->updateArticle($request->id, $request->validated());

        return $this->conrtollerJsonResponse(
            $result,
            [
                'admin_help_center_categories',
                'teacher_help_center_categories',
                'student_help_center_categories',
                'admin_faqs_category_ids',
                'admin_pinned_articles',
                'teacher_pinned_articles',
                'student_pinned_articles',
            ]
        );
    }

    public function deleteArticle(Request $request)
    {
        $this->validateExistence($request, 'articles');

        $result = $this->helpCenterService->deleteArticle($request->id);

        return $this->conrtollerJsonResponse(
            $result,
            [
                'admin_help_center_categories',
                'teacher_help_center_categories',
                'student_help_center_categories',
                'admin_faqs_category_ids',
                'admin_pinned_articles',
                'teacher_pinned_articles',
                'student_pinned_articles',
            ]
        );
    }

    public function deleteSelectedArticles(Request $request)
    {
        $this->validateExistence($request, 'articles');

        $result = $this->helpCenterService->deleteSelectedArticles($request->ids);

        return $this->conrtollerJsonResponse(
            $result,
            [
                'admin_help_center_categories',
                'teacher_help_center_categories',
                'student_help_center_categories',
                'admin_faqs_category_ids',
                'admin_pinned_articles',
                'teacher_pinned_articles',
                'student_pinned_articles',
            ]
        );
    }

    public function show($categorySlug, $articleSlug)
    {
        $article = Cache::remember("article_{$categorySlug}_{$articleSlug}", 1440, function () use ($categorySlug, $articleSlug) {
            return Article::with([
                'category',
                'articleContents' => fn($q) => $q->orderBy('order', 'asc')
            ])
                ->where('slug', $articleSlug)
                ->whereHas('category', fn($q) => $q->where('slug', $categorySlug))
                ->firstOrFail();
        });

        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->orderBy('published_at', 'desc')
            ->get();

        return view('admin.misc.help-center.show', compact('article', 'relatedArticles'));
    }

    public function insertContent(ContentsRequest $request, $id)
    {
        $article = Article::findOrFail($id);
        $result = $this->helpCenterService->insertContent($request, $id);

        return $this->conrtollerJsonResponse(
            $result,
            [
                "article_{$article->category->slug}_{$article->slug}",
            ]
        );
    }

    public function updateContent(Request $request)
    {
        $content = ArticleContent::with(['article:id,category_id,slug', 'article.category:id,slug'])->findOrFail($request->id);

        $validated = $request->validate([
            'order' => 'required|numeric|between:0,999999.99',
            'content' => 'required|string|max:1000',
        ]);

        $result = $this->helpCenterService->updateContent($request->id, $validated);

        return $this->conrtollerJsonResponse(
            $result,
            [
                "article_{$content->article->category->slug}_{$content->article->slug}",
            ]
        );
    }

    public function deleteContent(Request $request)
    {
        $this->validateExistence($request, 'article_contents');

        $content = ArticleContent::with(['article:id,category_id,slug', 'article.category:id,slug'])->findOrFail($request->id);

        $result = $this->helpCenterService->deleteContent($request->id);

        return $this->conrtollerJsonResponse(
            $result,
            [
                "article_{$content->article->category->slug}_{$content->article->slug}",
            ]
        );
    }
}
