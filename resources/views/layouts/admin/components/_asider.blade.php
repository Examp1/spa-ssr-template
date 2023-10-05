@php
    $asiderSections = config('asider.sections');
    if (isset($sections)) {
        $asiderSections = array_replace_recursive($asiderSections, $sections);
    }

    if(!isset($front_link_enabled)) $front_link_enabled = true;
    if(!isset($copy_enabled)) $copy_enabled = true;
@endphp
<aside class="r-aside">
    @if ($asiderSections['control']['show'] !== false)
        {{-- секция с кнопками сохранить, отменить итд (section name:control) --}}
        <section>
            <h2 data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                {{ __($asiderSections['control']['title']) }} <i class="fa fa-caret-down"></i></h2>

            <div class="collapse @if (!$asiderSections['control']['collapse_hide']) show @endif" id="collapseExample">
                <hr>
                <div class="accordion-content" style="display: flex">
                    <div class="topBtnWrp">
                        <input type="hidden" name="save_method" value="save_and_exit">
                        <span class="btn-lg btn btn-success text-white float-right save-btn">{{ __('Save') }}</span>
                        <input type="submit" value="{{ __('Save and exit') }}"
                            class="btn-lg btn btn-outline-success float-right">
                        <span data-url="{{ route($route . '.index') }}"
                            class="btn btn-lg btn-warning float-right cancel-btn">{{ __('Cancel') }}</span>

                        {{-- JIRA --}}
                        {{-- переделал внешний вид 3х кнопок --}}

                        <div class="smallBtnWrp">
                            @if ($model->id && $front_link_enabled)
                            <a href="{{ $model->frontLink(true) }}"
                            target="_blank"
                            title="{{ __('Preview') }}"
                            class="btn btn-info btn-md">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span id="copyUrl"
                                  class="btn btn-md float-right btn-light btn-block"
                                  title="{{ __('Link to preview') }}">
                                <i class="fa fa-copy"></i>
                            </span>
                            <input type="text" class="copy" id="copyInpt" />
                        @endif

                        @if ($model->id && $copy_enabled)
                            <a href="{{ route($route . '.copy', ['id' => $model->id]) }}"
                               class="btn btn-lg float-right btn-light btn-block"
                               title="{{ __('Create a new page based on') }}">
                                <i class="mdi mdi-file"></i>
                            </a>
                        @endif
                        </div>

                    </div>
                    {{-- @if ($model->id && $front_link_enabled)
                        <hr>
                        <div class="btnWrp">
                            <a href="{{ $model->frontLink(true) }}" target="_blank" title="{{ __('Preview') }}"
                                class="mt-2 btn btn-secondary btn-lg btn-block"><i class="fa fa-eye"></i>
                                {{ __('Preview') }}
                            </a>
                            <span id="copyUrl" class="btn btn-lg float-right btn-light btn-block"><i
                                    class="fa fa-copy"></i> {{ __('Link to preview') }}</span>
                            <input type="text" class="copy" id="copyInpt" />
                        </div>
                    @endif
                    @if ($model->id && $copy_enabled)
                        <hr>
                        <div class="btnWrp">
                            <a href="{{ route($route . '.copy', ['id' => $model->id]) }}"
                                class="btn btn-lg float-right btn-light btn-block"><i class="mdi mdi-file"></i>{{ __('Create a new page based on') }}</a>
                        </div>
                    @endif --}}
                </div>
            </div>
        </section>
    @endif

    @if ($asiderSections['info']['show'] !== false)
        {{-- информация (section name:info) --}}
        <section class="info">
            <h2 data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false"
                aria-controls="collapseExample3">{{ __($asiderSections['info']['title']) }} <i
                    class="fa fa-caret-down"></i></h2>
            <div class="collapse @if (!$asiderSections['info']['collapse_hide']) show @endif" id="collapseExample3">
                <hr>
                <div class="accordion-content">
                    @if ($asiderSections['info']['blocks']['order']['show'] !== false)
                        @php($order_field = $asiderSections['info']['blocks']['order']['field_name'])
                        @php($order_default = $asiderSections['info']['blocks']['order']['default'] ?? '1')
                        <div class="section_item">
                            <label>
                                <span class="tolltip" data-text="test"><i class="fa fa-question-circle"
                                        aria-hidden="true"></i>
                                </span> {{ __($asiderSections['info']['blocks']['order']['title']) }}
                            </label>
                            <div class="inputWrapper inputWrapperOrder">
                                <input type="number" name="order"
                                    value="{{ old($order_field, $model->$order_field ?: $order_default) }}"
                                    class="{{ $errors->has($order_field) ? ' is-invalid' : '' }}">
                                <div class="wrp">
                                    <span class="order-plus-btn">+</span>
                                    <span class="order-minus-btn">-</span>
                                </div>

                                @if ($errors->has($order_field))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first($order_field) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($asiderSections['info']['blocks']['parent_category']['show'] !== false)
                        @php($parent_category_field = $asiderSections['info']['blocks']['parent_category']['field_name'])
                        <div class="section_item">
                            <div class="input-group">
                                <label>{{ __($asiderSections['info']['blocks']['parent_category']['title']) }}</label>
                                <select name="{{ $parent_category_field }}">
                                    <option value="0">{{ __('main') }}</option>
                                    @include('admin.blog.categories.node')
                                </select>
                            </div>
                        </div>
                    @endif

                    @if ($asiderSections['info']['blocks']['main_category']['show'] !== false)
                        @php($main_category_field = $asiderSections['info']['blocks']['main_category']['field_name'])
                        <div class="section_item">
                            <div class="input-group">
                                <label>{{ __($asiderSections['info']['blocks']['main_category']['title']) }}</label>
                                <select class="select2 {{ $errors->has($main_category_field) ? ' is-invalid' : '' }}"
                                    name="{{ $main_category_field }}">
                                    <option value="">---</option>
                                    {!! \App\Models\BlogCategories::getOptionsHTML([$model->$main_category_field]) !!}
                                </select>

                                @if ($errors->has($main_category_field))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first($main_category_field) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($asiderSections['info']['blocks']['categories']['show'] !== false)
                        @php($categories_field = $asiderSections['info']['blocks']['categories']['field_name'])
                        <div class="section_item">
                            <div class="input-group">
                                <label>{{ __($asiderSections['info']['blocks']['categories']['title']) }}</label>
                                <select name="{{ $categories_field }}" multiple="multiple" class="select2"
                                    style="width: 100%">
                                    {!! \App\Models\BlogCategories::getOptionsHTML($model->categories->pluck('id')->toArray()) !!}
                                </select>
                            </div>
                        </div>
                    @endif

                    @if ($asiderSections['info']['blocks']['tags']['show'] !== false)
                        <?php
                        $tags_field = $asiderSections['info']['blocks']['tags']['field_name'];
                        $selectTags = \App\Models\BlogArticleTag::query()
                            ->where('blog_article_id', $model->id)
                            ->pluck('blog_tag_id')
                            ->toArray();
                        ?>
                        <div class="section_item">
                            <div class="input-group">
                                <label>{{ __($asiderSections['info']['blocks']['tags']['title']) }}</label>
                                <select name="{{ $tags_field }}" multiple="multiple"
                                    class="select2-tag form-control" style="width: 100%">
                                    @foreach (\App\Models\BlogTags::query()->get() as $tag)
                                        <option value="{{ $tag->id }}"
                                            @if ($selectTags && in_array($tag->id, $selectTags)) selected @endif>{{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if ($asiderSections['info']['blocks']['public_date']['show'] !== false)
                        @php($public_date_field = $asiderSections['info']['blocks']['public_date']['field_name'])
                        <div class="section_item">
                            <div class="input-group">
                                <label>{{ __($asiderSections['info']['blocks']['public_date']['title']) }}</label>
                                <input type="input" style="width: 100%" class="form-control"
                                    name="{{ $public_date_field }}" value="{{ $model->$public_date_field }}"
                                    id="{{ $public_date_field }}">
                            </div>
                        </div>
                    @endif

                    @if ($asiderSections['info']['blocks']['menu']['show'] !== false)
                        @php($menu_field = $asiderSections['info']['blocks']['menu']['field_name'])
                        <div class="section_item">
                            <div class="input-group">
                                <label>{{ __($asiderSections['info']['blocks']['menu']['title']) }}</label>
                                <select name="{{ $menu_field }}">
                                    <option value="">---</option>
                                    @foreach (\App\Models\Menu::getTagsWithId() as $menuId => $item)
                                        <option value="{{ $menuId }}"
                                            @if (old($menu_field, $model->$menu_field ?? '') == $menuId) selected @endif>{{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    @include('ecom::admin.pieces._asider')

                    {{-- TODO: make project specific fields better --}}
                    {{-- Project fields --}}

                    @if($front_link_enabled)
                        <div class="section_item">
                            <div class="input-group">
                                <label>Slug</label>
                                <input type="text" name="slug" value="{{ old('slug', $model->slug ?? '') }}">
                                <span><strong>{{ __('Permalink') }}:</strong> <a
                                        href="{{ env('APP_URL') . $model->frontLink() }}"
                                        target="_blank">{{ env('APP_URL') . $model->frontLink() }}</a></span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if ($asiderSections['image']['show'] !== false)
        {{-- Главная картинка (section name:image) --}}
        @foreach ($localizations as $key => $lang)
            <section
                class="image image-language-section image-language-section-{{ $key }} @if (config('translatable.locale') !== $key) hide @endif">
                <h2 data-toggle="collapse" data-target="#collapseExample5" aria-expanded="false"
                    aria-controls="collapseExample5"><span>{{ __($asiderSections['image']['title']) }} <img
                            src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                            alt="{{ $key }}"></span><i class="fa fa-caret-down"></i></h2>
                <div class="collapse @if (!$asiderSections['image']['collapse_hide']) show @endif" id="collapseExample5">
                    <hr>
                    <div class="accordion-content">
                        @if(get_class($model) == "App\Models\BlogArticles")
                            <div style="display: flex;justify-content: center; gap: 30px">
                                <div>
                                    <div class="section_item justify-content-start">
                                        <img src="#" alt="">
                                        <label>Прев'ю</label>
                                    </div>
                                    <div class="section_item justify-content-center">
                                        {{ media_preview_box(
                                            'page_data[' . $key . '][preview_image]',
                                            old('page_data.' . $key . '.preview_image', $data[$key]['preview_image'] ?? ''),
                                            null,
                                            'page_data[' . $key . '][preview_alt]',
                                            old('page_data.' . $key . '.preview_alt', $data[$key]['preview_alt'] ?? ''),
                                        ) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="section_item justify-content-start">
                                        <img src="#" alt="">
                                        <label>Для банера</label>
                                    </div>
                                    <div class="section_item justify-content-center">
                                        {{ media_preview_box(
                                            'page_data[' . $key . '][image]',
                                            old('page_data.' . $key . '.image', $data[$key]['image'] ?? ''),
                                            null,
                                            'page_data[' . $key . '][alt]',
                                            old('page_data.' . $key . '.alt', $data[$key]['alt'] ?? ''),
                                        ) }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="section_item justify-content-start">
                                <img src="#" alt="">
                                <label>{{ __('Recommended size') }} {{ \App\Models\Landing::IMAGE_RECOMMENDED_SIZE }}, {{ __('proportions') }}
                                    {{ \App\Models\Landing::IMAGE_PROPORTION }}</label>
                            </div>
                            <div class="section_item justify-content-center">
                                {{ media_preview_box(
                                    'page_data[' . $key . '][image]',
                                    old('page_data.' . $key . '.image', $data[$key]['image'] ?? ''),
                                    null,
                                    'page_data[' . $key . '][alt]',
                                    old('page_data.' . $key . '.alt', $data[$key]['alt'] ?? ''),
                                ) }}
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    @if (isset($asiderSections['ban_image']['show']) && $asiderSections['ban_image']['show'] !== false)
        @foreach ($localizations as $key => $lang)
            <section
                class="image image-language-section image-language-section-{{ $key }} @if (config('translatable.locale') !== $key) hide @endif">
                <h2 data-toggle="collapse" data-target="#collapse_ban_image" aria-expanded="false"
                    aria-controls="collapse_ban_image"><span>{{ __($asiderSections['ban_image']['title']) }} <img
                            src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                            alt="{{ $key }}"></span><i class="fa fa-caret-down"></i></h2>
                <div class="collapse @if (!$asiderSections['ban_image']['collapse_hide']) show @endif" id="collapse_ban_image">
                    <hr>
                    <div class="accordion-content">
                        <div class="section_item justify-content-start">
                            <img src="#" alt="">
                            <label>{{ __('Recommended size') }} {{ \App\Models\Landing::IMAGE_RECOMMENDED_SIZE }}, {{ __('proportions') }}
                                {{ \App\Models\Landing::IMAGE_PROPORTION }}</label>
                        </div>
                        <div class="section_item justify-content-center">
                            {{ media_preview_box(
                                'page_data[' . $key . '][ban_image]',
                                old('page_data.' . $key . '.ban_image', $data[$key]['ban_image'] ?? ''),
                                null,
                                'page_data[' . $key . '][ban_alt]',
                                old('page_data.' . $key . '.ban_alt', $data[$key]['ban_alt'] ?? ''),
                            ) }}
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    @if ($asiderSections['status']['show'] !== false)
        {{-- cекция со статусами (section name:status) --}}
        <section class="status">
            <h2 data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false"
                aria-controls="collapseExample2">{{ __($asiderSections['status']['title']) }} <i
                    class="fa fa-caret-down"></i></h2>
            <div class="collapse @if (!$asiderSections['status']['collapse_hide']) show @endif" id="collapseExample2">
                <hr>
                <div class="accordion-content">
                    @if (count($asiderSections['status']['statuses']))
                        @foreach ($asiderSections['status']['statuses'] as $statusKey => $statusItem)
                            @php($status_field_name = $statusItem['field_name'])
                            <div class="material-switch md-switch">
                                <span class="tolltip" data-text="{{ __($statusItem['tooltip']) }}"><i
                                        class="fa fa-question-circle" aria-hidden="true"></i></span>
                                <span>{{ __($statusItem['title']) }}</span>
                                <input value="1" name="{{ $status_field_name }}"
                                    id="someSwitchOptionSuccess{{ $statusKey }}" type="checkbox"
                                    {{ old($status_field_name, $model->$status_field_name) || (isset($statusItem['value']) && $statusItem['value']) ? ' checked' : '' }} />
                                <label for="someSwitchOptionSuccess{{ $statusKey }}" class="label-success"></label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if ($asiderSections['video']['show'] !== false)
        {{-- видео (section name:video) --}}
        <section class="video">
            <h2 data-toggle="collapse" data-target="#collapseExample4" aria-expanded="false"
                aria-controls="collapseExample4">{{ __($asiderSections['video']['title']) }} <i
                    class="fa fa-caret-down"></i></h2>
            <div class="collapse @if (!$asiderSections['video']['collapse_hide']) show @endif" id="collapseExample4">
                <hr>
                <div class="accordion-content">
                    <div class="section_item">
                        <div class="input-group">
                            <label>Посилання на відео на Youtube</label>
                            <input type="text" name="video" value="{{ old('video', $model->video ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Удаление (section name:delete) --}}
    @if ($asiderSections['delete']['show'] !== false && $model->id)
        @if (isset($permission_prefix))
            @can($permission_prefix . '_delete')
                <section class="info">
                    <h2 data-toggle="collapse" data-target="#collapseExample6" aria-expanded="false"
                        aria-controls="collapseExample6">{{ __($asiderSections['delete']['title']) }} <i
                            class="fa fa-caret-down"></i></h2>
                    <div class="collapse @if (!$asiderSections['delete']['collapse_hide']) show @endif" id="collapseExample6">
                        <hr>
                        <button type="button" class="btn btn-danger btn-block text-white delete-model-btn"><i
                                class="fa fa-trash"></i>
                            {{ __($asiderSections['delete']['btn_name']) }}
                        </button>
                    </div>
                </section>
            @endcan
        @else
            <section class="info">
                <h2 data-toggle="collapse" data-target="#collapseExample6" aria-expanded="false"
                    aria-controls="collapseExample6">{{ __($asiderSections['delete']['title']) }} <i
                        class="fa fa-caret-down"></i></h2>
                <div class="collapse @if (!$asiderSections['delete']['collapse_hide']) show @endif" id="collapseExample6">
                    <hr>
                    <button type="button" class="btn btn-danger btn-block text-white delete-model-btn"><i
                            class="fa fa-trash"></i>
                        {{ $asiderSections['delete']['btn_name'] }}
                    </button>
                </div>
            </section>
        @endif
    @endif
</aside>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Create a new tag') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">{{ __('Title') }}</label>
                    @foreach (config('translatable.locales') as $locale => $nameLocale)
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" title="{{ $nameLocale }}">
                                    <img src="/images/langs/{{ $locale }}.jpg" alt="{{ $nameLocale }}"
                                        style="width: 20px">
                                </span>
                            </div>
                            <input type="text"
                                class="form-control new_tag_field_name new_tag_field_name_{{ $locale }}"
                                data-lang="{{ $locale }}">
                        </div>
                    @endforeach
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Slug</span>
                        </div>
                        <input type="text" class="form-control new_tag_field_slug">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add-new-tag-btn">{{ __('Add') }}</button>
            </div>
        </div>
    </div>
</div>
<div class="up">
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
</div>

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

        .select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color:#5897fb !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            color: #3e5569;
            background-color: #fff;
            border-color: rgba(0,0,0,0.25);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }

        .select2-container--default .select2-selection--multiple {
            height: inherit;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('matrix/css/icons/font-awesome/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('matrix/libs/moment/moment.js') }}"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script>
        function clearModalNewTag() {
            $("#exampleModal").find(".new_tag_field_slug").val("");
            $("#exampleModal").find(".new_tag_field_name").val("");
        }

        $(document).ready(() => {
            $(".select2-field-tagable").select2({
                tags: true
            });
            $('.select2').select2();


            $('#public_date').datetimepicker({
                format: 'DD-MM-YYYY HH:mm',
                useCurrent: true,
                icons: {
                    time: "fa fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: "fa fa-chevron-left",
                    next: "fa fa-chevron-right",
                    today: "fa fa-clock",
                    clear: "fa fa-trash"
                }
            });

            $('.select2-tag').select2({
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
                language: {
                    noResults: function() {
                        return "<button class='btn btn-success text-white btn-xs btn-modal-add-tag'>{{ __('Add new tag') }}</button>";
                    }
                }
            });

            $(document).on('click', '.btn-modal-add-tag', function(event) {
                let lang = $(".nav-main-tab .nav-link.active").data('lang');
                let name = $('.select2-tag').siblings('.select2').find('textarea[type="search"]').val();
                let slug = translit(name);

                clearModalNewTag();

                $("#exampleModal").find(".new_tag_field_slug").val(slug);
                $("#exampleModal").find(".new_tag_field_name_" + lang).val(name);

                $("#exampleModal").modal('show');
            });

            $(document).on('click', '.add-new-tag-btn', function(event) {
                let data = {
                    slug: $("#exampleModal").find(".new_tag_field_slug").val(),
                    names: {}
                };

                $(".new_tag_field_name").each(function() {
                    let lang = $(this).data('lang');
                    data.names[lang] = $(this).val();
                });

                $.ajax({
                    url: "{{ route('blog.tags.add-tag-ajax') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        data: data
                    },
                    success: function(res) {
                        if (res.success) {
                            let selectedIds = $(".select2-tag").val();

                            console.log(selectedIds);

                            $(".select2-tag").select2('destroy');
                            let optionsOrig = [];

                            $(".select2-tag option").each(function() {
                                if (inArray($(this)[0].value, selectedIds)) {
                                    $(this)[0].setAttribute('selected', 'selected');
                                }

                                optionsOrig.push($(this)[0].outerHTML);
                            });

                            optionsOrig.push("<option value='" + res.tag.id + "' selected>" +
                                res.tag.name + "</option>");

                            $(".select2-tag").html("");

                            for (let i = 0; i < optionsOrig.length; i++) {
                                $(".select2-tag").append(optionsOrig[i]);
                            }

                            $('.select2-tag').select2({
                                allowClear: true,
                                escapeMarkup: function(markup) {
                                    return markup;
                                },
                                language: {
                                    noResults: function() {
                                        return "<button class='btn btn-success text-white btn-xs btn-modal-add-tag'>Додати новий тег</button>";
                                    }
                                }
                            });

                            $("#exampleModal").modal('hide');
                        }
                    }
                });
            });
            $(window).scroll(function() {
                console.log();
                if (window.pageYOffset >= 800) {
                    $('.up').addClass('active')
                } else {
                    $('.up').removeClass('active')
                }
            });
            $('.up').click((e) => {
                window.scrollTo(0, 0)
            })
        });
    </script>
@endpush
