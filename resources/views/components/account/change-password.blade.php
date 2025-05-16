<div class="card mb-6">
    <h5 class="card-header">{{ trans('account.changePassword') }}</h5>
    <div class="card-body pt-1">
        <form id="change-password-form" action="{{ $action }}" method="POST"
            autocomplete="off">
            @csrf
            <div class="row">
                <x-basic-input divClasses="mb-5 col-md-6" type="password" name="currentPassword"
                    label="{{ trans('account.currentPassword') }}" required />
            </div>
            <div class="row g-5 mb-6">
                <x-basic-input context="modal" type="password" name="newPassword"
                    label="{{ trans('account.newPassword') }}" required />
                <x-basic-input context="modal" type="password" name="confirmNewPassword"
                    label="{{ trans('account.confirmNewPassword') }}" required />
            </div>
            <h6 class="text-body">{{ trans('account.passwordRequirements') }}:</h6>
            <ul class="ps-4 mb-0">
                <li class="mb-4">{{ trans('account.passwordMinLength') }}</li>
                <li class="mb-4">{{ trans('account.passwordCase') }}</li>
                <li class="mb-4">{{ trans('account.passwordNumbers') }}</li>
                <li>{{ trans('account.passwordSpecialChar') }}</li>
            </ul>
            <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">{{ trans('main.submit') }}</button>
                <button type="reset"
                    class="btn btn-outline-secondary">{{ trans('main.cancel') }}</button>
            </div>
        </form>
    </div>
</div>
