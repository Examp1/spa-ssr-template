@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">{{ __('Orders') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit order') }} #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="mt-3">
                    @include('ecom::admin.orders.edit_form')
                    @include('ecom::admin.orders.edit_form_products')
                    @include('ecom::admin.orders.history')
                </div>
            </div>
        </div>
    </div>
@endsection
