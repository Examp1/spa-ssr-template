<div class="form-group row">
    <label class="col-md-3 text-right" for="copy_from">З</label>
    <div class="col-md-9">
        <select id="copy_from" class="form-control">
            @foreach(config('translatable.locales') as $lang => $item)
                <option value="{{$lang}}">{{$item}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="copy_to">На</label>
    <div class="col-md-9">
        <select id="copy_to" class="form-control">
            <option value="">---</option>
            @foreach(config('translatable.locales') as $lang => $item)
                <option value="{{$lang}}" @if($lang == config('translatable.locale')) disabled @endif>{{$item}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="constructor_replace">Замінювати блоки конструктора</label>
    <div class="col-md-9">
        <select id="constructor_replace" class="form-control">
            <option value="yes">Так</option>
            <option value="no">Ні</option>
        </select>
    </div>
</div>

<span class="btn btn-success copy-from-lang-btn text-white"
    data-model_id="{{$model_id}}"
    data-route="{{$route}}"
>{{__("Copy")}}</span>

@push('scripts')
    <script>
        $(document).ready(function (){
            $("#copy_from").on("change",function(){
                $("#copy_to").val("");
                $("#copy_to").find('option').prop('disabled',false);
                $("#copy_to").find('option[value="'+$(this).val()+'"]').prop('disabled',true);
            });

            $(".copy-from-lang-btn").on('click',function (){
                $("#copy_to").removeClass('is-invalid');
                if($("#copy_to").val() == ""){
                    $("#copy_to").addClass('is-invalid');
                    return;
                }

                let model_id = $(this).data('model_id');
                let route = $(this).data('route');

                $("#form_copy_model_lang").attr('action',route);
                $("#form_copy_model_lang").find("#form_copy__model_id").val(model_id);
                $("#form_copy_model_lang").find("#form_copy__from").val($("#copy_from").val());
                $("#form_copy_model_lang").find("#form_copy__to").val($("#copy_to").val());
                $("#form_copy_model_lang").find("#form_copy__constructor_replace").val($("#constructor_replace").val());
                $("#form_copy_model_lang").submit();
            })
        });
    </script>
@endpush
