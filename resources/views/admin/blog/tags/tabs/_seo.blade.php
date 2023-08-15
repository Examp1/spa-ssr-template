<div class="form-group row">
    <label class="col-md-3 text-right" for="page_meta_title_{{ $lang }}">Meta title</label>
    <div class="col-md-9">
        <input type="text" data-lang="{{ $lang }}" name="page_data[{{ $lang }}][meta_title]"
            value="{{ old('page_data.' . $lang . '.meta_title', $data[$lang]['meta_title'] ?? '') }}"
            id="page_meta_title_{{ $lang }}"
            class="form-control{{ $errors->has('page_data.' . $lang . '.meta_title') ? ' is-invalid' : '' }} meta-field">

        @if ($errors->has('page_data.' . $lang . '.meta_title'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.meta_title') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_meta_description_{{ $lang }}">Meta description</label>
    <div class="col-md-9">
        <input type="text" data-lang="{{ $lang }}" name="page_data[{{ $lang }}][meta_description]"
            value="{{ old('page_data.' . $lang . '.meta_description', $data[$lang]['meta_description'] ?? '') }}"
            id="page_meta_description_{{ $lang }}"
            class="form-control{{ $errors->has('page_data.' . $lang . '.meta_description') ? ' is-invalid' : '' }} meta-field">

        @if ($errors->has('page_data.' . $lang . '.meta_description'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.meta_description') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right">{{ __('Allow auto-generation') }}</label>
    <div class="col-md-9">
        <div class="material-switch pull-left">
            <input name="page_data[{{ $lang }}][meta_auto_gen]" value="0" type="hidden" />
            @php($autoGenVal = old('page_data.' . $lang . '.meta_auto_gen', $data[$lang]['meta_auto_gen'] ?? 1))
            <input id="autoGenSwitch{{ $lang }}" class="meta_auto_gen_{{ $lang }}"
                name="page_data[{{ $lang }}][meta_auto_gen]" value="1" type="checkbox"
                {{ $autoGenVal ? ' checked' : '' }} />
            <label for="autoGenSwitch{{ $lang }}" class="label-success"></label>
        </div>
    </div>
</div>

<input type="hidden" name="page_data[{{ $lang }}][meta_created_as]"
    class="meta_created_as_{{ $lang }}"
    value="{{ old('page_data.' . $lang . '.meta_created_as', $data[$lang]['meta_created_as'] ?? 0) }}">

<div class="form-group row">
    <label class="col-md-3 text-right"></label>
    <div class="col-md-9">
       <span class="btn btn-primary btn-meta-generate-single-{{$lang}}"
             data-lang="{{ $lang }}"
             data-route="{{route('blog.tags.meta-generate')}}"
             data-template_title="{{app(\App\Modules\Setting\Setting::class)->get('blogtags_template_title',$lang)}}"
             data-template_desc="{{app(\App\Modules\Setting\Setting::class)->get('blogtags_template_description',$lang)}}"

       >{{__("Generate meta")}}</span>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.btn-meta-generate-single-{{$lang}}').on('click', function() {
                let route = $(this).data('route');
                let lang = $(this).data('lang');
                let template_title = $(this).data('template_title');
                let template_desc = $(this).data('template_desc');
                $.ajax({
                    type: "POST",
                    url: route,
                    data: {
                        _token:"{{csrf_token()}}",
                        template_lang: lang,
                        template_title: template_title,
                        template_desc: template_desc,
                        model_id:"{{$model->id}}"
                    },
                    success: function (data) {
                        if(data.success){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            if(data.meta.title || data.meta.description){
                                $("#page_meta_title_" + lang).val(data.meta.title);
                                $("#page_meta_description_" + lang).val(data.meta.description);
                            }
                        }
                    },
                    error: function (jqXHR, text, error) {
                        console.log('seo-title-generate error!');
                    }
                });
            });
        });
    </script>
@endpush
