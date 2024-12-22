<!-- Add Modal -->
<x-modal modalType="add" modalSize="modal-xl" modalTitle="{{ trans('main.addItem', ['item' => trans('admin/plans.plan')]) }}" action="{{ route('admin.plans.insert') }}">
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/plans.placeholders.name_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/plans.placeholders.name_en') }}" required/>
        <x-basic-input context="modal" price divClasses="col-12 col-md-4" type="number" name="monthly_price" label="{{ trans('admin/plans.monthly_price') }}" placeholder="0.00" required/>
        <x-basic-input context="modal" price divClasses="col-12 col-md-4" type="number" name="term_price" label="{{ trans('admin/plans.term_price') }}" placeholder="0.00" required/>
        <x-basic-input context="modal" price divClasses="col-12 col-md-4" type="number" name="year_price" label="{{ trans('admin/plans.year_price') }}" placeholder="0.00" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="student_limit" label="{{ trans('admin/plans.student_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="parent_limit" label="{{ trans('admin/plans.parent_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="assistant_limit" label="{{ trans('admin/plans.assistant_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="group_limit" label="{{ trans('admin/plans.group_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="quiz_monthly_limit" label="{{ trans('admin/plans.quiz_monthly_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="quiz_term_limit" label="{{ trans('admin/plans.quiz_term_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="quiz_year_limit" label="{{ trans('admin/plans.quiz_year_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="assignment_monthly_limit" label="{{ trans('admin/plans.assignment_monthly_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="assignment_term_limit" label="{{ trans('admin/plans.assignment_term_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="assignment_year_limit" label="{{ trans('admin/plans.assignment_year_limit') }}" placeholder="5" required/>
        <x-select-input context="modal" divClasses="col-12 col-md-4" name="attendance_reports" label="{{ trans('admin/plans.attendance_reports') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-select-input context="modal" divClasses="col-12 col-md-4" name="financial_reports" label="{{ trans('admin/plans.financial_reports') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-select-input context="modal" divClasses="col-12 col-md-4" name="performance_reports" label="{{ trans('admin/plans.performance_reports') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-select-input context="modal" divClasses="col-12" name="whatsapp_messages" label="{{ trans('admin/plans.whatsapp_messages') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-text-area context="modal" name="description_ar" label="{{ trans('main.description_ar') }}" placeholder="{{ trans('main.placeholders.description_ar') }}"/>
        <x-text-area context="modal" name="description_en" label="{{ trans('main.description_en') }}" placeholder="{{ trans('main.placeholders.description_en') }}"/>
    </div>
</x-modal>
<!-- Edit Modal -->
<x-modal modalType="edit" modalSize="modal-xl" modalTitle="{{ trans('main.editItem', ['item' => trans('admin/plans.plan')]) }}" action="{{ route('admin.plans.update') }}" id>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/plans.placeholders.name_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/plans.placeholders.name_en') }}" required/>
        <x-basic-input context="modal" price divClasses="col-12 col-md-4" type="number" name="monthly_price" label="{{ trans('admin/plans.monthly_price') }}" placeholder="0.00" required/>
        <x-basic-input context="modal" price divClasses="col-12 col-md-4" type="number" name="term_price" label="{{ trans('admin/plans.term_price') }}" placeholder="0.00" required/>
        <x-basic-input context="modal" price divClasses="col-12 col-md-4" type="number" name="year_price" label="{{ trans('admin/plans.year_price') }}" placeholder="0.00" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="student_limit" label="{{ trans('admin/plans.student_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="parent_limit" label="{{ trans('admin/plans.parent_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="assistant_limit" label="{{ trans('admin/plans.assistant_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-3" type="number" name="group_limit" label="{{ trans('admin/plans.group_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="quiz_monthly_limit" label="{{ trans('admin/plans.quiz_monthly_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="quiz_term_limit" label="{{ trans('admin/plans.quiz_term_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="quiz_year_limit" label="{{ trans('admin/plans.quiz_year_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="assignment_monthly_limit" label="{{ trans('admin/plans.assignment_monthly_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="assignment_term_limit" label="{{ trans('admin/plans.assignment_term_limit') }}" placeholder="5" required/>
        <x-basic-input context="modal" divClasses="col-12 col-md-4" type="number" name="assignment_year_limit" label="{{ trans('admin/plans.assignment_year_limit') }}" placeholder="5" required/>
        <x-select-input context="modal" divClasses="col-12 col-md-4" name="attendance_reports" label="{{ trans('admin/plans.attendance_reports') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-select-input context="modal" divClasses="col-12 col-md-4" name="financial_reports" label="{{ trans('admin/plans.financial_reports') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-select-input context="modal" divClasses="col-12 col-md-4" name="performance_reports" label="{{ trans('admin/plans.performance_reports') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-select-input context="modal" name="whatsapp_messages" label="{{ trans('admin/plans.whatsapp_messages') }}" :options="[1 => trans('main.allowed'), 0 => trans('main.notallowed')]" required/>
        <x-select-input context="modal" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
        <x-text-area context="modal" name="description_ar" label="{{ trans('main.description_ar') }}" placeholder="{{ trans('main.placeholders.description_ar') }}"/>
        <x-text-area context="modal" name="description_en" label="{{ trans('main.description_en') }}" placeholder="{{ trans('main.placeholders.description_en') }}"/>
    </div>
</x-modal>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/plans.plan')]) }}"
    action="{{ route('admin.plans.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/plans.selectedPlans')]) }}"
    action="{{ route('admin.plans.deleteSelected') }}" ids submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
