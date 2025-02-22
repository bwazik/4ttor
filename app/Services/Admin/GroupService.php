<?php

namespace App\Services\Admin;

use App\Models\Group;
use Illuminate\Support\Facades\DB;
use App\Traits\PreventDeletionIfRelated;

class GroupService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['students'];
    protected $transModelKey = 'admin/groups.groups';

    public function getGroupsForDatatable($groupsQuery)
    {
        return datatables()->eloquent($groupsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('teacher_id', function ($row) {
                return "<a target='_blank' href='" . route('admin.teachers.details', $row->teacher_id) . "'>" . ($row->teacher_id ? $row->teacher->name : '-') . "</a>";
            })
            ->editColumn('day_1', function ($row) {
                return $row->day_1 ? getDayName($row->day_1) : '-';
            })
            ->editColumn('day_2', function ($row) {
                return $row->day_2 ? getDayName($row->day_2) : '-';
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
                            id="edit-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '" data-is_active="' . ($row->is_active == 0 ? '0' : '1') . '"
                            data-teacher_id="' . $row->teacher_id . '" data-day_1="' . $row->day_1 . '" data-day_2="' . $row->day_2 . '" data-time="' . $row->time . '">
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
            ->rawColumns(['selectbox', 'teacher_id', 'is_active', 'actions'])
            ->make(true);
    }

    public function insertGroup(array $request)
    {
        DB::beginTransaction();

        try {
            Group::create([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'teacher_id' => $request['teacher_id'],
                'day_1' => $request['day_1'] ?? null,
                'day_2' => $request['day_2'] ?? null,
                'time' => $request['time'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/groups.group')]),
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

    public function updateGroup($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $group = Group::findOrFail($id);

            $group->update([
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'teacher_id' => $request['teacher_id'],
                'day_1' => $request['day_1'],
                'day_2' => $request['day_2'],
                'time' => $request['time'],
                'is_active' => $request['is_active'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/groups.group')]),
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

    public function deleteGroup($id): array
    {
        DB::beginTransaction();

        try {
            $group = Group::select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($group)) {
                return $dependencyCheck;
            }

            $group->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/groups.group')]),
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

    public function deleteSelectedGroups($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $groups = Group::whereIn('id', $ids)
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($groups)) {
                return $dependencyCheck;
            }

            Group::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/groups.groups'))]),
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

    public function checkDependenciesForSingleDeletion($group)
    {
        return $this->checkForSingleDependencies($group, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($groups)
    {
        return $this->checkForMultipleDependencies($groups, $this->relationships, $this->transModelKey);
    }
}
