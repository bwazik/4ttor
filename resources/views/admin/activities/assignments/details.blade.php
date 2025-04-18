@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/assignments.assignments'))

@section('content')
    <div class="row g-6">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-1">
                        <div class="me-1">
                            <h5 class="mb-0">{{ $assignment->title }}</h5>
                            <p class="mb-0">{{ trans('main.mr') }}: <span class="fw-medium text-heading"> {{ $assignment->teacher->name }} </span></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-success rounded-pill">{{ $assignment->grade->name }}</span>
                            <i class="ri-share-forward-line ri-24px mx-4 cursor-pointer"></i>
                        </div>
                    </div>
                    <div class="card academy-content shadow-none border">
                        <div class="card-body pt-3">
                            <h5>{{ trans('admin/assignments.details') }}</h5>
                            <div class="d-flex flex-wrap row-gap-2">
                                <div class="me-12">
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-calendar-schedule-line ri-20px me-2"></i>{{ trans('main.deadline') }}: {{ isoFormat($assignment->deadline) }}
                                    </p>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-star-smile-line ri-20px me-2"></i>{{ trans('main.score') }}: {{ $assignment->score }}
                                    </p>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-survey-line ri-20px me-2"></i>{{ trans('main.grade') }}: {{ $assignment->grade->name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-group-2-line ri-20px me-2 me-2"></i>{{ trans('main.groups') }}:
                                        <p>
                                            @foreach($assignment->groups as $group)
                                                <span class="badge bg-label-success rounded-pill mb-3">{{ $group->name }}</span>
                                            @endforeach
                                        </p>
                                    </p>
                                </div>
                            </div>
                            <hr class="my-6" />
                            <h5>{{ trans('admin/assignments.instructions') }}</h5>
                            <p class="mb-6">
                                {{ $assignment->description ?: '-' }}
                            </p>
                            <hr class="my-6" />
                            <h5>{{ app()->getLocale() === 'ar' ? 'ال' : '' }}{{ trans('admin/teachers.teacher') }}</h5>
                            <div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper">
                                    <div class="avatar me-4">
                                        <img src="{{ $assignment->teacher->profile_pic ? asset('storage/profiles/teachers/'.$assignment->teacher->profile_pic) : asset('assets/img/avatars/default.jpg') }}" alt="Avatar" class="rounded-circle" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1">{{ $assignment->teacher->name }}</h6>
                                    <small>{{ trans('admin/teachers.teacher') }} {{ $assignment->teacher->subject->name }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <form id="add-form"
                action="{{ route('admin.assignments.files.upload', $assignment->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <x-basic-input divClasses="mb-3" type="file" name="file" label="{{ trans('main.files') }}" required/>
                <button type="submit" class="btn btn-primary w-100 d-block">{{ trans('main.submit') }}</button>
            </form>
            <hr class="my-4" />
            <div class="accordion stick-top accordion-custom-button" id="assignmentDetails">
                <div class="accordion-item active mb-0">
                    <div class="accordion-header border-bottom-0" id="headingOne">
                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                            data-bs-target="#assignmentfiles" aria-expanded="true" aria-controls="assignmentfiles">
                            <span class="d-flex flex-column">
                                <span class="h5 mb-0">{{ trans('admin/assignments.files') }}</span>
                                <span class="text-body fw-normal">{{ trans('admin/assignments.filesCount') }}: {{ $assignment->assignmentFiles->count() }}</span>
                            </span>
                        </button>
                    </div>
                    <div id="assignmentfiles" class="accordion-collapse collapse show" data-bs-parent="#assignmentDetails">
                        <div class="accordion-body py-4 border-top">
                            @forelse($assignment->assignmentFiles as $file)
                                <div class="d-flex align-items-center mb-4">
                                    <div class="me-2">
                                        @php
                                            $extension = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                            $imageSrc = match ($extension) {
                                                'pdf' => asset('assets/img/icons/misc/pdf.png'),
                                                'jpg', 'jpeg' => asset('assets/img/icons/misc/jpg.png'),
                                                'png' => asset('assets/img/icons/misc/png.png'),
                                                'doc', 'docx' => asset('assets/img/icons/misc/doc.png'),
                                                'xls', 'xlsx' => asset('assets/img/icons/misc/excel.png'),
                                                'txt' => asset('assets/img/icons/misc/txt.png'),
                                                default => asset('assets/img/icons/misc/file.png'),
                                            };
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="{{ $extension }} icon" style="width: 32px; height: 32px;" />
                                    </div>
                                    <span class="text-nowrap overflow-hidden text-truncate" style="max-width: 200px;" title="{{ $file->file_name }}">
                                        <a href="{{ route('admin.assignments.files.download', $file->id) }}" class="text-decoration-none">
                                            {{ $file->file_name }}
                                        </a>
                                        <small class="text-body d-block">
                                            @if($file->file_size / (1024 * 1024) >= 1)
                                                {{ number_format($file->file_size / (1024 * 1024), 2) }} MB
                                            @else
                                                {{ number_format($file->file_size / 1024, 2) }} KB
                                            @endif
                                        </small>
                                    </span>
                                    <div class="ms-auto">
                                        <button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light"
                                            id="delete-button" data-id="{{ $file->id }}" data-file_name="{{ $file->file_name }}"
                                            data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                            <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p>No files uploaded for this assignment.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/assignments.assignment')]) }}"
        action="{{ route('admin.assignments.files.delete') }}" id  submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
        @include('partials.delete-modal-body')
    </x-modal>
@endsection

@section('page-js')
    <script>
        const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png'];
        handleProfilePicSubmit('#add-form', 6, allowedExtensions);

        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('file_name')}`
            }
        });

        handleDeletionFormSubmit('#delete-form', '#delete-modal')
    </script>
@endsection
