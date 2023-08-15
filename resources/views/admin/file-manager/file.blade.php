@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item">{{ __('Multimedia') }}</li>
            <li class="breadcrumb-item">{{ __('Files') }}</li>
        </ol>
    </nav>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <iframe src="/filemanager?type=file" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(() => {

        });
    </script>
@endpush

