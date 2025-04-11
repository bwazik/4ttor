<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <div class="card-header d-flex align-items-center justify-content-between flex-column flex-md-row border-bottom">
            <div class="head-label text-center">
                <h5 class="card-title mb-0">{{ trans('main.datatableTitle', ['item' => trans('admin/answers.answers')]) }}</h5>
            </div>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    <button id="add-answer-button" class="btn btn-primary waves-effect waves-light" tabindex="0"
                        data-bs-toggle="offcanvas" data-bs-target="#add-answer-modal">
                        <span>
                            <i class="ri-add-line ri-16px me-sm-2"></i>
                            <span class="d-none d-sm-inline-block">{{ trans('main.addItem', ['item' => trans('admin/answers.answer')]) }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <table id="datatable{{ $question->id }}" class="datatables-basic table">
            <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>{{ trans('admin/answers.answer_text') }}</th>
                    <th>{{ trans('main.is_correct') }}</th>
                    <th>{{ trans('main.score') }}</th>
                    <th>{{ trans('main.actions') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
