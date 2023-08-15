<input type="hidden" name="_tab" value="{{$tab}}">

<?php
$defaultLang = \App\Models\Langs::getDefaultLangCode();
$font_style_sel = old('setting_data.' . $defaultLang . '.theme_font_style', $data[$defaultLang]['theme_font_style'][0]['value'] ?? '');
$bg_type_sel = old('setting_data.' . $defaultLang . '.theme_bg_type', $data[$defaultLang]['theme_bg_type'][0]['value'] ?? '');
$noise_sel = old('setting_data.' . $defaultLang . '.theme_noise', $data[$defaultLang]['theme_noise'][0]['value'] ?? '');
$gradient_type_sel = old('setting_data.' . $defaultLang . '.theme_gradient_type', $data[$defaultLang]['theme_gradient_type'][0]['value'] ?? '');
?>

<div class="form-group row">
    <label class="col-md-2 text-right">Кольорова схема</label>
    <div class="col-md-10">
        <select name="setting_data[{{ $defaultLang }}][theme_bg_type]" class="form-control select-color-type" id="setting_theme_bg_type_{{ $defaultLang }}">
            <option value="color" @if($bg_type_sel == "color") selected @endif>Колір</option>
            <option value="gradient" @if($bg_type_sel == "gradient") selected @endif>Градієнт</option>
        </select>
    </div>
</div>

<div class="form-group row theme_color_block" @if($bg_type_sel == "gradient") style="display: none" @endif>
    <label class="col-md-2 text-right" for="setting_theme_color_{{ $defaultLang }}">Колір теми</label>
    <div class="col-md-10">
        <input type="color" name="setting_data[{{ $defaultLang }}][theme_color]" value="{{ old('setting_data.' . $defaultLang . '.theme_color', $data[$defaultLang]['theme_color'][0]['value'] ?? '') }}" id="setting_theme_color_{{ $defaultLang }}" class="form-control{{ $errors->has('setting_data.' . $defaultLang . '.theme_color') ? ' is-invalid' : '' }}">
    </div>
</div>

<div class="theme_gradient_block" @if($bg_type_sel == "color") style="display: none" @endif>
    <div class="form-group row">
        <label class="col-md-2 text-right" for="setting_theme_gradient_{{ $defaultLang }}">Кольори градієнту</label>
        <div class="col-md-5">
            <input type="color" name="setting_data[{{ $defaultLang }}][theme_gradient]" value="{{ old('setting_data.' . $defaultLang . '.theme_gradient', $data[$defaultLang]['theme_gradient'][0]['value'] ?? '') }}" id="setting_theme_gradient_{{ $defaultLang }}" class="form-control{{ $errors->has('setting_data.' . $defaultLang . '.theme_gradient') ? ' is-invalid' : '' }}">
        </div>
        <div class="col-md-5">
            <input type="color" name="setting_data[{{ $defaultLang }}][theme_gradient2]" value="{{ old('setting_data.' . $defaultLang . '.theme_gradient2', $data[$defaultLang]['theme_gradient2'][0]['value'] ?? '') }}" id="setting_theme_gradient2_{{ $defaultLang }}" class="form-control{{ $errors->has('setting_data.' . $defaultLang . '.theme_gradient2') ? ' is-invalid' : '' }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 text-right" for="setting_theme_gradient_type_{{ $defaultLang }}">Тип градієнту</label>
        <div class="col-md-10">
            <select name="setting_data[{{ $defaultLang }}][theme_gradient_type]" class="form-control" id="setting_theme_gradient_type_{{ $defaultLang }}">
                <option value="linear" @if($gradient_type_sel == "linear") selected @endif>Linear</option>
                <option value="radial" @if($gradient_type_sel == "radial") selected @endif>Radial</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 text-right" for="setting_theme_gradient_deg_{{ $defaultLang }}">Градус градієнту</label>
        <div class="col-md-10">
            <input type="range"
                   min="0"
                   max="360"
                   name="setting_data[{{ $defaultLang }}][theme_gradient_deg]"
                   value="{{ old('setting_data.' . $defaultLang . '.theme_gradient_deg', $data[$defaultLang]['theme_gradient_deg'][0]['value'] ?? '') }}"
                   id="setting_theme_gradient_deg_{{ $defaultLang }}"
                   class="range-input {{ $errors->has('setting_data.' . $defaultLang . '.theme_gradient_deg') ? ' is-invalid' : '' }}"
            >
            <span id="range_value">{{old('setting_data.' . $defaultLang . '.theme_gradient_deg', $data[$defaultLang]['theme_gradient_deg'][0]['value'] ?? '')}}</span>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 text-right" for="setting_theme_noise_{{ $defaultLang }}">Шум</label>
    <div class="col-md-10">
        <select name="setting_data[{{ $defaultLang }}][theme_noise]" class="form-control" id="setting_theme_noise_{{ $defaultLang }}">
          <option value="0" @if($noise_sel == "0") selected @endif>Ні</option>
         <option value="1" @if($noise_sel == "1") selected @endif>Так</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 text-right">Шрифт</label>
    <div class="col-md-5">
        <select name="setting_data[{{ $defaultLang }}][theme_font_style]" class="form-control select-font-component" id="setting_theme_font_style_{{ $defaultLang }}">
            <option value="">---</option>
            @foreach(config('theme.fonts') as $key => $font)
                <option value="{{$key}}" @if($font_style_sel == $key) selected @endif>{{$font['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        @php($fontSelected = app(Setting::class)->get('theme_font_style', config('translatable.locale')))
        @foreach(config('theme.fonts') as $key => $font)
            <span class="template-font-style" data-font_key="{{$key}}"
                  style="font-family: {{$font['name']}}; font-size: 18px; @if($fontSelected != $key) display: none; @else display: block; @endif"
            >Приклад, як буде виглядати шрифт</span>
        @endforeach
    </div>
</div>

@push('styles')
    @foreach(config('theme.fonts') as $font)
        {!! $font['link'] !!}
    @endforeach

    <style>
        @font-face {
            font-family: "e-Ukraine";
            src: url("/fonts/e-Ukraine/e-Ukraine.otf");
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".select-color-type").on('change', function(){
               let type = $(this).val();

               if(type == "color"){
                   $(".theme_color_block").show();
                   $(".theme_gradient_block").hide();
               } else {
                   $(".theme_color_block").hide();
                   $(".theme_gradient_block").show();
               }
            });

            $(".range-input").on("change, input", function(){
               $("#range_value").text($(this).val());
            });

            $(".select-font-component").on('change', function (){
                let font = $(this).val();
                $(".template-font-style").hide();
                $(".template-font-style[data-font_key='"+font+"']").show();
            });
        });
    </script>
@endpush

