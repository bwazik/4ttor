<!-- Add Offcanvas -->
<x-modal modalType="add" modalSize="modal-lg" modalTitle="{{ trans('main.addItem', ['item' => trans('admin/quizzes.quiz')]) }}" action="{{ route('teacher.quizzes.insert') }}">
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_en') }}" required/>
        <x-select-input context="modal" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" required/>
        <x-select-input context="modal" name="groups" label="{{ trans('main.group') }}" multiple required/>
        <x-basic-input context="modal" type="number" name="duration" label="{{ trans('main.duration') }}" placeholder="60" required/>
        <x-select-input context="modal" name="quiz_mode" label="{{ trans('main.status') }}" :options="[1 => trans('admin/quizzes.fixed'), 2 => trans('admin/quizzes.flexible')]" required/>
        <x-basic-input context="modal" type="text" name="start_time" classes="flatpickr-date-time" label="{{ trans('main.start_time') }}" placeholder="YYYY-MM-DD" required/>
        <x-basic-input context="modal" type="text" name="end_time" classes="flatpickr-date-time" label="{{ trans('main.end_time') }}" placeholder="YYYY-MM-DD" required/>
        <x-select-input divClasses="col-12 col-md-3" name="randomize_questions" label="{{ trans('admin/quizzes.randomize_questions') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
        <x-select-input divClasses="col-12 col-md-3" name="randomize_answers" label="{{ trans('admin/quizzes.randomize_answers') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
        <x-select-input divClasses="col-12 col-md-3" name="show_result" label="{{ trans('admin/quizzes.show_result') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
        <x-select-input divClasses="col-12 col-md-3" name="allow_review" label="{{ trans('admin/quizzes.allow_review') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
    </div>
</x-modal>
<!-- Edit Offcanvas -->
<x-modal modalType="edit" modalSize="modal-lg" modalTitle="{{ trans('main.editItem', ['item' => trans('admin/quizzes.quiz')]) }}" action="{{ route('teacher.quizzes.update') }}" id>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_en') }}" required/>
        <x-select-input context="modal" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" required/>
        <x-select-input context="modal" name="groups" label="{{ trans('main.group') }}" :options="$groups" multiple required/>
        <x-basic-input context="modal" type="number" name="duration" label="{{ trans('main.duration') }}" placeholder="60" required/>
        <x-select-input context="modal" name="quiz_mode" label="{{ trans('main.status') }}" :options="[1 => trans('admin/quizzes.fixed'), 2 => trans('admin/quizzes.flexible')]" required/>
        <x-basic-input context="modal" type="text" name="start_time" classes="flatpickr-date-time" label="{{ trans('main.start_time') }}" placeholder="YYYY-MM-DD" required/>
        <x-basic-input context="modal" type="text" name="end_time" classes="flatpickr-date-time" label="{{ trans('main.end_time') }}" placeholder="YYYY-MM-DD" required/>
        <x-select-input divClasses="col-12 col-md-3" name="randomize_questions" label="{{ trans('admin/quizzes.randomize_questions') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
        <x-select-input divClasses="col-12 col-md-3" name="randomize_answers" label="{{ trans('admin/quizzes.randomize_answers') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
        <x-select-input divClasses="col-12 col-md-3" name="show_result" label="{{ trans('admin/quizzes.show_result') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
        <x-select-input divClasses="col-12 col-md-3" name="allow_review" label="{{ trans('admin/quizzes.allow_review') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]" required/>
    </div>
</x-modal>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/quizzes.quiz')]) }}"
    action="{{ route('teacher.quizzes.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/quizzes.selectedQuizzes')]) }}"
    action="{{ route('teacher.quizzes.deleteSelected') }}" ids submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
