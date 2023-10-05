@extends('layouts.admin.app')

@section('content')
    <div class="top_textWrp">
        <h1> {{ $model->name }} </h1>
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
            </ol>
        </nav>
    </div>

    <form class="form-horizontal" method="POST" action="{{ route('products.update', $model->id) }}">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card">

                    <div class="card-header">
                        <ul class="nav nav-tabs" id="myTab" role="tablist"
                            style="margin-bottom: -10px;border-bottom: none">
                            <li class="nav-item">
                                <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab"
                                    aria-controls="main" aria-selected="true">{{ __('Info') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="price-tab" data-toggle="tab" href="#price" role="tab"
                                    aria-controls="price" aria-selected="false">{{ __('Options') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="images-tab" data-toggle="tab" href="#images" role="tab"
                                    aria-controls="images" aria-selected="false">{{ __('Images') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="related-tab" data-toggle="tab" href="#related" role="tab"
                                    aria-controls="related" aria-selected="false">{{ __('Related') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="attributes-tab" data-toggle="tab" href="#attributes" role="tab"
                                    aria-controls="attributes" aria-selected="false">{{ __('Attribute') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab"
                                    aria-controls="seo" aria-selected="false">SEO {!! $model->isEmptyMeta(config('translatable.locale')) ? '<span style="color:red">!</span>' : '' !!}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            {{-- --------------------------- MAIN TAB --------------------------------------- --}}
                            <div class="tab-pane fade show active" id="main" role="tabpanel"
                                aria-labelledby="main-tab">
                                <ul class="nav nav-tabs nav-main-tab" role="tablist">
                                    @foreach ($localizations as $key => $lang)
                                        <li class="nav-item">
                                            <a data-lang="{{ $key }}"
                                                class="nav-link @if (config('translatable.locale') == $key) active @endif"
                                                data-toggle="tab" href="#main_lang_{{ $key }}" role="tab">
                                                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                                                        src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                        alt="{{ $key }}"> {{ $lang }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <br>
                                <div class="tab-content">
                                    @foreach ($localizations as $key => $catLang)
                                        <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
                                            id="main_lang_{{ $key }}" role="tabpanel">
                                            @include('ecom::admin.products.tabs._main', [
                                                'lang' => $key,
                                                'data' => $data,
                                                'model' => $model,
                                            ])
                                            {{-- {!! Constructor::output($model->getTranslation($key),$key) !!} --}}
                                        </div>
                                    @endforeach
                                    {{-- @include('ecom::admin.products._form', ['model' => $model]) --}}
                                </div>
                            </div>

                            {{-- --------------------------- SEO TAB --------------------------------------- --}}
                            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    @foreach ($localizations as $key => $lang)
                                        <li class="nav-item">
                                            <a class="nav-link @if (config('translatable.locale') == $key) active @endif"
                                                data-toggle="tab" href="#seo_lang_{{ $key }}" role="tab">
                                                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                                                        src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                        alt="{{ $key }}"> {{ $lang }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <br>
                                <div class="tab-content tabcontent-border">
                                    @foreach ($localizations as $key => $catLang)
                                        <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
                                            id="seo_lang_{{ $key }}" role="tabpanel">
                                            @include('ecom::admin.products.tabs._seo', [
                                                'lang' => $key,
                                                'data' => $data,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- --------------------------- Price TAB --------------------------------------- --}}
                            <div class="tab-pane fade" id="price">
                                @include('ecom::admin.products.tabs._price', [])
                            </div>
                            {{-- --------------------------- Images TAB --------------------------------------- --}}
                            <div class="tab-pane fade" id="images">
                                @include('ecom::admin.products.tabs._images', [])
                            </div>
                            {{-- --------------------------- Related TAB --------------------------------------- --}}
                            <div class="tab-pane fade" id="related">
                                @include('ecom::admin.products.tabs._related')
                            </div>
                            {{-- --------------------------- Attributes TAB --------------------------------------- --}}
                            <div class="tab-pane fade" id="attributes">
                                @include('ecom::admin.products.tabs._attributes', [
                                    'model' => $model,
                                ])

                            </div>
                        </div>

                    </div>

                </div>
            </div>
            @include('ecom::admin.products.aside')
        </div>
        </div>
    </form>
    @can('products_delete')
        <form action="{{ route('products.destroy', $model->id) }}" method="POST" class="delete-model-form">
            @csrf
            @method('DELETE')
        </form>
    @endcan
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #5897fb !important;
            border: 1px solid #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color: #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            color: #3e5569;
            background-color: #fff;
            border-color: rgba(0, 0, 0, 0.25);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }

        /* ==============================================================
                                        Switches
            ============================================================== */
        .material-switch {
            line-height: 3em;
        }

        .material-switch>input[type="checkbox"] {
            display: none;
        }

        .material-switch>label {
            cursor: pointer;
            height: 0px;
            position: relative;
            width: 40px;
        }

        .material-switch>label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position: absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }

        .material-switch>label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }

        .material-switch>input[type="checkbox"]:checked+label::before {
            background: inherit;
            opacity: 0.5;
        }

        .material-switch>input[type="checkbox"]:checked+label::after {
            background: inherit;
            left: 20px;
        }

        .card-price-element.not-save .card-header {
            background-color: #ff070773;
        }

        .collapsed .mdi-minus:before {
            content: "\F415";
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2-field").select2();

            $('#myTab .nav-item a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                let mainThis = $(this);
                let tab = $(this).attr('aria-controls');

                if (tab === 'price') {
                    $('.save-btn').hide();
                    $('.save-btn').next().hide();
                } else {
                    $('.prices-container .card-price-element').each(function() {
                        let _this = $(this);
                        let changed = _this.find('.data-changed').val();

                        if (changed === '1') {
                            _this.addClass('not-save');

                            e.preventDefault();

                            Swal.fire({
                                title: 'Секундочку!',
                                text: "Ви зберегли дані?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Зберегти!',
                                cancelButtonText: '{{ __('No') }}'
                            }).then((result) => {
                                if (result.value) {
                                    _this.find('.btn-save-price-elem').trigger('click');
                                    _this.removeClass('not-save');
                                    _this.find('.data-changed').val('0');
                                    mainThis.trigger('show.bs.tab');
                                } else {
                                    _this.removeClass('not-save');
                                    _this.find('.data-changed').val('0');
                                    mainThis.trigger('show.bs.tab');
                                }
                            });
                        }
                    });

                    $('.save-btn').show();
                    $('.save-btn').next().show();
                }
            })

            $(document).on('click', '.add-attr-element', function() {
                let $container = $(this).closest('.attr-container');

                let $cloneElem = $(this).closest('.attr-element').clone();

                $container.append($cloneElem);

                if ($cloneElem.find('.btns-group').find('.remove-attr-element').length == 0) {
                    $cloneElem.find('.btns-group').prepend(
                        '<span class="btn btn-danger text-white remove-attr-element"><i class="mdi mdi-minus"></i></span>'
                    );
                }
                if ($cloneElem.find('input.group_value').length > 0) {
                    $cloneElem.find('input.group_value').val('null');
                }
                $(this).remove();
            });

            $(document).on('click', '.remove-attr-element', function() {
                if ($(this).siblings('.add-attr-element').length == 1) {
                    $(this).closest('.attr-container .attr-element').last().prev().find('.btns-group')
                        .append(
                            '<span class="btn btn-primary add-attr-element"><i class="mdi mdi-plus"></i></span>'
                        );
                }

                $(this).closest('.attr-element').remove();
            });

            $(".btn-add-price-elem").on('click', function() {
                let product_id = $(this).data('product_id');
                $.ajax({
                    url: "{{ route('products.add-price') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: product_id
                    },
                    success: function(res) {
                        $('.prices-container').append(res);

                        // Scroll to new element
                        let last = $('.prices-container > .card-price-element:last-child');
                        $("html, body").animate({
                            scrollTop: last.offset().top + "px"
                        }, {
                            duration: 300
                        });
                    }
                });
            });

            //Save all
            $(".btn-save-all").on('click', function() {
                $(".btn-save-price-elem").click();
            });

            $(document).on('click', ".btn-copy-price-elem", function() {
                let price_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('products.copy-price') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        price_id: price_id
                    },
                    success: function(res) {
                        $('.prices-container').append(res);

                        // Scroll to new element
                        let last = $('.prices-container > .card-price-element:last-child');
                        $("html, body").animate({
                            scrollTop: last.offset().top + "px"
                        }, {
                            duration: 300
                        });
                    }
                });
            });

            $(document).on('click', ".btn-remove-price-elem", function() {
                let id = $(this).data('id');
                let _this = $(this);

                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: "{{ __('Are you trying to delete an entry?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('Yes, do it!') }}',
                    cancelButtonText: '{{ __('No') }}'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('products.remove-price') }}",
                            type: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(res) {
                                _this.closest('.card-price-element').remove();

                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', ".btn-save-price-elem", function() {
                let id = $(this).data('id');

                let _this = $(this);

                let status = _this.closest('.card-price-element').find('.pf-status').prop('checked');
                let image_class = _this.closest('.card-price-element').data('image-class');
                let image = _this.closest('.card-price-element').find('input[name="' + image_class + '"]')
                    .val();

                let images_class = _this.closest('.card-price-element').data('images-class');
                let images = _this.closest('.card-price-element').find('.' + images_class + ' :input')
                    .serialize();

                let attributes = _this.closest('.card-price-element').find('.price_attributes-div :input')
                    .serialize();

                $.ajax({
                    url: "{{ route('products.save-price') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        count: _this.closest('.card-price-element').find(
                            '.pf-count').val(),
                        code: _this.closest('.card-price-element').find(
                            '.pf-code').val(),
                        name: _this.closest('.card-price-element').find(
                            '.pf-name').val(),
                        color: _this.closest('.card-price-element').find(
                            '.pf-color').val(),
                        price: _this.closest('.card-price-element').find(
                            '.pf-price').val(),
                        old_price: _this.closest('.card-price-element').find(
                            '.pf-old_price').val(),
                        cost: _this.closest('.card-price-element').find(
                            '.pf-cost').val(),
                        image: image,
                        images: images,
                        attributes: attributes,
                        order: _this.closest('.card-price-element').find('.pf-order').val(),
                        status: status ? '1' : '0',
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            _this.closest('.card-price-element').replaceWith(res.elem)
                        }
                    },
                    error: function(jqXHR, text, error) {
                        var responseText = jQuery.parseJSON(jqXHR.responseText);
                        console.log(responseText);
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: '{{ __('Error!') }}',
                            text: responseText.message == 'CSRF token mismatch.' ?
                                '{{ __('Session has expired. Please reload the page.') }}' :
                                responseText.message,
                            showConfirmButton: false,
                            timer: 4000
                        });
                    }
                });
            });

            $(document).on('change', ".pf-field", function() {
                $(this).closest('.card-price-element').find('.data-changed').val('1');
            })

            $(document).on('click', '.add-images-element', function() {
                let $container = $(this).parent().find('.images-container');
                let $cloneElem = $(this).parent().find('template');

                $container.append($cloneElem.html());
            });

            $(document).on('click', '.remove-images-item', function() {
                $(this).closest('.form-group').remove();
            });

            $(".radio-like-checkbox").change(function() {
                let attr_div = $(this).data('attribute-class');
                $('.' + attr_div).find('.radio-like-checkbox').not(this).prop('checked', false);
            });
        });
    </script>
@endpush
