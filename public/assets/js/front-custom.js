let errorMessage = window.translations.errorMessage || 'An unexpected error occurred. Please try again later!';
let tooManyRequestsMessage = window.translations.tooManyRequestsMessage || 'You have exceeded the maximum number of requests. Please try again later!';
let submitButton;

function handleFormSubmit(formId, fields, modalId, modalType, getDatatableId, redirectTo = null) {
    const $form = $(formId);
    const $submitButton = $form.find('button[type="submit"]');
    const originalButtonContent = $submitButton.html();

    $(formId).on('submit', function(e) {
        e.preventDefault();

        $submitButton.find('.waves-ripple').remove();
        $submitButton.prop('disabled', true);
        $submitButton.html(`<i class="ri-loader-4-line ri-spin ri-20px me-1"></i> ${window.translations.processing}...`);

        // Clear previous error states
        $.each(fields, function(_, field) {
            $(formId + ' #' + field).removeClass('is-invalid');
            $(formId + ' #' + field + '_error').text('').addClass('d-none').removeClass('d-block');
        });

        const formData = new FormData(this);
        let datatableId = typeof getDatatableId === 'function' ? getDatatableId() : getDatatableId;

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            dataType: "json",
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                if (response.success) {
                    $.each(fields, function(_, field) {
                        const fieldElement = $(formId + ' #' + field);
                        if (fieldElement.is('select')) {
                            fieldElement.val('').trigger('change');
                        } else {
                            fieldElement.val('');
                        }
                    });
                    toastr.success(response.success)
                    resetButtonState($submitButton, originalButtonContent);
                    if (modalType === 'offcanvas') {
                        $(modalId).offcanvas('hide');
                    } else if (modalType === 'modal') {
                        $(modalId).modal('hide');
                    }
                    if ($(datatableId).length) {
                        refreshDataTable(datatableId);
                    } else if (redirectTo) {
                        window.location.href = redirectTo;
                    } else {
                        location.reload();
                    }
                } else {
                    toastr.error(response.error || errorMessage);
                    resetButtonState($submitButton, originalButtonContent);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 429) {
                    toastr.error(tooManyRequestsMessage);
                } else if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, val) {
                            const normalizedKey = key.replace(/\.\d+$/, '');
                            const inputElement = $(formId + ' #' + normalizedKey);
                            const errorElement = $(formId + ' #' + normalizedKey + '_error');
                            inputElement.addClass('is-invalid');
                            errorElement.text(val[0]).addClass('d-block').removeClass('d-none');
                        });
                    } else if (xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error(errorMessage);
                    }
                } else {
                    toastr.error(errorMessage);
                }

                resetButtonState($submitButton, originalButtonContent);
            },
            complete: function() {
                resetButtonState($submitButton, originalButtonContent);
            }
        });
    });
}

function resetButtonState(submitButton, originalButtonContent) {
    setTimeout(function() {
        submitButton.prop('disabled', false);
        submitButton.html(originalButtonContent);
        submitButton.blur();
        submitButton.find('.waves-ripple').remove();
        if (typeof Waves !== 'undefined') {
            Waves.init();
            Waves.attach(submitButton[0]);
        }
    }, 1500);
}
