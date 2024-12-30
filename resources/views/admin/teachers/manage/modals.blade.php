<!-- Add Modal -->
<x-modal modalType="add" modalSize="modal-xl" modalTitle="{{ trans('main.addItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.insert') }}">
    <div class="row mb-4">
        @foreach($plans as $plan)
            <div class="col-md me-2 mt-2 mb-5">
                <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="customRadioIconAdd{{ $plan->id }}">
                        <input name="plan_id" class="form-check-input" type="radio" value="{{ $plan->id }}" id="customRadioIconAdd{{ $plan->id }}">
                        <span class="custom-option-header">
                            <span class="h6 mb-0">{{ $plan->name }}</span>
                            <small class="text-muted">{{ formatCurrency($plan->monthly_price) }} {{ trans('main.currency') }}</small>
                        </span>
                        <span class="custom-option-body">
                            <small>{{ $plan->description }}</small>
                        </span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/teachers.placeholders.name_ar') }}" required />
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/teachers.placeholders.name_en') }}" required />
        <x-basic-input context="modal" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/teachers.placeholders.username') }}" required />
        <x-basic-input context="modal" type="password" name="password" label="{{ trans('main.password') }}" required />
        <x-basic-input context="modal" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/teachers.placeholders.phone') }}" required />
        <x-basic-input context="modal" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/teachers.placeholders.email') }}" />
        <x-select-input context="modal" name="subject_id" label="{{ trans('main.subject') }}" :options="$subjects" required />
        <x-select-input context="modal" name="grades" label="{{ trans('main.grades') }}" :options="$grades" multiple required />
    </div>
</x-modal>
<!-- Edit Modal -->
<x-modal modalType="edit" modalSize="modal-xl" modalTitle="{{ trans('main.editItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.update') }}" id>
    <div class="row mb-4">
        @foreach($plans as $plan)
            <div class="col-md mb-md-0 me-2 mt-2 mb-5">
                <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="customRadioIconEdit{{ $plan->id }}">
                        <input name="plan_id" class="form-check-input plan_id" type="radio" value="{{ $plan->id }}" id="customRadioIconEdit{{ $plan->id }}">
                        <span class="custom-option-header">
                            <span class="h6 mb-0">{{ $plan->name }}</span>
                            <small class="text-muted">{{ formatCurrency($plan->monthly_price) }} {{ trans('main.currency') }}</small>
                        </span>
                        <span class="custom-option-body">
                            <small>{{ $plan->description }}</small>
                        </span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/teachers.placeholders.name_ar') }}" required />
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/teachers.placeholders.name_en') }}" required />
        <x-basic-input context="modal" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/teachers.placeholders.username') }}" required />
        <x-basic-input context="modal" type="password" name="password" label="{{ trans('main.password') }}" />
        <x-basic-input context="modal" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/teachers.placeholders.phone') }}" required />
        <x-basic-input context="modal" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/teachers.placeholders.email') }}" />
        <x-select-input context="modal" name="subject_id" label="{{ trans('main.subject') }}" :options="$subjects" required />
        <x-select-input context="modal" name="grades" label="{{ trans('main.grades') }}" :options="$grades" multiple required />
        <x-select-input context="modal" divClasses="col-12" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]" />
    </div>
</x-modal>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teachers.teacher')]) }}" action="{{ route('admin.teachers.delete') }}" id submitColor="danger"
    submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teachers.selectedTeachers')]) }}"  action="{{ route('admin.teachers.deleteSelected') }}" submitColor="danger" ids
    submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Archive Modal -->
<x-modal modalType="archive"
    modalTitle="{{ trans('main.archiveItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.archive') }}" id submitButton="{{ trans('main.yes_archive') }}">
    @include('partials.archive-modal-body')
</x-modal>
<!-- Archive Selected Modal -->
<x-modal modalType="archive-selected"
    modalTitle="{{ trans('main.archiveItem', ['item' => trans('admin/teachers.selectedTeachers')]) }}"
    action="{{ route('admin.teachers.archiveSelected') }}" ids submitButton="{{ trans('main.yes_archive') }}">
    @include('partials.archive-modal-body')
</x-modal>
