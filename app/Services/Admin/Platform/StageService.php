<?php

namespace App\Services\Admin\Platform;

use App\Models\Stage;
use App\Traits\PreventDeletionIfRelated;
use Illuminate\Support\Facades\DB;

class StageService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['grades'];
    protected $transModelKey = 'admin/stages.stages';

    public function getStagesForDatatable($stagesQuery)
    {
        return datatables()->eloquent($stagesQuery)
            ->addIndexColumn()
->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge rounded-pill bg-label-success" text-capitalized="">'.trans('main.active').'</span>' : '<span class="badge rounded-pill bg-label-secondary" text-capitalized="">'.trans('main.inactive').'</span>';
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

    public function insertStage(array $request)
    {
        DB::beginTransaction();

        try {
            Stage::create([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'is_active' => $request['is_active'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/stages.stage')]),
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

    public function updateStage($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $stage = Stage::findOrFail($id);

            $stage->update([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'is_active' => $request['is_active'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/stages.stage')]),
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

    public function deleteStage($id): array
    {
        DB::beginTransaction();

        try {
            $stage = Stage::select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($stage)) {
                return $dependencyCheck;
            }

            $stage->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/stages.stage')]),
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

    public function deleteSelectedStages($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $stages = Stage::whereIn('id', $ids)
                ->select('id', 'name')
                ->orderBy('id')
                ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($stages)) {
                return $dependencyCheck;
            }

            Stage::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/stages.stages'))]),
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

    public function checkDependenciesForSingleDeletion($stage)
    {
        return $this->checkForSingleDependencies($stage, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($stages)
    {
        return $this->checkForMultipleDependencies($stages, $this->relationships, $this->transModelKey);
    }
}
