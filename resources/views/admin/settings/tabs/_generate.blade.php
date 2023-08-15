<h4>{{ __('Meta data generation') }}</h4>

<div class="form-group row">
    <label class="col-md-3 text-right">{{ __('Template for Title') }}</label>
    <div class="col-md-6">
        <input type="text" name="setting_data[{{ $key }}][{{$tab}}_template_title]" value="{{ old('setting_data.' . $key . '.'.$tab.'_template_title', $data[$key][$tab.'_template_title'][0]['value'] ?? '') }}" class="form-control template-title-input-{{ $key }}">
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right">{{ __('Template for Description') }}</label>
    <div class="col-md-6">
        <input type="text" name="setting_data[{{ $key }}][{{$tab}}_template_description]" value="{{ old('setting_data.' . $key . '.'.$tab.'_template_description', $data[$key][$tab.'_template_description'][0]['value'] ?? '') }}" class="form-control template-desc-input-{{ $key }}">
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right"></label>
    <div class="col-md-6">
        <span style="color: #999">%SiteName% - {{ __('Site name') }}</span><br>
        <span style="color: #999">%PageName% - {{ __('Name of the page') }}</span><br>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right">{{ __('Rewrite all') }}</label>
    <div class="col-md-6">
        <div class="material-switch pull-left">
            <input id="autoGenSwitch{{ $key }}" class="template-rewrite-{{ $key }}" value="1" type="checkbox" />
            <label for="autoGenSwitch{{ $key }}" class="label-success"></label>
        </div>
        <span class="btn btn-primary float-right btn-meta-generate text-white" data-slug="" data-lang="{{ $key }}" data-route="{{$generate_route??''}}">{{ __('Generate') }}</span>
    </div>
</div>
