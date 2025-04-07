<div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-border-shadow-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/students.total_students') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['totalStudents'] }}</h4>
                        </div>
                        <small class="mb-0">{{ trans('admin/students.all_active_inactive') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded-3">
                            <div class="ri-graduation-cap-line ri-28px"></div>
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
                        <p class="text-heading mb-1">{{ trans('admin/students.active_students') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['activeStudents'] }}</h4>
                            <p class="text-success mb-1">
                                ({{ $pageStatistics['totalStudents'] > 0 ? round(($pageStatistics['activeStudents'] / $pageStatistics['totalStudents']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/students.currently_working') }}</small>
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
                        <p class="text-heading mb-1">{{ trans('admin/students.inactive_students') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['inactiveStudents'] }}</h4>
                            <p class="text-danger mb-1">
                                ({{ $pageStatistics['totalStudents'] > 0 ? round(($pageStatistics['inactiveStudents'] / $pageStatistics['totalStudents']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/students.paused_or_disabled') }}</small>
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
                        <p class="text-heading mb-1">{{ trans('admin/students.archived_students') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['archivedStudents'] }}</h4>
                            <p class="text-warning mb-1">
                                ({{ $pageStatistics['totalStudents'] > 0 ? round(($pageStatistics['archivedStudents'] / $pageStatistics['totalStudents']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/students.removed_or_deleted') }}</small>
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
    <div class="col-sm-6 col-xl-4">
        <div class="card card-border-shadow-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/students.exempted_students') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['exemptedStudents'] }}</h4>
                            <p class="text-info mb-1">
                                ({{ $pageStatistics['totalStudents'] > 0 ? round(($pageStatistics['exemptedStudents'] / $pageStatistics['totalStudents']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/students.scholarship_or_support') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-info rounded-3">
                            <div class="ri-hand-coin-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card card-border-shadow-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/students.discounted_students') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['discountedStudents'] }}</h4>
                            <p class="text-secondary mb-1">
                                ({{ $pageStatistics['totalStudents'] > 0 ? round(($pageStatistics['discountedStudents'] / $pageStatistics['totalStudents']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/students.receiving_discount') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-secondary rounded-3">
                            <div class="ri-discount-percent-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card card-border-shadow-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/students.top_grade') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['topGrade']?->grade?->name ?? 'N/A' }}</h4>
                            <p class="text-primary mb-1">
                                ({{ $pageStatistics['topGrade']?->student_count ?? 0 }} {{ trans('admin/students.student') }})
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/students.most_populated_grade') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded-3">
                            <div class="ri-numbers-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
