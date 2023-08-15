@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a
                    href="/admin/forms?lang={{ request()->input('lang', config('translatable.locale')) }}">{{ __('Forms') }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ request()->input('lang') }}
            </li>
        </ol>
    </nav>

    <form action="{{ route(config('forms.route_name_prefix', 'admin.') . 'forms.update', $form) }}" method="post">
        @csrf

        @method('put')

        <input type="hidden" name="lang" value="{{ request()->get('lang') ?? config('translatable.locale') }}">

        <div class="card">
            <div class="card-header">
                <div class="form-row">
                    <div class="form-group input-group-sm col-sm-6">
                        <label for="FormName">Назва форми</label>

                        <input type="text" name="name" id="FormName"
                            class="form-control @error('name') is-invalid @enderror" value="{{ $form->name ?? '' }}">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group input-group-sm col-sm-6">
                        @foreach (config('translatable.locale_codes') as $langCode => $langName)
                            @if ($langCode != request()->input('lang'))
                                @if ($form->isExistsLang($langCode))
                                    <a href="/admin/forms/{{ $form->getLangId($langCode) }}/edit?lang={{ $langCode }}"
                                        title="Перейти" class="btn btn-success float-right text-white"
                                        style="margin: 0 5px">
                                        {{ $langName }}
                                    </a>
                                @else
                                    <a href="/admin/forms/copy/{{ $form->id }}/{{ $langCode }}"
                                        class="btn btn-danger float-right text-white" style="margin: 0 5px"
                                        title="Створити форму на основі поточної">
                                        <i class="fa fa-copy"></i>
                                        {{ $langName }}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div>
                    <div class="row">
                        <div class="form-group input-group-sm col-sm-12">
                            <label for="FormTitle">Заголовок для повідомлення</label>

                            <input type="text" name="title" id="FormTitle" placeholder="Новий запит"
                                class="form-control @error('title') is-invalid @enderror" value="{{ $form->title ?? '' }}">

                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group input-group-sm col-sm-12">
                            <label>Налаштування кнопки</label>
                            <input type="text" name="btn_name" placeholder="Напис на кнопці" class="form-control"
                                value="{{ $form->btn_name ?? '' }}">
                        </div>

                        <div class="form-group input-group-sm col-sm-12">
                            <label>Зображення</label>
                            {{ media_preview_box("image",old('image', $form->image ?? '')) }}
                        </div>

                        <div class="form-group input-group-sm col-sm-12">
                            <label>Надсилати в групу</label>
                            <?php $groupIdVal = $form->group_id ?? '' ?>
                            <select name="group_id" class="form-control" style="width: 100%">
                                <option value="">---</option>
                                @foreach(config('telegram.groups') as $group)
                                    <option value="{{$group['id']}}" @if($groupIdVal == $group['id']) selected @endif>{{$group['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <h4>Вікно результату</h4>

                        <div class="form-group input-group-sm col-sm-12">
                            <label>Заголовок</label>
                            <input type="text" name="success_title" class="form-control"
                                   value="{{ $form->success_title ?? '' }}">
                        </div>

                        <div class="form-group input-group-sm col-sm-12">
                            <label>Текст</label>
                            <textarea name="success_text" class="form-control">{{ $form->success_text ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div>
                    <select class="form-control form-field-type-select" style="display: inline-block;width: 300px;">
                        @foreach (Form::fieldsList() as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <span class="btn btn-success text-white add-form-field-btn">{{ __('Add field') }}</span>
                </div>
                <div id="fields_container" style="margin-top: 30px">
                    @if (isset($data) && is_array($data) && count($data))
                        @foreach ($data as $field)
                            @include('forms::fields.' . $field['type'], [
                                'field' => $field ?? null,
                                'is_new' => false,
                            ])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-sm btn-info btn-lg">
                <i class="far fa-save"></i>
                {{ __('Update') }}
            </button>
        </div>
    </form>
@endsection

@push('styles')
    <style type="text/css">
        #fields_container .card .card-title {
            margin-bottom: 0 !important;
        }

        #fields_container .confirm-delete-component-popup {
            position: absolute;
            z-index: 9999;
            right: 5px;
            top: 40px;
            background: #ffffff;
            padding: 15px;
            text-align: center;
            border: 1px solid #3c3f41;
            border-radius: 10px;
        }

        #fields_container .component-visibility-switch {
            position: absolute;
            top: -1px;
            right: 37px;
            z-index: 9;
        }

        #fields_container .display-layout {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #fff;
            opacity: 0.4;
            z-index: 1;
        }

        #fields_container .display-layout.display-off {
            display: block;
        }

        #fields_container .help-label {
            display: flex;
            flex-direction: column;
        }

        #fields_container .help-label span {
            font-style: normal;
            font-size: 12px;
            color: #777777;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".add-form-field-btn").on("click", function() {
                let type = $(".form-field-type-select").val();

                $.ajax({
                    url: "/admin/forms/add-field",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        type: type
                    },
                    success: function(res) {
                        $("#fields_container").append(res);
                        $("#fields_container").find(".editor").summernote(summernote_options);
                    }
                });
            });

            const component_container = $('#fields_container');

            /* Init sortable components */
            component_container.sortable({
                handle: '.move-label',
                axis: 'y',
                tolerance: 'pointer',
                cursor: 'move',
                update: function(event, ui) {
                    const sortable_array = $(this).sortable('toArray');
                    refresh_order(sortable_array);
                }
            });
            /* End Init sortable components */

            /* Remove component */
            $(document).on('click', '.remove-component', function(e) {
                e.preventDefault();

                const that = this;
                const confirm_delete_popup = $(that)
                    .parents('.card-component')
                    .find('.confirm-delete-component-popup');

                confirm_delete_popup.show();

                confirm_delete_popup
                    .find('.confirm-button')
                    .on('click', function() {
                        if ($(this).data('action') === 'confirm') {
                            $(that).parents('.card-component').remove();
                            confirm_delete_popup.hide();
                        } else {
                            confirm_delete_popup.hide();
                        }
                    });

                $(document).on('click', function(e) {
                    if (!confirm_delete_popup.is(e.target) && !$(that).is(e.target) && !$(that)
                        .children().is(e.target) && confirm_delete_popup.has(e.target).length === 0
                    ) {
                        confirm_delete_popup.hide();
                    }
                });
            });
            /* End Remove component */

            /* Show / hide component */
            $('.show-hide-checkbox').each(function() {
                on_off_block(this);
            });

            $(document).on('change', '.show-hide-checkbox', function() {
                on_off_block(this);
            });

            /* End Show / hide component */

            function on_off_block(element) {
                const block_body_fields = $(element)
                    .parents('.card-component')
                    .find('.display-layout');

                if ($(element).is(':checked')) {
                    block_body_fields.removeClass('display-off');
                } else {
                    block_body_fields.addClass('display-off');
                }
            }
        });
    </script>
@endpush
