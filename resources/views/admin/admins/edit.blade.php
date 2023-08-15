@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admins.index')}}">{{ __('Admins') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @include('admin.admins._form',['model' => $model, 'action' => 'edit'])
            </div>
        </div>
    </div>
</div>
@endsection
