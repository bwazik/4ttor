<div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-border-shadow-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/groups.total_groups') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['totalGroups'] }}</h4>
                        </div>
                        <small class="mb-0">{{ trans('admin/groups.all_active_inactive') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded-3">
                            <div class="ri-group-2-line ri-28px"></div>
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
                        <p class="text-heading mb-1">{{ trans('admin/groups.active_groups') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['activeGroups'] }}</h4>
                            <p class="text-success mb-1">
                                ({{ $pageStatistics['totalGroups'] > 0 ? round(($pageStatistics['activeGroups'] / $pageStatistics['totalGroups']) * 100, 2) : 0 }}%)                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/groups.currently_running') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-success rounded-3">
                            <div class="ri-check-line ri-28px"></div>
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
                        <p class="text-heading mb-1">{{ trans('admin/groups.inactive_groups') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['inactiveGroups'] }}</h4>
                            <p class="text-danger mb-1">
                                ({{ $pageStatistics['totalGroups'] > 0 ? round(($pageStatistics['inactiveGroups'] / $pageStatistics['totalGroups']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/groups.paused_or_closed') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-danger rounded-3">
                            <div class="ri-forbid-line ri-28px"></div>
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
                        <p class="text-heading mb-1">{{ trans('admin/groups.top_grade') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['topGrade']?->grade?->name ?? 'N/A' }}</h4>
                            <p class="text-primary mb-1">
                                ({{ $pageStatistics['topGrade']?->group_count ?? 0 }} {{ trans('admin/groups.group') }})
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/groups.most_populated_grade') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-warning rounded-3">
                            <div class="ri-numbers-line ri-28px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
