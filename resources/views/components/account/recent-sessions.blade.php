<div class="card mb-6">
    <h5 class="card-header">{{ trans('account.recentDevices') }}</h5>
    <div class="table-responsive">
        <table class="table table-border-bottom-0">
            <thead>
                <tr>
                    <th class="text-truncate">{{ trans('account.browser') }}</th>
                    <th class="text-truncate">{{ trans('account.device') }}</th>
                    <th class="text-truncate">{{ trans('account.location') }}</th>
                    <th class="text-truncate">{{ trans('account.lastActivity') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $session)
                    <tr>
                        <td class="text-truncate">
                            <i class="ri {{ $session['icon'] }} ri-24px text-success me-4"></i>
                            <span class="text-heading">{{ $session['browser'] }}</span>
                        </td>
                        <td class="text-truncate">{{ $session['device'] }}</td>
                        <td class="text-truncate">{{ $session['location'] }}</td>
                        <td class="text-truncate">
                            <h6 class="mb-0 align-items-center d-flex w-px-100 {{ $session['last_activity'] == trans('account.online') ? 'text-success' : 'text-warning' }}">
                                <i class="icon-base ri ri-circle-fill icon-10px me-1" style="font-size:8px;"></i>{{ $session['last_activity'] }}
                            </h6>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ trans('main.datatable.empty_table') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
