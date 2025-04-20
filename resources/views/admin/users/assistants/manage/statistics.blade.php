<div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-border-shadow-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">{{ trans('admin/assistants.total_assistants') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['totalAssistants'] }}</h4>
                        </div>
                        <small class="mb-0">{{ trans('admin/assistants.all_active_inactive') }}</small>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded-3">
                            <div class="ri-user-star-line ri-28px"></div>
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
                        <p class="text-heading mb-1">{{ trans('admin/assistants.active_assistants') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['activeAssistants'] }}</h4>
                            <p class="text-success mb-1">
                                ({{ $pageStatistics['totalAssistants'] > 0 ? round(($pageStatistics['activeAssistants'] / $pageStatistics['totalAssistants']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/assistants.currently_working') }}</small>
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
                        <p class="text-heading mb-1">{{ trans('admin/assistants.inactive_assistants') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['inactiveAssistants'] }}</h4>
                            <p class="text-danger mb-1">
                                ({{ $pageStatistics['totalAssistants'] > 0 ? round(($pageStatistics['inactiveAssistants'] / $pageStatistics['totalAssistants']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/assistants.paused_or_disabled') }}</small>
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
                        <p class="text-heading mb-1">{{ trans('admin/assistants.archived_assistants') }}</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $pageStatistics['archivedAssistants'] }}</h4>
                            <p class="text-warning mb-1">
                                ({{ $pageStatistics['totalAssistants'] > 0 ? round(($pageStatistics['archivedAssistants'] / $pageStatistics['totalAssistants']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                        <small class="mb-0">{{ trans('admin/assistants.removed_or_deleted') }}</small>
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
</div>
