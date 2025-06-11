<?php

namespace App\Services\Admin\Misc;
use App\Models\Faq;
use App\Traits\PublicValidatesTrait;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class FaqService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = [];

    protected $transModelKey = 'admin/faqs.faqs';

    public function insertFaq(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            Faq::create([
                'category_id' => $request['category_id'],
                'audience' => $request['audience'],
                'question' => ['en' => $request['question_en'], 'ar' => $request['question_ar']],
                'answer' => ['en' => $request['answer_en'], 'ar' => $request['answer_ar']],
                'is_active' => $request['is_active'] ?? true,
                'is_at_landing' => $request['is_at_landing'] ?? false,
                'order' => $request['order'] ?? 1,
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/faqs.faq')]));
        });
    }

    public function updateFaq($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request) {
            $faq = Faq::findOrFail($id);

            $faq->update([
                'category_id' => $request['category_id'],
                'audience' => $request['audience'],
                'question' => ['en' => $request['question_en'], 'ar' => $request['question_ar']],
                'answer' => ['en' => $request['answer_en'], 'ar' => $request['answer_ar']],
                'is_active' => $request['is_active'] ?? true,
                'is_at_landing' => $request['is_at_landing'] ?? false,
                'order' => $request['order'] ?? 1,
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/faqs.faq')]));
        });
    }

    public function deleteFaq($id): array
    {
        return $this->executeTransaction(function () use ($id) {
            $faq = Faq::select('id', 'question')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($faq))
                return $dependencyCheck;

            $faq->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/faqs.faq')]));
        });
    }

    public function deleteSelectedFaqs($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids) {
            $faqs = Faq::whereIn('id', $ids)->select('id', 'question')->orderBy('id')->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($faqs)) {
                return $dependencyCheck;
            }

            Faq::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/faqs.faq')]));
        });
    }

    public function checkDependenciesForSingleDeletion($faq)
    {
        return $this->checkForSingleDependencies($faq, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($faqs)
    {
        return $this->checkForMultipleDependencies($faqs, $this->relationships, $this->transModelKey);
    }
}