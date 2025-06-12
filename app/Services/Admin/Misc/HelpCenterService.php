<?php

namespace App\Services\Admin\Misc;
use App\Models\Article;
use App\Models\ArticleContent;
use App\Traits\PublicValidatesTrait;
use Illuminate\Support\Facades\Storage;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;
use App\Services\Admin\FileUploadService;

class HelpCenterService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = [];
    protected $transModelKey = 'admin/helpCenter.helpCenter';
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function insertArticle(array $request)
    {
        return $this->executeTransaction(function () use ($request) {
            $article = Article::create([
                'title' => ['en' => $request['title_en'], 'ar' => $request['title_ar']],
                'slug' => $request['slug'],
                'category_id' => $request['category_id'],
                'audience' => $request['audience'],
                'is_active' => $request['is_active'] ?? true,
                'is_pinned' => $request['is_pinned'] ?? false,
                'description' => (isset($request['description_en']) || isset($request['description_ar'])) ? ['en' => $request['description_en'], 'ar' => $request['description_ar']] : NULL,
                'published_at' => now(),
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/helpCenter.article')]));
        });
    }

    public function updateArticle($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request) {
            $article = Article::findOrFail($id);

            $article->update([
                'title' => ['en' => $request['title_en'], 'ar' => $request['title_ar']],
                'slug' => $request['slug'],
                'category_id' => $request['category_id'],
                'audience' => $request['audience'],
                'is_active' => $request['is_active'] ?? true,
                'is_pinned' => $request['is_pinned'] ?? false,
                'description' => (isset($request['description_en']) || isset($request['description_ar'])) ? ['en' => $request['description_en'], 'ar' => $request['description_ar']] : NULL,
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/helpCenter.article')]));
        });
    }

    public function deleteArticle($id): array
    {
        return $this->executeTransaction(function () use ($id) {
            $article = Article::select('id', 'title')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($article))
                return $dependencyCheck;

            $article->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/helpCenter.article')]));
        });
    }

    public function deleteSelectedArticles($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids) {
            $articles = Article::whereIn('id', $ids)->select('id', 'title')->orderBy('id')->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($articles)) {
                return $dependencyCheck;
            }

            Article::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/helpCenter.article')]));
        });
    }

    public function insertContent($request, $id)
    {
        return $this->executeTransaction(function () use ($request, $id) {
            $article = Article::findOrFail($id);

            $data = [
                'article_id' => $article->id,
                'type' => $request['type'],
                'order' => $request['order'],
            ];

            if ($request['type'] == 1) {
                $data['content'] = $request['textContent'];
            } elseif ($request['type'] == 2) {
                $uploadResult = $this->fileUploadService->uploadImage($request, $article->slug);
                if ($uploadResult['status'] !== 'success') {
                    return $this->errorResponse($uploadResult['message']);
                }
                $data['content'] = $uploadResult['data']['file_name'];
            } else {
                return $this->errorResponse(trans('main.errorMessage'));
            }

            ArticleContent::create($data);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/helpCenter.content')]));
        });
    }

    public function updateContent($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request) {
            $content = ArticleContent::with(['article:id,category_id,slug', 'article.category:id,slug'])->findOrFail($id);

            $content->update([
                'order' => $request['order'],
                'content' => $request['content'],
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/helpCenter.content')]));
        });
    }

    public function deleteContent($id): array
    {
        return $this->executeTransaction(function () use ($id) {
            $content = ArticleContent::with(['article:id,slug'])->findOrFail($id);

            if ($content->type == 2 && $content->content && Storage::disk('articles')->exists("{$content->article->slug}/{$content->content}")) {
                Storage::disk('articles')->delete("{$content->article->slug}/{$content->content}");
            }

            $content->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/helpCenter.content')]));
        });
    }

    public function checkDependenciesForSingleDeletion($article)
    {
        return $this->checkForSingleDependencies($article, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($articles)
    {
        return $this->checkForMultipleDependencies($articles, $this->relationships, $this->transModelKey);
    }
}