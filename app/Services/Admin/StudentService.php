<?php

namespace App\Services\Admin;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    public function getStudentsForDatatable($studentsQuery)
    {
        return datatables()->eloquent($studentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->addColumn('details', function ($row) {
                $profilePic = $row->profile_pic ?
                '<img src="' . asset('storage/' . $row->profile_picture) . '" alt="Profile Picture" class="rounded-circle">' :
                '<img src="' . asset('assets/img/avatars/19.png') . '" alt="Profile Picture" class="rounded-circle">';

                return
                '<div class="d-flex justify-content-start align-items-center">
                    <div class="avatar-wrapper">
                        <div class="avatar me-2">'.$profilePic.'</div>
                    </div>
                    <div class="d-flex flex-column align-items-start">
                        <span class="emp_name text-truncate text-heading fw-medium">'.$row->name.'</span>
                        <small class="emp_post text-truncate">'.$row->grade->name.'</small>
                    </div>
                </div>';
            })
            ->editColumn('parent_id', function ($row) {
                return $row->parent_id ? $row->parent_id : '-';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge rounded-pill bg-label-success" text-capitalized="">'.trans('main.active').'</span>' : '<span class="badge rounded-pill bg-label-secondary" text-capitalized="">'.trans('main.inactive').'</span>';
            })
            ->addColumn('actions', function ($row) {
                $teacherIds = $row->teachers->pluck('id')->toArray();
                $teachers = implode(',', $teacherIds);

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
                    data-password="" data-birth_date="'.$row->birth_date.'" data-gender="'.$row->gender.'" data-grade_id=' . $row->grade_id . ' data-teachers='. $teachers .' data-is_active="' . ($row->is_active == 0 ? '0' : '1') . '">
                    <i class="ri-edit-box-line ri-20px"></i>
                </button>';
            })
            ->rawColumns(['selectbox', 'details', 'is_active', 'actions'])
            ->make(true);
    }

    public function getArchivedStudentsForDatatable($studentsQuery)
    {
        return datatables()->eloquent($studentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', function ($row) {
                $btn = '<td class="dt-checkboxes-cell"><input type="checkbox" value="' . $row->id . '" class="dt-checkboxes form-check-input"></td>';
                return $btn;
            })
            ->addColumn('details', function ($row) {
                $profilePic = $row->profile_pic ?
                '<img src="' . asset('storage/' . $row->profile_picture) . '" alt="Profile Picture" class="rounded-circle">' :
                '<img src="' . asset('assets/img/avatars/19.png') . '" alt="Profile Picture" class="rounded-circle">';

                return
                '<div class="d-flex justify-content-start align-items-center">
                    <div class="avatar-wrapper">
                        <div class="avatar me-2">'.$profilePic.'</div>
                    </div>
                    <div class="d-flex flex-column align-items-start">
                        <span class="emp_name text-truncate text-heading fw-medium">'.$row->name.'</span>
                        <small class="emp_post text-truncate">'.$row->grade->name.'</small>
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

    public function insertStudent(array $request)
    {
        DB::beginTransaction();

        try {
            $student = Student::create([
                'username' => $request['username'],
                'password' => Hash::make($request['username']),
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'birth_date' => $request['birth_date'],
                'gender' => $request['gender'],
                'grade_id' => $request['grade_id'],
            ]);

            $student->teachers()->attach($request['teachers']);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.added', ['item' => trans('admin/students.student')]),
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

    public function updateStudent($id, array $data): array
    {
        DB::beginTransaction();

        try {
            $student = Student::findOrFail($id);

            if (!empty($data['password'])) {
                $data['password'] = bcrypt(Hash::make($data['password']));
            } else {
                unset($data['password']);
            }

            $student->update([
                'username' => $data['username'],
                'password' => $data['password'] ?? $student->password,
                'name' => ['ar' => $data['name_ar'], 'en' => $data['name_en']],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'grade_id' => $data['grade_id'],
                'is_active' => $data['is_active'],
            ]);

            $student->teachers()->sync($data['teachers'] ?? []);

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.edited', ['item' => trans('admin/students.student')]),
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

    public function deleteStudent($id): array
    {
        DB::beginTransaction();

        try {
            $student = Student::withTrashed()->findOrFail($id);
            $student->forceDelete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.deleted', ['item' => trans('admin/students.student')]),
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

    public function archiveStudent($id): array
    {
        DB::beginTransaction();

        try {
            $student = Student::findOrFail($id);
            $student->delete();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.archived', ['item' => trans('admin/students.student')]),
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

    public function restoreStudent($id): array
    {
        DB::beginTransaction();

        try {
            $student = Student::onlyTrashed()->findOrFail($id);
            $student->restore();

            DB::commit();

            return [
                'status' => 'success',
                'message' => trans('main.restored', ['item' => trans('admin/students.student')]),
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

    public function deleteSelectedStudents($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            Student::withTrashed()->whereIn('id', $ids)->forceDelete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.deletedSelected', ['item' => strtolower(trans('admin/students.students'))]),
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

    public function archiveSelectedStudents($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            Student::whereIn('id', $ids)->delete();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.archivedSelected', ['item' => strtolower(trans('admin/students.students'))]),
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

    public function restoreSelectedStudents($ids)
    {
        if (empty($ids)) {
            return [
                'status' => 'error',
                'message' => trans('main.noItemsSelected'),
            ];
        }

        DB::beginTransaction();

        try {
            Student::onlyTrashed()->whereIn('id', $ids)->restore();

            DB::commit();
            return [
                'status' => 'success',
                'message' => trans('main.restoredSelected', ['item' => strtolower(trans('admin/students.students'))]),
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
