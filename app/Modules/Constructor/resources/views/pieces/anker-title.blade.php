<div class="row">
    <div class="col-6 input-group-sm mb-3"></div>
    <div class="col-6 input-group-sm mb-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Anker title</span>
            </div>
            <input type="text" placeholder="{{ trans($params['labels']['anker_title']) }}" class="anker-from-input form-control @error(constructor_field_name_dot($key, 'content.anker_title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.anker_title') }}" value="{{ old(constructor_field_name_dot($key, 'content.anker_title'), $content['anker_title'] ?? '') }}">
            <input type="text" name="{{ constructor_field_name($key, 'content.anker_id') }}" class="form-control anker-to-input" value="{{ old(constructor_field_name_dot($key, 'content.anker_id'), $content['anker_id'] ?? '') }}">
            <div class="input-group-append">
                <span class="btn btn-dark anker-copy-btn" title="Скопіювати ID"><i class="fa fa-copy"></i></span>
            </div>
        </div>
    </div>
</div>
