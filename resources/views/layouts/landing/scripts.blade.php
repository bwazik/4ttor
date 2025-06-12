<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<!-- endbuild -->

<script>
    window.translations = {
        items: @json(trans('main.items')),
        errorMessage: @json(trans('main.errorMessage')),
        tooManyRequestsMessage: @json(trans('main.tooManyRequestsMessage')),
        processing: @json(trans('main.processing')),
        select_option: @json(trans('main.select_option')),
    };
</script>

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/nouislider/nouislider.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/front-main.js') }}"></script>

<!-- Page JS -->
@yield('page-js')
