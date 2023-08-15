@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    <div class="row">
        <div class="col-6 mb-3 input-group-sm">
            <input type="text" placeholder="{{ trans($params['labels']['title']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

            @error(constructor_field_name_dot($key, 'content.title'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-6 mb-3 input-group-sm">
            <input type="text" placeholder="{{ trans($params['labels']['link']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.link')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.link') }}" value="{{ old(constructor_field_name_dot($key, 'content.link'), $content['link'] ?? '') }}">

            @error(constructor_field_name_dot($key, 'content.link'))
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
