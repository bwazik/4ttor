<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Misc\FaqService;
use App\Http\Requests\Admin\Misc\FaqsRequest;
use App\Traits\ServiceResponseTrait;

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
        $faqsQuery = Faq::query()->select('id', 'name', 'slug', 'icon', 'description', 'order');

        if ($request->ajax()) {
            return $this->faqService->getFaqsForDatatable($faqsQuery);
        }

        return view('admin.misc.faqs.index');
    }


    public function insert(FaqsRequest $request)
    {
        $result = $this->faqService->insertFaq($request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function update(FaqsRequest $request)
    {
        $result = $this->faqService->updateFaq($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'faqs');

        $result = $this->faqService->deleteFaq($request->id);

        return $this->conrtollerJsonResponse($result);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'faqs');

        $result = $this->faqService->deleteSelectedFaqs($request->ids);

        return $this->conrtollerJsonResponse($result);
    }

}
