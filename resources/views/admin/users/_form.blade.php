@if ($action === 'create')
    <form class="form-horizontal" method="POST" action="{{ route('users.store') }}">
@elseif($action === 'edit')
    <form action="{{ route('users.update', $model->id) }}" class="form-horizontal" method="post">
        @method('PUT')
@endif
@csrf
<input type="hidden" name="id" value="{{ $model->id }}">
<div class="form-group row">
    <label class="col-md-3 text-right required" for="name">{{ __('Name') }}</label>
    <div class="col-md-9">
        <input type="text" name="name" id="name"
            class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', $model->name) }}">

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right required" for="email">E-mail</label>
    <div class="col-md-9">
        <input type="text" name="email" id="email"
            class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
            value="{{ old('email', $model->email) }}">

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="phone">{{ __('Phone') }}</label>
    <div class="col-md-9">
        <input type="text" name="phone" id="phone"
            class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}"
            value="{{ old('phone', $model->phone) }}">

        @if ($errors->has('phone'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('phone') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right @if (!$model->id) required @endif" for="password">Пароль</label>
    <div class="col-md-9">
        <input type="password" name="password" id="password"
            class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}">

        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_status">{{ __('Status') }}</label>
    <div class="col-md-9">
        <div class="material-switch pull-left">
            <input name="status" value="0" type="hidden" />
            <input id="someSwitchOptionSuccess" name="status" value="1" type="checkbox"
                {{ old('status', $model->status) ? ' checked' : '' }} />
            <label for="someSwitchOptionSuccess" class="label-success"></label>
        </div>

        @if ($errors->has('status'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('status') }}</strong>
            </span>
        @endif
    </div>
</div>

<input type="submit" class="btn btn-success text-white" value="{{ __('Save') }}">
</form>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #009688 !important;
            border: 1px solid #009688 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color: #009688 !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            color: #3e5569;
            background-color: #fff;
            border-color: rgba(0, 0, 0, 0.25);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(() => {
            $('.select2-field').select2();
        });
    </script>
@endpush
