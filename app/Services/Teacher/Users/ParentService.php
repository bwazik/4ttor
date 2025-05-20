<?php

namespace App\Services\Teacher\Users;

use App\Models\Student;
use App\Models\MyParent;
use App\Traits\PublicValidatesTrait;
use Illuminate\Support\Facades\Hash;
use App\Traits\DatabaseTransactionTrait;
use App\Traits\PreventDeletionIfRelated;

class ParentService
{
    use PreventDeletionIfRelated, PublicValidatesTrait, DatabaseTransactionTrait;

    protected $teacherId;

    public function __construct()
    {
        $this->teacherId = auth()->guard('teacher')->user()->id;
    }

    public function getParentsForDatatable($parentsQuery)
    {
        return datatables()->eloquent($parentsQuery)
            ->addIndexColumn()
            ->addColumn('selectbox', fn($row) => generateSelectbox($row->uuid))
            ->editColumn('name', fn($row) => $row->name)
            ->editColumn('is_active', fn($row) => formatActiveStatus($row->is_active))
            ->addColumn('actions', fn($row) => $this->generateActionButtons($row))
            ->filterColumn('is_active', fn($query, $keyword) => filterByStatus($query, $keyword))
            ->rawColumns(['selectbox', 'is_active', 'actions'])
            ->make(true);
    }

    private function generateActionButtons($row)
    {
        $studentIds = $row->students->pluck('uuid')->toArray();
        $students = implode(',', $studentIds);

        return
            '<div class="d-inline-block">' .
                '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' .
                    '<i class="ri-more-2-line"></i>' .
                '</a>' .
                '<ul class="dropdown-menu dropdown-menu-end m-0">' .
                    '<li>
                        <a target="_blank" href="#" class="dropdown-item">'.trans('main.details').'</a>
                    </li>' .
                    '<div class="dropdown-divider"></div>' .
                    '<li>' .
                        '<a href="javascript:;" class="dropdown-item text-danger" ' .
                            'id="delete-button" ' .
                            'data-id="' . $row->uuid . '" ' .
                            'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                            'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                            'data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">' .
                            trans('main.delete') .
                        '</a>' .
                    '</li>' .
                '</ul>' .
            '</div>' .
            '<button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light" ' .
                'tabindex="0" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" ' .
                'id="edit-button" ' .
                'data-id="' . $row->uuid . '" ' .
                'data-name_ar="' . $row->getTranslation('name', 'ar') . '" ' .
                'data-name_en="' . $row->getTranslation('name', 'en') . '" ' .
                'data-username="' . $row->username . '" ' .
                'data-email="' . $row->email . '" ' .
                'data-phone="' . $row->phone . '" ' .
                'data-password="" ' .
                'data-gender="' . $row->gender . '" ' .
                'data-students="' . $students . '" ' .
                'data-is_active="' . ($row->is_active ? '1' : '0') . '">' .
                '<i class="ri-edit-box-line ri-20px"></i>' .
            '</button>';
    }

    public function insertParent(array $request)
    {
        return $this->executeTransaction(function () use ($request)
        {
            $parent = MyParent::create([
                'username' => $request['username'],
                'password' => Hash::make($request['username']),
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'gender' => $request['gender'],
            ]);

            $studentIds = !empty($request['students'])
                ? Student::whereIn('uuid', $request['students'])->pluck('id')->toArray()
                : [];

            $this->syncStudentParentRelation($studentIds, $parent->id, isAdmin());

            return $this->successResponse(trans('main.added', ['item' => trans('admin/parents.parent')]));
        });
    }

    public function updateParent($id, array $request): array
    {
        return $this->executeTransaction(function () use ($id, $request)
        {
            $parent = MyParent::findOrFail($id);

            $this->processPassword($request);

            $parent->update([
                'username' => $request['username'],
                'password' => $request['password'] ?? $parent->password,
                'name' => ['ar' => $request['name_ar'], 'en' => $request['name_en']],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'gender' => $request['gender'],
                'is_active' => $request['is_active'],
            ]);

            $studentIds = !empty($request['students'])
                ? Student::whereIn('uuid', $request['students'])->pluck('id')->toArray()
                : [];

            $this->syncStudentParentRelation($studentIds, $parent->id, isAdmin());

            return $this->successResponse(trans('main.edited', ['item' => trans('admin/parents.parent')]));
        });
    }

    public function deleteParent($id): array
    {
        return $this->executeTransaction(function () use ($id)
        {
            MyParent::findOrFail($id)->delete();

            return $this->successResponse(trans('main.deleted', ['item' => trans('admin/parents.parent')]));
        });
    }

    public function deleteSelectedParents($ids)
    {
        if ($validationResult = $this->validateSelectedItems((array) $ids))
            return $validationResult;

        return $this->executeTransaction(function () use ($ids)
        {
            MyParent::whereIn('id', $ids)->delete();

            return $this->successResponse(trans('main.deletedSelected', ['item' => trans('admin/parents.parent')]));
        });
    }
}
