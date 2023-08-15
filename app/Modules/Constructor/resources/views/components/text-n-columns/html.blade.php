@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-12 mb-3 input-group-sm">
                    <label>{{ $params['labels']['title'] }}</label>
                    <input type="text" placeholder="{{ trans($params['labels']['title']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

                    @error(constructor_field_name_dot($key, 'content.title'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="form-group input-group-sm mb-12">
                        <select class="form-control @error(constructor_field_name_dot($key, 'content.title_column_select')) is-invalid @enderror item-row-template-count" name="{{ constructor_field_name($key, 'content.title_column_select') }}">
                            <option value="">{{ $params['labels']['title_column_select'] }}</option>
                            @foreach($params['columns'] as $slug => $position)
                                <option value="{{ $slug }}" @if (old(constructor_field_name_dot($key, 'content.title_column_select'), $content['title_column_select'] ?? '') == $slug) selected @endif>{{ $position }}</option>
                            @endforeach
                        </select>

                        @error(constructor_field_name_dot($key, 'content.title_column_select'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <div style="display: none;">
                        @foreach ($params['columns'] as $col_count => $width)
                            <div data-item-id="#tableRowPlaceholder{{ $col_count }}"
                                 class="item-row-template{{ $col_count }} item-group mb-1 bg-light border border-dark p-1 col-12">
                                <table class="table table-hover text-nowrap">
                                    <tbody>
                                    <tr>
                                        @for($i = 0; $i  < $col_count; $i++)
                                            <td>
                                                <label>{{ $params['labels']['title_column_select'] }}<button type="button" class="btn btn-succes btn-sm edit-item ml-1"><i class="fas fa-edit text-green"></i></button><button type="button" class="btn btn-succes btn-sm edit-item-del"><i class="fas fa-window-close text-red"></i></button></label>
                                                <textarea rows="10" class="form-control small_summernote" name="{{ constructor_field_name($key, 'content.rows') }}[#tableRowPlaceholder{{ $col_count }}][{{ $i }}][column_text]"></textarea>
                                            </td>
                                        @endfor
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="mt-1 text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.rows') }}" value="">

                    <div class="items-row-container w-100">
                        @foreach ((array) old(constructor_field_name($key, 'content.rows'), $content['rows'] ?? []) as $k => $value)
                            @if($value)
                                <div data-item-id="{{ $k }}"
                                     class="item-template item-group mb-1 bg-light border border-dark p-1 col-12">

                                    <table class="table table-hover text-nowrap">
                                        <tbody>
                                        <tr>
                                            @foreach((array) $value as $col => $text)
                                                <td>
                                                    <label>{{ $params['labels']['title_column_select'] }}<button type="button" class="btn btn-succes btn-sm edit-item ml-1"><i class="fas fa-edit text-green"></i></button><button type="button" class="btn btn-succes btn-sm edit-item-del"><i class="fas fa-window-close text-red"></i></button></label>
                                                    <textarea
                                                        rows="10"
                                                        class="form-control small_summernote @error(constructor_field_name_dot($key, 'content.rows') . '.{{ $k }}.{{ $col }}.column_text') is-invalid @enderror"
                                                        name="{{ constructor_field_name($key, 'content.rows') }}[{{ $k }}][{{ $col }}][column_text]">{{ $text['column_text'] ?? '' }}</textarea>
                                                </td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-1 text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-info btn-sm add-table-row_{{$lang}} d-block mt-2">Додати</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            <div class="row">
                <div class="col-6 input-group-sm mb-3">
                    <label>{{ $params['labels']['title_font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.title_font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title_font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.title_font_size'), $content['title_font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 input-group-sm mb-3">
                    <label>{{ $params['labels']['font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.font_size'), $content['font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'text-n-columns','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
