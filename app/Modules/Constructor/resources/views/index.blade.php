<input type="hidden" name="constructorData[{{$lang}}][entity_name]" value="{{ get_class($entity) }}">
{{--<input type="hidden" name="entity_name" value="{{ get_class($entity) }}">--}}
<input type="hidden" name="constructorData[{{$lang}}][entity_id]" value="{{ $entity->id }}">
{{--<input type="hidden" name="entity_id" value="{{ $entity->id }}">--}}

<div class="card">
    <div class="card-header">
        <span class="card-title d-block w-100 mb-3">Конструктор</span>

        <div id="componentsScopeContainer" style="display: flex;flex-wrap: wrap;gap: 5px;">
            @foreach($entity->availableConstructorComponents() as $component)
                @push('styles')
                    {!! $component->styles() !!}
                @endpush

                @push('scripts')
                    {!! $component->scripts($lang) !!}
                @endpush

                <button type="button" class="btn btn-outline-dark clone-component-{{$lang}} btn-sm" data-component="{{ $component->name() }}">{{ trans($component->label()) }}</button>

                <div style="display:none;">
                    {!! $component->show($lang) !!}
                </div>
            @endforeach
        </div>
    </div>

    <input type="hidden" name="placeholder" value="{{ $placeholder }}">

    <div class="card-body">
        <div style="display: flex;justify-content: flex-end;margin-top: -15px;">
            <span class="btn btn-link collapse-btn-{{$lang}}">Згорнути все</span>
            <span class="btn btn-link expand-btn-{{$lang}}">Розгорнути все</span>
        </div>
        <div id="componentContainer_{{$lang}}" class="mt-3 componentContainer componentContainer-{{$lang}}">
            @foreach($entity->definedConstructorComponents(old('constructorData.'.$lang.'.constructor',[])) as $component)
                {!! $component->show($lang) !!}
            @endforeach
        </div>
    </div>
</div>

