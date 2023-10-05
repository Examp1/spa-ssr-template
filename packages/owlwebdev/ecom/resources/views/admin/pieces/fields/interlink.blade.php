<div class="input-group interlink-container">
    <div class="input-group-prepend">
        <span class="input-group-text">{{ $label ?? __('Link') }}</span>
    </div>
    <select class="form-control interlink-select-type-{{ $lang }}" name="{{ $name_type }}">
        @foreach (config('menu.entities') as $key => $mType)
            <option value="{{ $key }}" @if (isset($sel_type) && $sel_type == $key) selected @endif>
                {{ $mType::SHOWN_NAME }}</option>
        @endforeach
        <option value="arbitrary" @if (isset($sel_type) && $sel_type == 'arbitrary') selected @endif>{{ __('Arbitrary link') }}</option>
    </select>

    @foreach (config('menu.entities') as $key => $mType)
        <div class="select-type select-type-{{ $key }}"
            @if ((!isset($sel_type) && $key === 'Pages') || (isset($sel_type) && $sel_type == $key)) style="display: initial;" @else style="display: none;" @endif>
            <select
                class="form-control @isset($sel_type) select2-internal-init @else select2-internal @endif" style="width: 300px" name="{{ $name_val }}[{{ $key }}]">
                {!! $mType::getOptionsHTML($sel_val[$sel_type ?? null] ?? null) !!}
            </select>
        </div>
    @endforeach

    <input type="text" class="form-control select-type select-type-arbitrary" @if (isset($sel_type) && $sel_type == 'arbitrary') value="{{ $sel_val[$sel_type] }}" @endif  @if (isset($sel_type) && $sel_type == 'arbitrary') style="display: initial;" @else style="display: none;" @endif name="{{ $name_val }}[arbitrary]">
        </div>
