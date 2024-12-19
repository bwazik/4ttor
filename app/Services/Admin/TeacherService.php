<?php

namespace App\Services\Admin;

use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class TeacherService
{
    public function getTeachersForDatatable($teachersQuery)
    {
        return datatables()->eloquent($teachersQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->addColumn('details', function ($row) {
                $profilePic = $row->profile_pic ?
                '<img src="' . asset('storage/' . $row->profile_picture) . '" alt="Profile Picture" class="rounded-circle">' :
                '<img src="' . asset('assets/img/avatars/19.png') . '" alt="Profile Picture" class="rounded-circle">';

                $teacherName = App::getLocale() == 'ar' ?
                trans('admin/teachers.teacher') . ' ' . $row->subject->name :
                $row->subject->name . ' ' .  trans('admin/teachers.teacher');

                return
                '<div class="d-flex justify-content-start align-items-center">
                    <div class="avatar-wrapper">
                        <div class="avatar me-2">'.$profilePic.'</div>
                    </div>
                    <div class="d-flex flex-column align-items-start">
                        <span class="emp_name text-truncate text-heading fw-medium">'.$row->name.'</span>
                        <small class="emp_post text-truncate">'.$teacherName.'</small>
                    </div>
                </div>';
            })
            ->editColumn('plan_id', function ($row) {
                return $row->plan_id ? $row->plan_id : '-';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge rounded-pill bg-label-success" text-capitalized="">'.trans('main.active').'</span>' : '<span class="badge rounded-pill bg-label-secondary" text-capitalized="">'.trans('main.inactive').'</span>';
            })
            ->addColumn('actions', function ($row) {
                $gradeIds = $row->grades->pluck('id')->toArray();
                $grades = implode(',', $gradeIds);

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
                    data-password="" data-subject_id=' . $row->subject_id . ' data-grades='. $grades .' data-is_active="' . ($row->is_active == 0 ? '0' : '1') . '">
                    <i class="ri-edit-box-line ri-20px"></i>
                </button>';
            })
            ->rawColumns(['selectbox', 'details', 'is_active', 'actions'])
            ->make(true);
    }

    public function getArchivedTeachersForDatatable($teachersQuery)
    {
        return datatables()->eloquent($teachersQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->addColumn('details', function ($row) {
                $profilePic = $row->profile_pic ?
                '<img src="' . asset('storage/' . $row->profile_picture) . '" alt="Profile Picture" class="rounded-circle">' :
                '<img src="' . asset('assets/img/avatars/19.png') . '" alt="Profile Picture" class="rounded-circle">';

                $teacherName = App::getLocale() == 'ar' ?
                trans('admin/teachers.teacher') . ' ' . $row->subject->name :
                $row->subject->name . ' ' .  trans('admin/teachers.teacher');

                return
                '<div class="d-flex justify-content-start align-items-center">
                    <div class="avatar-wrapper">
                        <div class="avatar me-2">'.$profilePic.'</div>
                    </div>
                    <div class="d-flex flex-column align-items-start">
                        <span class="emp_name text-truncate text-heading fw-medium">'.$row->name.'</span>
                        <small class="emp_post text-truncate">'.$teacherName.'</small>
                    </div>
                </div>';
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
            ->rawColumns(['selectbox', 'details', 'actions'])
            ->make(true);
    }

    public function insertTeacher(array $request)
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::create([
                'username' => $request['username'],
                'password' => Hash::make($request['username']),
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'subject_id' => $request['subject_id'],
            ]);

            $teacher->grades()->attach($request['grades']);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/teachers.teacher')]),
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

    public function updateTeacher($id, array $request): array
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::findOrFail($id);

            if (!empty($request['password'])) {
                $request['password'] = bcrypt(Hash::make($request['password']));
            } else {
                unset($request['password']);
            }

            $teacher->update([
                'username' => $request['username'],
                'password' => $request['password'] ?? $teacher->password,
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'subject_id' => $request['subject_id'],
                'is_active' => $request['is_active'],
            ]);

            $teacher->grades()->sync($request['grades'] ?? []);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/teachers.teacher')]),
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

    public function deleteTeacher($id): array
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::withTrashed()->findOrFail($id);
            $teacher->forceDelete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/teachers.teacher')]),
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

    public function archiveTeacher($id): array
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.archived', ['item' => trans('admin/teachers.teacher')]),
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

    public function restoreTeacher($id): array
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::onlyTrashed()->findOrFail($id);
            $teacher->restore();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.restored', ['item' => trans('admin/teachers.teacher')]),
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

    public function deleteSelectedTeachers($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            Teacher::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/teachers.teachers'))]),
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

    public function archiveSelectedTeachers($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            Teacher::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.archivedSelected', ['item' => strtolower(trans('admin/teachers.teachers'))]),
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

    public function restoreSelectedTeachers($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            Teacher::onlyTrashed()->whereIn('id', $ids)->restore();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.restoredSelected', ['item' => strtolower(trans('admin/teachers.teachers'))]),
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
}