@push('styles')
    <style type="text/css">
        .confirm-delete-component-popup {
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

        .component-visibility-switch {
            position: absolute;
            top: 4px;
            right: 60px;
            z-index: 9;
        }

        .display-layout {
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

        .display-layout.display-off {
            display: block;
        }

        .collapse-button {

        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            @if($entity->id)
            $(".componentContainer.componentContainer-{{$lang}}").each(function(){
                $(this).find('[name]').each(function(){
                    if($(this).attr('name')){
                        let name = $(this).attr('name').replace('constructor','constructorData[{{$lang}}][constructor]');
                        $(this).attr('name',name);
                    }
                });
            });
            @endif

            $(".collapse-btn-{{$lang}}").on("click", function () {
                $(".componentContainer-{{$lang}} .card-component").each(function () {
                    let flag = $(this).find('.collapse-button').attr('aria-expanded');

                    if(flag == 'true'){
                        console.log(flag)
                        $(this).find('.collapse-button').trigger('click');
                    }
                });
            })

            $(".expand-btn-{{$lang}}").on("click", function () {
                $(".componentContainer-{{$lang}} .card-component").each(function () {
                    let flag = $(this).find('.collapse-button').attr('aria-expanded');
                    if(flag == 'false'){
                        $(this).find('.collapse-button').trigger('click');
                    }
                });
            })

            $('.collapse').on('hide.bs.collapse\t', function () {
                const collapse_button = $(this).parents('.card-component').find('.collapse-button');

                if (collapse_button) {
                    collapse_button.html('<i class="far fa-caret-square-up"></i>');
                }
            });

            $('.collapse').on('show.bs.collapse\t', function () {
                const collapse_button = $(this).parents('.card-component').find('.collapse-button');

                if (collapse_button) {
                    collapse_button.html('<i class="far fa-caret-square-down"></i>');
                }
            });

            String.prototype.replaceAll = function (search, replace) {
                return this.split(search).join(replace);
            };

            const component_container = $('#componentContainer_{{$lang}}');
            const component_placeholder = $('input[name=placeholder]').val();

            init_summernote(component_container);
            init_select2(component_container);
            setTimeout(function () {
                init_select2_widget(component_container);
            },1000);

            /* Init sortable components */
            component_container.sortable({
                handle: '.move-label',
                axis: 'y',
                tolerance: 'pointer',
                cursor: 'move',
                update: function (event, ui) {
                    const sortable_array = $(this).sortable('toArray');
                    refresh_order(sortable_array);
                }
            });
            /* End Init sortable components */

            /* Create component */
            $('.clone-component-{{$lang}}').on('click', function (e) {
                const component = $(this).data('component');

                if ($('*').is('.' + component + '.' + component + '_{{$lang}}')) {
                    const clone_component = $('.' + component + '.' + component + '_{{$lang}}')
                        .clone()[0]
                        .outerHTML
                        .replaceAll(component_placeholder, computation_component_id(component_container))
                        .replaceAll('name="constructor', 'name="constructorData[{{$lang}}][constructor]');

                    component_container.append(clone_component);

                    init_summernote(component_container);
                    init_select2(component_container);
                    init_select2_widget(component_container);

                    const sortable_array = component_container
                        .sortable('refreshPositions')
                        .sortable('toArray');

                    refresh_order(sortable_array);

                    // Плавный скрол к созданомуу компоненту
                    let cc_id = $(clone_component).attr('id');
                    $("html, body").animate({
                        scrollTop: $($("#" + cc_id)).offset().top + "px"
                    }, {
                        duration: 300
                    });
                }
            });
            /* End Create component */

            /* Remove component */
            $(document).on('click', '.remove-component', function (e) {
                e.preventDefault();

                const that = this;
                const confirm_delete_popup = $(that)
                    .parents('.card-component')
                    .find('.confirm-delete-component-popup');

                confirm_delete_popup.show();

                confirm_delete_popup
                    .find('.confirm-button')
                    .on('click', function () {
                        if ($(this).data('action') === 'confirm') {
                            $(that).parents('.card-component').remove();
                            confirm_delete_popup.hide();
                        } else {
                            confirm_delete_popup.hide();
                        }
                    });

                $(document).on('click', function (e) {
                    if (!confirm_delete_popup.is(e.target) && !$(that).is(e.target) && !$(that).children().is(e.target) && confirm_delete_popup.has(e.target).length === 0) {
                        confirm_delete_popup.hide();
                    }
                });
            });
            /* End Remove component */

            /* Show / hide component */
            $('.show-hide-checkbox').each(function () {
                on_off_block(this);
            });

            $(document).on('change', '.show-hide-checkbox', function () {
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

            function computation_component_id(component_container) {
                const components = component_container.children();

                if (components.length > 0) {
                    const array_components = [];

                    for (let i = 0; i < components.length; i++) {
                        let propertyValue = +$(components[i]).data('component-id');
                        array_components.push(propertyValue);
                    }

                    return Math.max.apply(null, array_components) + 1;
                }

                return 1;
            }

            function refresh_order(elements) {
                if (elements.length > 0) {
                    elements.forEach(function (item, i) {
                        $("#componentContainer_{{$lang}}").find('#' + elements[i])
                            .find('.position-component')
                            .val(i + 1);
                    });
                }
            }

            function init_summernote(component_container) {
                component_container.children().each(function () {
                    $(this).find('textarea').each(function () {
                        if ($(this).hasClass('summernote')) {
                            $(this).summernote(summernote_options);
                        }
                    });
                });
            }

            function init_select2(component_container) {
                component_container.children().each(function () {
                    $(this).find('select').each(function () {
                        if ($(this).hasClass('select2-field')) {
                            $(this).select2();
                        }
                    });
                });
            }

            function init_select2_widget(component_container) {
                component_container.children().each(function () {
                    $(this).find('.select2-widget').each(function () {
                        if ($(this).hasClass('select2-widget')) {
                            $(this).select2({});
                        }
                    });

                    $(this).find('.btn-icon-select2-ready2').each(function () {
                        if ($(this).hasClass('btn-icon-select2-ready2')) {
                            $(this).select2({
                                templateResult: formatStateIcon,
                                templateSelection: formatStateIcon,
                            });
                        }
                    });
                });
            }

            $(document).on('click', '.post-linked-in', function(e) {
                e.preventDefault();

                const widgetId = $(this).attr('data-id');
                const input = document.createElement("input");

                input.value = widgetId;
                document.body.appendChild(input);
                input.select();

                try {
                    document.execCommand('copy');
                }
                catch (err) {
                    console.log('Oops, unable to copy');
                }

                document.body.removeChild(input);
            });

        });
    </script>
@endpush
