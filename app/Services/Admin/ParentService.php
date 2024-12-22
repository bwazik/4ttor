<?php

namespace App\Services\Admin;

use App\Models\MyParent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\PreventDeletionIfRelated;

class ParentService
{
    use PreventDeletionIfRelated;

    protected $relationships = ['students'];
    protected $transModelKey = 'admin/parents.parents';

    public function getParentsForDatatable($parentsQuery)
    {
        return datatables()->eloquent($parentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge rounded-pill bg-label-success" text-capitalized="">'.trans('main.active').'</span>' : '<span class="badge rounded-pill bg-label-secondary" text-capitalized="">'.trans('main.inactive').'</span>';
            })
            ->addColumn('actions', function ($row) {
                return
                '<div class="d-inline-block">
                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end m-0">
                        <li><a href="javascript:;" class="dropdown-item">'.trans('main.details').'</a></li>
                        <li>
                            <a href="javascript:;" class="dropdown-item"
                                id="archive-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                                data-bs-target="#archive-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                '.trans('main.archive').'
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li>
                            <a href="javascript:;" class="dropdown-item text-danger"
                                id="delete-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                                data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                '.trans('main.delete').'
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                    tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal"
                    id="edit-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                    data-username=' . $row->username . ' data-email=' . $row->email . ' data-phone=' . $row->phone . '
                    data-password="" data-gender="'.$row->gender.'" data-is_active="' . ($row->is_active == 0 ? '0' : '1') . '">
                    <i class="ri-edit-box-line ri-20px"></i>
                </button>';
            })
            ->rawColumns(['selectbox', 'is_active', 'actions'])
            ->make(true);
    }

    public function getArchivedParentsForDatatable($parentsQuery)
    {
        return datatables()->eloquent($parentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('actions', function ($row) {
                return
                '<div class="d-inline-block">
                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end m-0">
                        <li><a href="javascript:;" class="dropdown-item">'.trans('main.details').'</a></li>
                        <li>
                            <a href="javascript:;" class="dropdown-item"
                                id="restore-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                                data-bs-target="#restore-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                '.trans('main.restore').'
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li>
                            <a href="javascript:;" class="dropdown-item text-danger"
                                id="delete-button" data-id=' . $row->id . ' data-name_ar="' . $row->getTranslation('name', 'ar') . '" data-name_en="' . $row->getTranslation('name', 'en') . '"
                                data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                '.trans('main.delete').'
                            </a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['selectbox', 'actions'])
            ->make(true);
    }

    public function insertParent(array $request)
    {
        DB::beginTransaction();

        try {
            MyParent::create([
                'username' => $request['username'],
                'password' => Hash::make($request['username']),
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'gender' => $request['gender'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/parents.parent')]),
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

    public function updateParent($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $parent = MyParent::findOrFail($id);

            if (!empty($request['password'])) {
                $request['password'] = bcrypt(Hash::make($request['password']));
            } else {
                unset($request['password']);
            }

            $parent->update([
                'username' => $request['username'],
                'password' => $request['password'] ?? $parent->password,
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'gender' => $request['gender'],
                'is_active' => $request['is_active'],
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/parents.parent')]),
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

    public function deleteParent($id): array
    {
        DB::beginTransaction();

        try {
            $parent = MyParent::withTrashed()->select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($parent)) {
                return $dependencyCheck;
            }

            $parent->forceDelete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/parents.parent')]),
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

    public function archiveParent($id): array
    {
        DB::beginTransaction();

        try {
            $parent = MyParent::findOrFail($id);
            $parent->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.archived', ['item' => trans('admin/parents.parent')]),
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

    public function restoreParent($id): array
    {
        DB::beginTransaction();

        try {
            $parent = MyParent::onlyTrashed()->findOrFail($id);
            $parent->restore();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.restored', ['item' => trans('admin/parents.parent')]),
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

    public function deleteSelectedParents($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            $parents = MyParent::whereIn('id', $ids)
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($parents)) {
                return $dependencyCheck;
            }

            MyParent::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/parents.parents'))]),
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

    public function archiveSelectedParents($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            MyParent::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.archivedSelected', ['item' => strtolower(trans('admin/parents.parents'))]),
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

    public function restoreSelectedParents($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            MyParent::onlyTrashed()->whereIn('id', $ids)->restore();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.restoredSelected', ['item' => strtolower(trans('admin/parents.parents'))]),
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

    public function checkDependenciesForSingleDeletion($parent)
    {
        return $this->checkForSingleDependencies($parent, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($parents)
    {
        return $this->checkForMultipleDependencies($parents, $this->relationships, $this->transModelKey);
    }
}
