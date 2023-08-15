<div class="card">
    <div class="card-header collapsed" style="cursor: pointer;" id="heading{{$typeName}}"
         data-toggle="collapse" data-target="#collapse{{$typeName}}" aria-expanded="false"
         aria-controls="collapse{{$typeName}}">
        {{$shownName}}
    </div>
    <div id="collapse{{$typeName}}" class="collapse" aria-labelledby="heading{{$typeName}}"
         data-parent="#accordion">
        <div class="card-body">
            <form action="{{ route('menu.add-item') }}" method="post">
                @csrf
                <input type="hidden" name="tag" value="{{ $tag }}">
                <input type="hidden" name="type"
                       value="{{ $type }}">

                <div class="form-group col-12">
                    <input type="text" name="name" placeholder="{{ __('Title') }}"
                           class="form-control">
                </div>
                @if($type != \App\Models\Menu::TYPE_ARBITRARY)
                    <div class="form-group col-12">
                        <select name="model_id" id="model_id_{{rand(1000,9999)}}" class="select2-elem" style="width: 100%">
                            {!! $optionsHTML !!}
                        </select>
                    </div>
                @else
                    <div class="form-group col-12">
                        <input type="text" name="url" placeholder="URL"
                               class="form-control">
                    </div>
                @endif
                <div class="form-group col-12">
                    <input type="submit" value="{{ __('Create') }}" style="margin-bottom: 15px;"
                           class="btn btn-success text-white float-right">
                </div>
            </form>
        </div>
    </div>
</div>
