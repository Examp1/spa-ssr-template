@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item">{{ __('Blog') }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Subscriptions') }}</li>
        </ol>
    </nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <textarea class="form-control" rows="20">{!! $data !!}</textarea>
            </div>
        </div>
    </div>
</div>
@endsection

