@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ __('Settings') }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ __(\App\Models\Settings::getTabNames()[$tab]) }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-settings"></i>
                    <span style="text-transform: uppercase">{{ __(\App\Models\Settings::getTabNames()[$tab]) }}</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.save') }}" method="post" class="form-horizontal">
                        @csrf
                        @include('admin.settings.tabs.' . $tab, ['data' => $data])

                        @can('setting_' . $tab . '_edit')
                            <input type="submit" value="{{ __('Save') }}"
                                class="btn btn-success text-white float-right btn-save-setting">
                        @endcan
                    </form>
                    <form action="{{ route('settings.contacts.remove') }}" method="post" id="remove_contact_block_form">
                        @csrf
                        <input type="hidden" name="lang" id="remove_contact_block_form__field_lang">
                        <input type="hidden" name="count_contact_block"
                            id="remove_contact_block_form__field_count_contact_block">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.btn-meta-generate').on('click', function() {
                let route = $(this).data('route');
                let lang = $(this).data('lang');
                let slug = $(this).data('slug');
                $.ajax({
                    type: "POST",
                    url: route,
                    data: {
                        _token:"{{csrf_token()}}",
                        template_lang: lang,
                        template_title: $(".template-title-input-" + lang + slug).val(),
                        template_desc: $(".template-desc-input-" + lang + slug).val(),
                        rewrite_all: $(".template-rewrite-" + lang).prop("checked")
                    },
                    success: function (data) {
                        if(data.success){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function (jqXHR, text, error) {
                        console.log('seo-title-generate error!');
                    }
                });
            });

            $('.btn-preview-icon').select2({
                templateResult: formatStateIcon,
                templateSelection: formatStateIcon,
            })
        });
    </script>
@endpush
