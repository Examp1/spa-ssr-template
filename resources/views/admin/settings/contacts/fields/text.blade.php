<div class="form-group row">
    <label class="col-md-3 text-right">{{$fieldTitle}}</label>
    <div class="col-md-9">
        <input type="text"
               name="setting_data[{{ $lang }}][contacts][{{$countContactBlock}}][{{$fieldName}}]"
               value="{{ old('setting_data.' . $lang . '.contacts.'. $countContactBlock .'.' . $fieldName, $contact[$fieldName] ?? '') }}"
               class="form-control {{ $errors->has('setting_data.' . $lang . '.contacts.'. $countContactBlock .'.' . $fieldName) ? ' is-invalid' : '' }}"
        >
    </div>
</div>
