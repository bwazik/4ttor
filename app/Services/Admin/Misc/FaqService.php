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

    public function getFaqsForDatatable($faqsQuery)
    {
        return datatables()->eloquent($faqsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->editColumn('name', fn($row) => $row->name)
            ->editColumn('icon', fn($row) => '<i class="text-primary icon-base ri ri-'.$row->icon.' ri-30px"></i>')
            ->addColumn('actions', fn($row) => $this->generateActionButtons($row))
            ->rawColumns(['selectbox', 'icon', 'actions'])
            ->make(true);
    }

    private function generateActionButtons($row): string
    {
        return
            '<div class="align-items-center">' .
                '<span class="text-nowrap">' .
                    '<button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light" ' .
                        'tabindex="0" type="button" ' .
                        'data-bs-toggle="offcanvas" data-bs-target="#edit-modal" ' .
                        'id="edit-button" ' .
                        'data-id="' . $row->id . '" ' .
                        'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                        'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                        'data-slug="' . $row->slug . '" ' .
                        'data-icon="' . $row->icon . '" ' .
                        'data-description_ar="' . $row->getTranslation('description', 'ar') . '" ' .
                        'data-description_en="' . $row->getTranslation('description', 'en') . '" ' .
                        'data-order="' . $row->order . '" ' . '">' .
                        '<i class="ri-edit-box-line ri-20px"></i>' .
                    '</button>' .
                '</span>' .
                '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1" ' .
                    'id="delete-button" ' .
                    'data-id="' . $row->id . '" ' .
                    'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                    'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                    'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                    '<i class="ri-delete-bin-7-line ri-20px text-danger"></i>' .
                '</button>' .
            '</div>';
    }

    public function insertFaq(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            Faq::create([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'slug' => $request['slug'],
                'icon' => $request['icon'] ?? NULL,
                'description' => $request['description'] ? ['en' => $request['description_en'], 'ar' => $request['description_ar']] : NULL,
                'order' => $request['order'],
            ]);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/faqs.faq')]));
        });
    }

    public function updateFaq($id, array $request)
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            $faq = Faq::findOrFail($id);

            $faq->update([
                'name' => ['en' => $request['name_en'], 'ar' => $request['name_ar']],
                'slug' => $request['slug'],
                'icon' => $request['icon'] ?? NULL,
                'description' => $request['description'] ? ['en' => $request['description_en'], 'ar' => $request['description_ar']] : NULL,
                'order' => $request['order'],
            ]);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/faqs.faq')]));
        });
    }

    public function deleteFaq($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            $faq = Faq::select('id', 'name')->findOrFail($id);

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

        return $this->executeTransaction(function () use ($ids)
        {
            $faqs = Faq::whereIn('id', $ids)->select('id', 'name')->orderBy('id')->get();

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
