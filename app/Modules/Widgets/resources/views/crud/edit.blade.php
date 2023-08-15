@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="/admin/widgets?lang={{request()->input('lang',config('translatable.locale'))}}">{{ __('Widgets') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $widget->instance }}</li>
            <li class="breadcrumb-item active" aria-current="page">
                {{request()->input('lang')}}
            </li>
        </ol>
    </nav>

    <form action="{{ route(config('widgets.route_name_prefix', 'admin.') . 'widgets.update', $widget) }}" method="post">
        @csrf

        @method('put')

        <input type="hidden" name="instance" value="{{ $widget->instance }}">
        <input type="hidden" name="lang" value="{{ request()->get('lang') ?? config('translatable.locale') }}">

        <div class="card">
            <div class="card-header">
                <div class="form-row">
                    <div class="form-group input-group-sm col-sm-6">
                        <label for="widgetName">{{ __('Widget name') }}</label>

                        <input type="text" name="name" id="widgetName"
                            class="form-control @error('name') is-invalid @enderror" value="{{ $widget->name ?? '' }}">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group input-group-sm col-sm-6">
                        @foreach(config('translatable.locale_codes') as $langCode => $langName)
                            @if($langCode != request()->input('lang'))
                                @if($widget->isExistsLang($langCode))
                                    <a href="/admin/widgets/{{$widget->getLangId($langCode)}}/edit?lang={{$langCode}}" title="Перейти" class="btn btn-success float-right text-white" style="margin: 0 5px">
                                        {{$langName}}
                                    </a>
                                @else
                                    <a href="/admin/widgets/copy/{{$widget->id}}/{{$langCode}}" class="btn btn-danger float-right text-white" style="margin: 0 5px" title="Создать виджет на основе текущего">
                                        <i class="fa fa-copy"></i>
                                        {{$langName}}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-body">
                @foreach ($object->fields() as $field)
                    @if (isset($field['separator']))
                        @if (!$loop->first)
                            <br /><br />
                        @endif

                        <h5>{{ trans($field['separator']) }}</h5>
                        <hr />
                    @else
                        @includeif('widgets::fields.' . $field['type'], ['value' => isset($widget,
                        $widget->data[$field['name']]) ? $widget->data[$field['name']] : $field['value'], 'list' =>
                        isset($field['list']) ? $field['list'] : [],'alt_value' => isset($widget, $field['alt_name'],
                        $widget->data[$field['alt_name']]) ? $widget->data[$field['alt_name']] : ($field['alt_value'] ?? '')])
                    @endif
                @endforeach
            </div>
        </div>

        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-sm btn-info btn-lg">
                <i class="far fa-save"></i>
                {{ __('Update') }}
            </button>
        </div>
    </form>
@endsection
