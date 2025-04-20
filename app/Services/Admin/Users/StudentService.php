<?php

namespace App\Services\Admin\Users;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use App\Traits\PreventDeletionIfRelated;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PublicValidatesTrait;

class StudentService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $relationships = ['invoices', 'studentAccount', 'receipts', 'refunds', 'attendances', 'assignmentSubmissions'];
    protected $transModelKey = 'admin/students.students';

    public function getStudentsForDatatable($studentsQuery)
    {
        return datatables()->eloquent($studentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->addColumn('details', fn($row) => generateDetailsColumn($row->name, $row->profile_pic, 'storage/profiles/students', $row->email))
            ->editColumn('grade_id', fn($row) => formatRelation($row->grade_id, $row->grade, 'name'))
            ->editColumn('parent_id', fn($row) => formatRelation($row->parent_id, $row->parent, 'name', 'admin.parents.details'))
            ->editColumn('is_active', fn($row) => formatActiveStatus($row->is_active))
            ->addColumn('actions', fn($row) => $this->generateUnarchivedActionButtons($row))
            ->filterColumn('details', fn($query, $keyword) => filterDetailsColumn($query, $keyword, 'email'))
            ->filterColumn('grade_id', fn($query, $keyword) => filterByRelation($query, 'grade', 'name', $keyword))
            ->filterColumn('parent_id', fn($query, $keyword) => filterByRelation($query, 'parent', 'name', $keyword))
            ->filterColumn('is_active', fn($query, $keyword) => filterByStatus($query, $keyword))
            ->rawColumns(['selectbox', 'details', 'grade_id', 'parent_id', 'is_active', 'actions'])
            ->make(true);
    }

    private function generateUnarchivedActionButtons($row)
    {
        $teacherIds = $row->teachers->pluck('id')->toArray();
        $groupIds = $row->groups->pluck('id')->toArray();
        $teachers = implode(',', $teacherIds);
        $groups = implode(',', $groupIds);

        return
            '<div class="d-inline-block">' .
                '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' .
                    '<i class="ri-more-2-line"></i>' .
                '</a>' .
                '<ul class="dropdown-menu dropdown-menu-end m-0">' .
                    '<li>
                        <a target="_blank" href="'.route('admin.students.details', $row->id).'" class="dropdown-item">'.trans('main.details').'</a>
                    </li>' .
                    '<li>' .
                        '<a href="javascript:;" class="dropdown-item" ' .
                            'id="archive-button" ' .
                            'data-id="' . $row->id . '" ' .
                            'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                            'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                            'data-bs-target="#archive-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                            trans('main.archive').
                        '</a>' .
                    '<li>' .
                    '<div class="dropdown-divider"></div>' .
                    '<li>' .
                        '<a href="javascript:;" class="dropdown-item text-danger" ' .
                            'id="delete-button" ' .
                            'data-id="' . $row->id . '" ' .
                            'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                            'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                            'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                            trans('main.delete').
                        '</a>' .
                    '</li>' .
                '</ul>' .
            '</div>' .
            '<button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light" ' .
                'tabindex="0" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" ' .
                'id="edit-button" ' .
                'data-id="' . $row->id . '" ' .
                'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                'data-username="' . $row->username . '" ' .
                'data-email="' . $row->email . '" ' .
                'data-phone="' . $row->phone . '" ' .
                'data-password="" ' .
                'data-birth_date="' . $row->birth_date . '" ' .
                'data-gender="' . $row->gender . '" ' .
                'data-grade_id="' . $row->grade_id . '" ' .
                'data-parent_id="' . $row->parent_id . '" ' .
                'data-teachers="' . $teachers . '" ' .
                'data-groups="' . $groups . '" ' .
                'data-is_active="' . ($row->is_active ? '1' : '0') . '">' .
                '<i class="ri-edit-box-line ri-20px"></i>' .
            '</button>';
    }

    public function getArchivedStudentsForDatatable($studentsQuery)
    {
        return datatables()->eloquent($studentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->id))
            ->addColumn('details', fn($row) => generateDetailsColumn($row->name, $row->profile_pic, 'storage/profiles/students', $row->email))
            ->addColumn('actions', fn($row) => $this->generateArchivedActionButtons($row))
            ->filterColumn('details', fn($query, $keyword) => filterDetailsColumn($query, $keyword, 'email'))
            ->rawColumns(['selectbox', 'details', 'actions'])
            ->make(true);
    }

    private function generateArchivedActionButtons($row)
    {
        return
            '<div class="d-inline-block">' .
                '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' .
                    '<i class="ri-more-2-line"></i>' .
                '</a>' .
                '<ul class="dropdown-menu dropdown-menu-end m-0">' .
                    '<li>' .
                        '<a href="javascript:;" class="dropdown-item" ' .
                            'id="restore-button" ' .
                            'data-id="' . $row->id . '" ' .
                            'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                            'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                            'data-bs-target="#restore-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                            trans('main.restore') .
                        '</a>' .
                    '</li>' .
                    '<div class="dropdown-divider"></div>' .
                    '<li>' .
                        '<a href="javascript:;" class="dropdown-item text-danger" ' .
                            'id="delete-button" ' .
                            'data-id="' . $row->id . '" ' .
                            'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                            'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                            'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                            trans('main.delete') .
                        '</a>' .
                    '</li>' .
                '</ul>' .
            '</div>';
    }

    public function insertStudent(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            if ($validationResult = $this->validateTeacherGradeAndGroups($request['teachers'], $request['groups'], $request['grade_id']))
                return $validationResult;

            $student = Student::create([
                'username' => $request['username'],
                'password' => Hash::make($request['username']),
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'birth_date' => $request['birth_date'],
                'gender' => $request['gender'],
                'grade_id' => $request['grade_id'],
                'parent_id' => $request['parent_id'] ?? null,
            ]);

            $student->teachers()->attach($request['teachers']);
            $student->groups()->attach($request['groups']);

            return $this->successResponse(trans('main.added', ['item' => trans('admin/students.student')]));
        });
    }

    public function updateStudent($id, array $request): array
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            if ($validationResult = $this->validateTeacherGradeAndGroups($request['teachers'], $request['groups'], $request['grade_id']))
                return $validationResult;

            $student = Student::findOrFail($id);

            $this->processPassword($request);

            $student->update([
                'username' => $request['username'],
                'password' => $request['password'] ?? $student->password,
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'birth_date' => $request['birth_date'],
                'gender' => $request['gender'],
                'grade_id' => $request['grade_id'],
                'parent_id' => $request['parent_id'] ?? null,
                'is_active' => $request['is_active'],
            ]);

            $student->teachers()->sync($request['teachers'] ?? []);
            $student->groups()->sync($request['groups'] ?? []);

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/students.student')]));
        });
    }

    public function deleteStudent($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            $student = Student::withTrashed()->select('id', 'name')->findOrFail($id);

            if ($dependencyCheck = $this->checkDependenciesForSingleDeletion($student)) {
                return $dependencyCheck;
            }

            $student->forceDelete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/students.student')]));
        });
    }

    public function archiveStudent($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            Student::findOrFail($id)->delete();

            return $this->successResponse(trans('main.archived', ['item' => trans('admin/students.student')]));
        });
    }

    public function restoreStudent($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            Student::onlyTrashed()->findOrFail($id)->restore();

            return $this->successResponse(trans('main.restored', ['item' => trans('admin/students.student')]));
        });
    }

    public function deleteSelectedStudents($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            $students = Student::whereIn('id', $ids)
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

            if ($dependencyCheck = $this->checkDependenciesForMultipleDeletion($students)) {
                return $dependencyCheck;
            }

            Student::withTrashed()->whereIn('id', $ids)->forceDelete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/students.student')]));
        });
    }

    public function archiveSelectedStudents($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            Student::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.archivedSelected', ['item' => trans('admin/students.student')]));
        });
    }

    public function restoreSelectedStudents($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            Student::onlyTrashed()->whereIn('id', $ids)->restore();

            return $this->successResponse(trans('main.restoredSelected', ['item' => trans('admin/students.student')]));
        });
    }

    public function checkDependenciesForSingleDeletion($teacher): array|null
    {
        return $this->checkForSingleDependencies($teacher, $this->relationships, $this->transModelKey);
    }

    public function checkDependenciesForMultipleDeletion($teachers)
    {
        return $this->checkForMultipleDependencies($teachers, $this->relationships, $this->transModelKey);
    }

    // public function getStudentAccountBalance($id): float
    // {
    //     $studentAccount = StudentAccount::where('student_id', $id)->select('debit', 'credit')->get();

    //     if ($studentAccount->isEmpty()) {
    //         return 0.00;
    //     }

    //     $debit = $studentAccount->sum('debit');
    //     $credit = $studentAccount->sum('credit');

    //     return round($debit - $credit, 2);
    // }
}
