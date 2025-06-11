<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\Faq;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Services\Admin\Misc\FaqService;
use App\Http\Requests\Admin\Misc\FaqsRequest;

class FaqsController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    public function index(Request $request)
    {
        $categories = Cache::remember('admin_faqs_categories', 1440, function () {
            return Category::with(['faqs' => fn($q) => $q->orderBy('order')])
                ->orderBy('order')
                ->get();
        });

        $categoryIds = Cache::remember('admin_faqs_category_ids', 1440, function () {
            return Category::select('id', 'name')
                ->orderBy('id')
                ->pluck('name', 'id')
                ->toArray();
        });

        return view('admin.misc.faqs.index', compact('categories', 'categoryIds'));
    }

    public function insert(FaqsRequest $request)
    {
        $result = $this->faqService->insertFaq($request->validated());

        return $this->conrtollerJsonResponse($result,
        [
            'admin_faqs_categories',
            'teacher_faqs_categories',
            'student_faqs_categories',
            'landing_faqs',
            'admin_faqs_category_ids',
        ]);
    }

    public function update(FaqsRequest $request)
    {
        $result = $this->faqService->updateFaq($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result,
        [
            'admin_faqs_categories',
            'teacher_faqs_categories',
            'student_faqs_categories',
            'landing_faqs',
            'admin_faqs_category_ids',
        ]);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'faqs');

        $result = $this->faqService->deleteFaq($request->id);

        return $this->conrtollerJsonResponse($result,
        [
            'admin_faqs_categories',
            'teacher_faqs_categories',
            'student_faqs_categories',
            'landing_faqs',
            'admin_faqs_category_ids',
        ]);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'faqs');

        $result = $this->faqService->deleteSelectedFaqs($request->ids);

        return $this->conrtollerJsonResponse($result,
        [
            'admin_faqs_categories',
            'teacher_faqs_categories',
            'student_faqs_categories',
            'landing_faqs',
            'admin_faqs_category_ids',
        ]);
    }
}
