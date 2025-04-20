<div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-border-shadow-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/teachers.total_teachers') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['totalTeachers'] }}</h4>
                        </div>
                        <small class="mb-0">{{ trans('admin/teachers.all_active_inactive') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded-3">
                            <div class="ri-presentation-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-border-shadow-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/teachers.active_teachers') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['activeTeachers'] }}</h4>
                            <p class="text-success mb-1">
                                ({{ $pageStatistics['totalTeachers'] > 0 ? round(($pageStatistics['activeTeachers'] / $pageStatistics['totalTeachers']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/teachers.currently_active') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-success rounded-3">
                            <div class="ri-user-follow-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-border-shadow-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/teachers.inactive_teachers') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['inactiveTeachers'] }}</h4>
                            <p class="text-danger mb-1">
                                ({{ $pageStatistics['totalTeachers'] > 0 ? round(($pageStatistics['inactiveTeachers'] / $pageStatistics['totalTeachers']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/teachers.paused_or_disabled') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-danger rounded-3">
                            <div class="ri-user-unfollow-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-border-shadow-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/teachers.archived_teachers') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['archivedTeachers'] }}</h4>
                            <p class="text-warning mb-1">
                                ({{ $pageStatistics['totalTeachers'] > 0 ? round(($pageStatistics['archivedTeachers'] / $pageStatistics['totalTeachers']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/teachers.removed_or_deleted') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-warning rounded-3">
                            <div class="ri-user-forbid-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card card-border-shadow-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/teachers.top_subject') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['topSubject']?->subject?->name ?? 'N/A' }}</h4>
                            <p class="text-info mb-1">
                                ({{ $pageStatistics['topSubject']?->teacher_count ?? 0 }} {{ trans('admin/teachers.teacher') }})
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/teachers.most_populated_subject') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-info rounded-3">
                            <div class="ri-book-shelf-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
