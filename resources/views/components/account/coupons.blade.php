<div class="card mb-6">
    <h5 class="card-header">{{ trans('account.redeemCoupon') }}</h5>
    <div class="card-body pt-1">
        <form id="redeem-form" action="{{ $action }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-6">
                <div class="row gx-5">
                    <x-basic-input divClasses="col-12 mb-3" type="text" name="code" label="{{ trans('main.code') }}" placeholder="{{ trans('admin/coupons.placeholders.code') }}" required/>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">{{ trans('account.redeem') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
