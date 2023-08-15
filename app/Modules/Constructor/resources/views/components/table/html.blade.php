@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang,'btns_hide' => true])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-6 mb-3 input-group-sm">
                    <label>{{ $params['labels']['title'] }}</label>
                    <input type="text" placeholder="{{ trans($params['labels']['title']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

                    @error(constructor_field_name_dot($key, 'content.title'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-group mb-1">
                        <select {{ empty(old(constructor_field_name($key, 'content.rows'), $content['rows'] ?? [])) ? '' : 'disabled' }}
                                class="form-control @error(constructor_field_name_dot($key, 'content.table_width')) is-invalid @enderror item-row-template-count row-count-component"
                                name="{{ constructor_field_name($key, 'content.table_width') }}">
                            {{-- <option value="">{{ $params['labels']['table_width'] }}</option> --}}
                            @foreach ($params['table_width'] as $slug => $position)
                                <option value="{{ $slug }}" @if (old(constructor_field_name_dot($key, 'content.table_width'), $content['table_width'] ?? '') == $slug) selected @endif>{{ $position }} {{ $params['labels']['table_width'] }}</option>
                            @endforeach
                        </select>
                        @if(!empty(old(constructor_field_name($key, 'content.rows'), $content['rows'] ?? [])))
                            <input type="hidden" name="{{ constructor_field_name($key, 'content.table_width') }}" value="{{ old(constructor_field_name_dot($key, 'content.table_width'), $content['table_width'] ?? '') }}">
                        @endif
                        @error(constructor_field_name_dot($key, 'content.table_width'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                        @enderror
                    </div>
                </div>

                <div class="col-12 input-group-sm mb-3">
                    <label>{{ $params['labels']['style_type'] }}</label>
                    <select class="form-control row-style-type-component
                @error(constructor_field_name_dot($key, 'content.style_type')) is-invalid @enderror"
                            name="{{ constructor_field_name($key, 'content.style_type') }}"
                        {{ empty(old(constructor_field_name($key, 'content.rows'), $content['rows'] ?? [])) ? '' : 'disabled' }}
                    >
                        @foreach($params['style_type'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.style_type'), $content['style_type'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                    @if(!empty(old(constructor_field_name($key, 'content.rows'), $content['rows'] ?? [])))
                        <input type="hidden" name="{{ constructor_field_name($key, 'content.style_type') }}" value="{{ old(constructor_field_name_dot($key, 'content.style_type'), $content['style_type'] ?? '') }}">
                    @endif
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <div style="display: none;">
                            @foreach ($params['table_width'] as $col_count => $width)
                                <div data-item-id="#blogTableRowPlaceholder{{ $col_count }}"
                                     class="item-row-template{{ $col_count }} item-group mb-1 bg-light border border-dark p-1 col-12">

                                    <div class="row-icon-select2-container" style="margin: 10px 15px; display: flex; align-items: baseline; gap: 15px">
                                        <label>Иконка</label>
                                        <select class="form-control row-icon-select2" style="width: 50px;"
                                                name="{{ constructor_field_name($key, 'content.rows') }}[#blogTableRowPlaceholder{{ $col_count }}][icon]"
                                        >
                                            @foreach($params['icons'] as $listKey => $listItem)
                                                @if($listKey !== "non")
                                                    <option value="{{ $listKey }}" data-icon="{{$listKey}}">{{ $listItem }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <div style="display: block; width: 100%">
                                            @for($i = 0; $i  < $col_count; $i++)
                                                <div style="display: inline-block; vertical-align: top; width: calc({{100 / $col_count}}% - 5px)">
                                                    <div class="form-group mb-1">
                                                        <label>{{ $params['labels']['column_width'] }}</label>
                                                        <select
                                                            class="form-control item-row-template-count"
                                                            name="{{ constructor_field_name($key, 'content.rows') }}[#blogTableRowPlaceholder{{ $col_count }}][{{ $i }}][column_width]">
                                                            {{-- <option value="">{{ $params['labels']['column_width'] }}</option> --}}
                                                            @foreach ($params['cols_width'] as $w_value => $w_text)
                                                                <option value="{{ $w_value }}">{{ $w_text }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>

                                                    <div class="form-group mb-1">
                                                        <label>{{ $params['labels']['column_text'] }}<button type="button" class="btn btn-succes btn-sm edit-item ml-1"><i class="fas fa-edit text-green"></i></button><button type="button" class="btn btn-succes btn-sm edit-item-del"><i class="fas fa-window-close text-red"></i></button></label>
                                                        <textarea
                                                            rows="10"
                                                            class="form-control small_summernote"
                                                            name="{{ constructor_field_name($key, 'content.rows') }}[#blogTableRowPlaceholder{{ $col_count }}][{{ $i }}][column_text]"></textarea>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="mt-1 text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Mob.rows --}}
                            @foreach ($params['table_width'] as $col_count => $width)
                                @if($col_count == "2")
                                    <div data-item-id="#blogTableRowPlaceholder{{ $col_count }}"
                                         class="item-mob_row-template{{ $col_count }} item-group mb-1 bg-light border border-dark p-1 col-12">

                                        <div class="mob_row-icon-select2-container" style="margin: 10px 15px; display: flex; align-items: baseline; gap: 15px">
                                            <label>Иконка</label>
                                            <select class="form-control mob_row-icon-select2" style="width: 50px;"
                                                    name="{{ constructor_field_name($key, 'content.mob_rows') }}[#blogTableRowPlaceholder{{ $col_count }}][icon]"
                                            >
                                                @foreach($params['icons'] as $listKey => $listItem)
                                                    @if($listKey !== "non")
                                                        <option value="{{ $listKey }}" data-icon="{{$listKey}}">{{ $listItem }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <div style="display: block; width: 100%">
                                                @for($i = 0; $i  < $col_count; $i++)
                                                    <div style="display: inline-block; vertical-align: top; width: calc({{100 / $col_count}}% - 5px)">
                                                        <div class="form-group mb-1">
                                                            <label>{{ $params['labels']['column_width'] }}</label>
                                                            <select
                                                                class="form-control item-row-template-count"
                                                                name="{{ constructor_field_name($key, 'content.mob_rows') }}[#blogTableRowPlaceholder{{ $col_count }}][{{ $i }}][column_width]">
                                                                {{-- <option value="">{{ $params['labels']['column_width'] }}</option> --}}
                                                                @foreach ($params['cols_width'] as $w_value => $w_text)
                                                                    <option value="{{ $w_value }}">{{ $w_text }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label>{{ $params['labels']['column_text'] }}<button type="button" class="btn btn-succes btn-sm edit-item ml-1"><i class="fas fa-edit text-green"></i></button><button type="button" class="btn btn-succes btn-sm edit-item-del"><i class="fas fa-window-close text-red"></i></button></label>
                                                            <textarea
                                                                rows="10"
                                                                class="form-control small_summernote"
                                                                name="{{ constructor_field_name($key, 'content.mob_rows') }}[#blogTableRowPlaceholder{{ $col_count }}][{{ $i }}][column_text]"></textarea>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mt-1 text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.rows') }}" value="">
                    <div class="items-row-container d-flex justify-content-center flex-wrap row">
                        @foreach ((array) old(constructor_field_name($key, 'content.rows'), $content['rows'] ?? []) as $k => $value)
                            @if($value)
                                <div data-item-id="{{ $k }}"
                                     class="item-template item-group mb-1 bg-light border border-dark p-1 col-12">

                                    @if($content['style_type'] == "icons")
                                        <div class="row-icon-select2-container" style="margin: 10px 15px; display: flex; align-items: baseline; gap: 15px">
                                            <label>Иконка</label>
                                            <select class="form-control row-icon-select2-ready" style="width: 50px;"
                                                    name="{{ constructor_field_name($key, 'content.rows') }}[{{ $k }}][icon]"
                                            >
                                                @foreach($params['icons'] as $listKey => $listItem)
                                                    @if($listKey !== "non")
                                                        <option
                                                            value="{{ $listKey }}"
                                                            data-icon="{{$listKey}}"
                                                            @if (old(constructor_field_name_dot($key, 'content.rows') . '.{{ $k }}.icon', $value['icon'] ?? '') == $listKey) selected @endif
                                                        >{{ $listItem }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <?php unset($value["icon"])?>

                                    <div>
                                        <div style="display: block; width: 100%">
                                            @foreach((array) $value as $col => $text)
                                                <div style="display: inline-block; vertical-align: top; width: calc({{100 / count($value)}}% - 5px)">
                                                    <div class="form-group mb-1">
                                                        <label>{{ $params['labels']['column_width'] }}</label>
                                                        <select
                                                            class="form-control item-row-template-count"
                                                            name="{{ constructor_field_name($key, 'content.rows') }}[{{ $k }}][{{ $col }}][column_width]">
                                                            {{-- <option value="">{{ $params['labels']['column_width'] }}</option> --}}
                                                            @foreach ($params['cols_width'] as $w_value => $w_text)
                                                                <option value="{{ $w_value }}" @if (old(constructor_field_name_dot($key, 'content.rows') . '.{{ $k }}.{{ $col }}.column_width', $text['column_width'] ?? '') == $w_value) selected @endif>{{ $w_text }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error(constructor_field_name_dot($key, 'content.rows') . '.' . $k . '.' . $col . '.column_width')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group mb-1">
                                                        <label>{{ $params['labels']['column_text'] }}<button type="button" class="btn btn-succes btn-sm edit-item ml-1"><i class="fas fa-edit text-green"></i></button><button type="button" class="btn btn-succes btn-sm edit-item-del"><i class="fas fa-window-close text-red"></i></button></label>
                                                        <textarea
                                                            rows="10"
                                                            class="form-control small_summernote @error(constructor_field_name_dot($key, 'content.rows') . '.{{ $k }}.{{ $col }}.column_text') is-invalid @enderror"
                                                            name="{{ constructor_field_name($key, 'content.rows') }}[{{ $k }}][{{ $col }}][column_text]">{{ $text['column_text'] ?? '' }}</textarea>

                                                        @error(constructor_field_name_dot($key, 'content.rows') . '.' . $k . '.' . $col . '.column_text')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mt-1 text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <hr>
                    <h4 class="text-center">Mob. Table</h4>
                    <div class="d-flex justify-content-center flex-wrap row">
                        <div class="col-12">
                            <div class="form-group mb-1">
                                <select {{ empty(old(constructor_field_name($key, 'content.mob_rows'), $content['mob_rows'] ?? [])) ? '' : 'disabled' }}
                                        class="form-control @error(constructor_field_name_dot($key, 'content.table_mob_width')) is-invalid @enderror item-mob_row-template-count mob_row-count-component"
                                        name="{{ constructor_field_name($key, 'content.table_mob_width') }}">
                                    {{-- <option value="">{{ $params['labels']['table_width'] }}</option> --}}
                                    @foreach ($params['table_width'] as $slug => $position)
                                        @if($slug == "2")
                                            <option value="{{ $slug }}" @if (old(constructor_field_name_dot($key, 'content.table_mob_width'), $content['table_mob_width'] ?? '') == $slug) selected @endif>{{ $position }} {{ $params['labels']['table_width'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @if(!empty(old(constructor_field_name($key, 'content.mob_rows'), $content['mob_rows'] ?? [])))
                                    <input type="hidden" name="{{ constructor_field_name($key, 'content.table_mob_width') }}" value="{{ old(constructor_field_name_dot($key, 'content.table_mob_width'), $content['table_mob_width'] ?? '') }}">
                                @endif
                                @error(constructor_field_name_dot($key, 'content.table_mob_width'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 input-group-sm mb-3">
                            <label>{{ $params['labels']['style_type'] }}</label>
                            <select class="form-control mob_row-style-type-component
                @error(constructor_field_name_dot($key, 'content.mob_style_type')) is-invalid @enderror"
                                    name="{{ constructor_field_name($key, 'content.mob_style_type') }}"
                                {{ empty(old(constructor_field_name($key, 'content.mob_rows'), $content['mob_rows'] ?? [])) ? '' : 'disabled' }}
                            >
                                @foreach($params['style_type'] as $listKey => $litItem)
                                    <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.mob_style_type'), $content['mob_style_type'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                                @endforeach
                            </select>
                            @if(!empty(old(constructor_field_name($key, 'content.mob_rows'), $content['mob_rows'] ?? [])))
                                <input type="hidden" name="{{ constructor_field_name($key, 'content.mob_style_type') }}" value="{{ old(constructor_field_name_dot($key, 'content.mob_style_type'), $content['mob_style_type'] ?? '') }}">
                            @endif
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.mob_rows') }}" value="">
                    <div class="items-mob_row-container d-flex justify-content-center flex-wrap row">
                        @foreach ((array) old(constructor_field_name($key, 'content.mob_rows'), $content['mob_rows'] ?? []) as $k => $value)
                            @if($value)
                                <div data-item-id="{{ $k }}"
                                     class="item-template item-group mb-1 bg-light border border-dark p-1 col-12">

                                    @if($content['style_type'] == "icons")
                                        <div class="mob_row-icon-select2-container" style="margin: 10px 15px; display: flex; align-items: baseline; gap: 15px">
                                            <label>Иконка</label>
                                            <select class="form-control row-icon-select2-ready" style="width: 50px;"
                                                    name="{{ constructor_field_name($key, 'content.mob_rows') }}[{{ $k }}][icon]"
                                            >
                                                @foreach($params['icons'] as $listKey => $listItem)
                                                    @if($listKey !== "non")
                                                        <option
                                                            value="{{ $listKey }}"
                                                            data-icon="{{$listKey}}"
                                                            @if (old(constructor_field_name_dot($key, 'content.mob_rows') . '.{{ $k }}.icon', $value['icon'] ?? '') == $listKey) selected @endif
                                                        >{{ $listItem }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <?php unset($value["icon"])?>

                                    <div>
                                        <div style="display: block; width: 100%">
                                            @foreach((array) $value as $col => $text)
                                                <div style="display: inline-block; vertical-align: top; width: calc({{100 / count($value)}}% - 5px)">
                                                    <div class="form-group mb-1">
                                                        <label>{{ $params['labels']['column_width'] }}</label>
                                                        <select
                                                            class="form-control item-row-template-count"
                                                            name="{{ constructor_field_name($key, 'content.mob_rows') }}[{{ $k }}][{{ $col }}][column_width]">
                                                            {{-- <option value="">{{ $params['labels']['column_width'] }}</option> --}}
                                                            @foreach ($params['cols_width'] as $w_value => $w_text)
                                                                <option value="{{ $w_value }}" @if (old(constructor_field_name_dot($key, 'content.mob_rows') . '.{{ $k }}.{{ $col }}.column_width', $text['column_width'] ?? '') == $w_value) selected @endif>{{ $w_text }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error(constructor_field_name_dot($key, 'content.mob_rows') . '.' . $k . '.' . $col . '.column_width')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group mb-1">
                                                        <label>{{ $params['labels']['column_text'] }}<button type="button" class="btn btn-succes btn-sm edit-item ml-1"><i class="fas fa-edit text-green"></i></button><button type="button" class="btn btn-succes btn-sm edit-item-del"><i class="fas fa-window-close text-red"></i></button></label>
                                                        <textarea
                                                            rows="10"
                                                            class="form-control small_summernote @error(constructor_field_name_dot($key, 'content.mob_rows') . '.{{ $k }}.{{ $col }}.column_text') is-invalid @enderror"
                                                            name="{{ constructor_field_name($key, 'content.mob_rows') }}[{{ $k }}][{{ $col }}][column_text]">{{ $text['column_text'] ?? '' }}</textarea>

                                                        @error(constructor_field_name_dot($key, 'content.mob_rows') . '.' . $k . '.' . $col . '.column_text')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mt-1 text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <button type="button"
                        class="add-blogTable-row_{{$lang}} btn btn-success btn-sm d-block mt-2">Добавить ряд</button>
                <button type="button"
                        class="add-blogTable-mob_row_{{$lang}} btn btn-success btn-sm d-block mt-2">Добавить ряд (mob.)</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            <div class="row">
                <div class="col-3 input-group-sm mb-3">
                    <label>{{ $params['labels']['title_font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.title_font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title_font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.title_font_size'), $content['title_font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>

    </div>
</div>

@include('constructor::layouts.footer')
