<?php

namespace App\Services\Admin\Platform;

use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class SubjectService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['teachers'];
    protected $transModelKey = 'admin/subjects.subjects';

    public function getSubjectsForDatatable($subjectsQuery)
    {
        return datatables()->eloquent($subjectsQuery)
            ->addIndexColumn()
->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge rounded-pill bg-label-success text-capitalized">'.trans('main.active').'</span>' : '<span class="badge rounded-pill bg-label-secondary text-capitalized">'.trans('main.inactive').'</span>';
            })
            ->addColumn('actions', function ($row) {
                return
                    '<div class="align-items-center">' .
                    '<span class="text-nowrap">
                        <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                            tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal"
                            id="edit-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '" data-is_active="' . ($row->is_active == 0 ? '0' : '1') . '">
                            <i class="ri-edit-box-line ri-20px"></i>
                        </button>
                    </span>' .
                    '<button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                            id="delete-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                            data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                        </button>' .
                    '</div>';
            })
            ->rawColumns(['selectbox', 'is_active', 'actions'])
            ->make(true);
    }

    public function insertSubject(array $request)
    {
        DB::beginTransaction();

        try {
            Subject::create([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'is_active' => $request['is_active'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/subjects.subject')]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function updateSubject($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $subject = Subject::findOrFail($id);

            $subject->update([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'is_active' => $request['is_active'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/subjects.subject')]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function deleteSubject($id): array
    {
        DB::beginTransaction();

        try {
            $subject = Subject::select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($subject)) {
                return $dependencyCheck;
            }

            $subject->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/subjects.subject')]),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function deleteSelectedSubjects($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $subjects = Subject::whereIn('id', $ids)
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($subjects)) {
                return $dependencyCheck;
            }

            Subject::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/subjects.subjects'))]),
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => config('app.env') === 'production'
                    ? trans('main.errorMessage')
                    : $e->getMessage(),
            ];
        }
    }

    public function checkDependenciesForSingleDeletion($subject)
    {
        return $this->checkForSingleDependencies($subject, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($subjects)
    {
        return $this->checkForMultipleDependencies($subjects, $this->relationships, $this->transModelKey);
    }
}
