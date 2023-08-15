<div id="component-{{ $key }}" class="card card-default {{ $name }} {{$name}}_{{$lang}} card-component" data-component-id="{{ $key }}">
    <div class="display-layout"></div>

    <div class="confirm-delete-component-popup" style="display: none;">
        <h5 class="text-sm">{{__('Are you sure you want to delete?')}}</h5>
        <button class="btn btn-sm btn-secondary confirm-button" type="button" data-action="cancel">{{__('Cancel')}}</button>
        <button class="btn btn-sm btn-danger confirm-button text-white" type="button" data-action="confirm">{{__('Remove')}}</button>
    </div>

    <div class="card card-outline card-info pt-1 pr-3 pb-1 pl-3 mb-0">
        <div class="card-title move-label d-inline">
            <i class="fas fa-braille mr-3"></i>
            <?php
            if($params['shown_name'] != 'widget'){
                $shownName = isset($content[$params['shown_name']]) ? mb_strimwidth($content[$params['shown_name']], 0, 30, "...") : '';
                $resTitle = '<span style="font-weight: normal">'.$shownName.'</span>';
            } else {
                if(isset($content['widget'])){
                    $model = \App\Modules\Widgets\Models\Widget::query()->where('id',$content['widget'])->first();
                    try {
                        $shownName = mb_strimwidth($model->name, 0, 30, "...");
                        $linkEdit = '/admin/widgets/'.$model->id.'/edit?lang=' . $lang;
                        $resTitle ='<a target="_blank" href="'.$linkEdit.'" title="Редагувати" style="font-weight: normal">'.$shownName.'</a>';
                        echo '<span class="widget-data" data-widget_name="'.$model->name.'" data-widget_id="widget_'.$model->instance.'_'.$model->id.'"></span>';
                        $widgetId = "widget_".$model->instance."_".$model->id;
                    } catch(Exception $e){
                        $resTitle = '';
                    }
                } else {
                    $resTitle = '';
                }
            }
            ?>
            {{ trans($label) }} - {!! $resTitle !!}
            @isset($widgetId)
                <span class="badge badge-dark">ID: {{$widgetId}}</span>
                <span class="btn btn-md btn-dark post-linked-in" style="padding: 2px 6px;" data-id="{{$widgetId}}">
                    <i class="fa fa-copy"></i>
                </span>
            @endisset

            <span class="float-right">
                <div class="d-inline component-visibility-switch custom-switch custom-switch-off-danger custom-switch-on-success">
                    <input type="hidden" name="{{ constructor_field_name($key, 'visibility') }}" value="0">
                    <input type="checkbox" name="{{ constructor_field_name($key, 'visibility') }}" class="custom-control-input show-hide-checkbox" id="componentVisibility{{ $key }}_{{$lang}}" value="1" @if ($visibility == 1) checked @endif>
                    <label class="custom-control-label" for="componentVisibility{{ $key }}_{{$lang}}"></label>
                </div>

                <a href="#" class="link-inherit text-danger ml-2 remove-component" title="{{__('Remove')}}">
                    <i class="fas fa-trash"></i>
                </a>

                 <a href="#collapse{{ $key }}_{{$lang}}" class="text-info collapse-button ml-2" data-toggle="collapse" aria-expanded="true">
                    <i class="far fa-caret-square-up"></i>
                </a>
            </span>
        </div>
    </div>

    <input type="hidden" name="{{ constructor_field_name($key, 'position') }}" value="{{ $position }}" class="position-component">
    <input type="hidden" name="{{ constructor_field_name($key, 'component') }}" value="{{ $name }}">
