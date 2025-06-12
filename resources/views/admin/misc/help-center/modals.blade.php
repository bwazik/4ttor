<!-- Add Modal -->
<x-modal modalType="add" modalSize="modal-lg" modalTitle="{{ trans('main.addItem', ['item' => trans('admin/helpCenter.article')]) }}"
    action="{{ route('admin.help-center.insertArticle') }}">
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('main.title_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('main.title_en') }}" required/>
        <x-basic-input context="modal" type="text" name="slug" label="{{ trans('main.slug') }}" placeholder="{{ trans('admin/categories.placeholders.slug') }}" required/>
        <x-select-input context="modal" name="category_id" label="{{ trans('admin/categories.category') }}" :options="$categoryIds" required/>
        <x-select-input context="modal" name="audience" label="{{ trans('main.audience') }}" :options="
        [
            1 => trans('admin/teachers.teachers'),
            2 => trans('admin/students.students'),
            3 => trans('admin/assistants.assistants'),
            4 => trans('admin/parents.parents'),
            5 => trans('admin/teachers.teachers') .' & '. trans('admin/assistants.assistants'),
            6 => trans('admin/students.students') .' & '. trans('admin/parents.parents'),
            7 => trans('main.all'),
        ]" required/>
        <x-select-input context="modal" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"/>
        <x-select-input divClasses="col-12" name="is_pinned" label="{{ trans('main.is_pinned') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]"/>
        <x-text-area context="modal" name="description_ar" label="{{ trans('main.description_ar') }}" placeholder="{{ trans('main.placeholders.description_ar') }}" maxlength="500"/>
        <x-text-area context="modal" name="description_en" label="{{ trans('main.description_en') }}" placeholder="{{ trans('main.placeholders.description_en') }}" maxlength="500"/>
    </div>
</x-modal>
<!-- Edit Modal -->
<x-modal modalType="edit" modalSize="modal-lg" modalTitle="{{ trans('main.editItem', ['item' => trans('admin/helpCenter.article')]) }}"
    action="{{ route('admin.help-center.updateArticle') }}" id>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('admin/helpCenter.placeholders.title_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('admin/helpCenter.placeholders.title_en') }}" required/>
        <x-basic-input context="modal" type="text" name="slug" label="{{ trans('main.slug') }}" placeholder="{{ trans('admin/categories.placeholders.slug') }}" required/>
        <x-select-input context="modal" name="category_id" label="{{ trans('admin/categories.category') }}" :options="$categoryIds" required/>
        <x-select-input context="modal" name="audience" label="{{ trans('main.audience') }}" :options="
        [
            1 => trans('admin/teachers.teachers'),
            2 => trans('admin/students.students'),
            3 => trans('admin/assistants.assistants'),
            4 => trans('admin/parents.parents'),
            5 => trans('admin/teachers.teachers') .' & '. trans('admin/assistants.assistants'),
            6 => trans('admin/students.students') .' & '. trans('admin/parents.parents'),
            7 => trans('main.all'),
        ]" required/>
        <x-select-input context="modal" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"/>
        <x-select-input divClasses="col-12" name="is_pinned" label="{{ trans('main.is_pinned') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]"/>
        <x-text-area context="modal" name="description_ar" label="{{ trans('main.description_ar') }}" placeholder="{{ trans('main.placeholders.description_ar') }}" maxlength="500"/>
        <x-text-area context="modal" name="description_en" label="{{ trans('main.description_en') }}" placeholder="{{ trans('main.placeholders.description_en') }}" maxlength="500"/>
    </div>
</x-modal>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/helpCenter.article')]) }}"
    action="{{ route('admin.help-center.deleteArticle') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/helpCenter.selectedArticles')]) }}"
    action="{{ route('admin.help-center.deleteSelectedArticles') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
