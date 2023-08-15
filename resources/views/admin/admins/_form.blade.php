@if ($action === 'create')
    <form class="form-horizontal" method="POST" action="{{ route('admins.store') }}">
    @elseif($action === 'edit')
        <form action="{{ route('admins.update', $model->id) }}" class="form-horizontal" method="post">
            @method('PUT')
@endif
@csrf

<div class="form-group row">
    <label class="col-md-3 text-right required" for="name">{{ __('Name') }}</label>
    <div class="col-md-9">
        <input type="text" name="name" id="name"
            class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
            value="{{ old('name', $model->name) }}">

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right required" for="group_ids">{{ __('Roles') }}</label>
    <div class="col-md-9">
        <select name="roles[]" id="roles_ids" multiple
            class="select2-field {{ $errors->has('roles') ? ' is-invalid' : '' }}" style="width: 100%">
            @foreach (\Spatie\Permission\Models\Role::query()->get() as $item)
                <option value="{{ $item->name }}" @if ($model->hasRole($item->name)) selected @endif>
                    {{ $item->name }}</option>
            @endforeach
        </select>

        @if ($errors->has('roles'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('roles') }}</strong>
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

<hr>
<div class="form-group row">
    <label class="col-md-3 text-right" for="crm_access">{{ __('CRM Access') }}</label>
    <div class="col-md-9">
        <select name="crm_access" class="form-control" id="crm_access">
            <option value="0" @if( $model->crm_access == 0) selected @endif >Ні</option>
            <option value="1" @if( $model->crm_access == 1) selected @endif>Так</option>
        </select>
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
