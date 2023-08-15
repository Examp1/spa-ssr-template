@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item">Mailgun test</li>
        </ol>
    </nav>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <form action="{{route('mailgun-test.send')}}" method="POST">
                    @csrf

                <div class="form-group row">
                    <label class="col-md-3 text-right">From</label>
                    <div class="col-md-9">
                        <input type="text" name="from" value=""  class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 text-right">To</label>
                    <div class="col-md-9">
                        <input type="text" name="to" value=""  class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 text-right">Subject</label>
                    <div class="col-md-9">
                        <input type="text" name="subject" value=""  class="form-control">
                    </div>
                </div>

                <h4>Template vars</h4>

                <div class="form-group row">
                    <label class="col-md-3 text-right">Name</label>
                    <div class="col-md-9">
                        <input type="text" name="vars[name]" value=""  class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 text-right">Text</label>
                    <div class="col-md-9">
                        <input type="text" name="vars[text]" value=""  class="form-control">
                    </div>
                </div>

                <input type="submit" value="Send" class="btn btn-success text-white float-end">

                </form>
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

