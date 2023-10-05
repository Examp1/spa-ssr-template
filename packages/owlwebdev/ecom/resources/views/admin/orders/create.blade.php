@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">{{ __('Orders') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('New order') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="mt-3">
                    <form class="form-horizontal" method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <div class="row ml-3 mr-3">


                            {{--Customer info block--}}
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ __('Choose User or will be created new') }}</h5>

                                        <div class="form-group row">
                                            <label for="userEmail" class="col-sm-4 text-right control-label col-form-label">{{ __('User') }}</label>
                                            <div class="col-sm-8">

                                                <select name="user_id" id="userEmail" class="select2 form-control custom-select ">
                                                    <option value="">Створити нового</option>
                                                    @foreach($users as $one_user)
                                                        <option value="{{ $one_user->id }}" data-link="{{ url('/admin/orders/create?u='. $one_user->id ) }}" {{ ($user && $one_user->id == $user->id) ? 'selected' : '' }}>{{ $one_user->name }}({{ $one_user->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @if($user)
                                            <div class="form-group row">
                                                <label for="userName" class="col-sm-4 text-right control-label col-form-label">{{ __('Name') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="user[name]" id="userName" class="form-control{{ $errors->has('user.name') ? ' is-invalid' :'' }}" value="{{ old('user.name', $user->name ?? '') }}" disabled>

                                                    @if ($errors->has('user.name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('user.name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="userEmail" class="col-sm-4 text-right control-label col-form-label">{{ __('Email') }}</label>
                                                <div class="col-sm-8">

                                                    <input type="email" name="user[email]" class="form-control{{ $errors->has('user.email') ? ' is-invalid' :'' }}" value="{{ old('user.email', $user->email ?? '') }}" disabled>

                                                    @if ($errors->has('user.email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('user.email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="userPhone" class="col-sm-4 text-right control-label col-form-label">{{ __('Phone') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="user[phone]" id="userPhone" class="form-control{{ $errors->has('user.phone') ? ' is-invalid' :'' }}" value="{{ old('user.phone', $user->phone ?? '') }}" disabled>

                                                    @if ($errors->has('user.phone'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('user.phone') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{--End Customer info block--}}


                            {{--Shipping info--}}
                            <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('Shipping info') }}</h5>

                                            <div class="form-group row ">
                                                <label for="shippingName" class="col-sm-4 text-right control-label col-form-label">{{ __('Name') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" required name="shipping_name" id="shippingName" class="form-control{{ $errors->has('shipping_name') ? ' is-invalid' :'' }}" value="{{ old('shipping_name', ($user ? $user->name : '')) }}">

                                                    @if ($errors->has('shipping_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('shipping_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row ">
                                                <label for="shippingEmail" class="col-sm-4 text-right control-label col-form-label">{{ __('Email') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="email" required name="shipping_email" id="shippingEmail" class="form-control{{ $errors->has('shipping_email') ? ' is-invalid' :'' }}" value="{{ old('shipping_email', ($user ? $user->email : '')) }}">

                                                    @if ($errors->has('shipping_email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('shipping_email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row ">
                                                <label for="shippingPhone" class="col-sm-4 text-right control-label col-form-label">{{ __('Phone') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="tel" required class="phone-mask form-control" value="{{ old('shipping_phone', ($user ? $user->phone : '')) }}">
                                                    <input type="hidden" name="shipping_phone" value="{{ old('shipping_phone', ($user ? $user->phone : '')) }}">
                                                    @if ($errors->has('shipping_phone'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('shipping_phone') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="shippingCity" class="col-sm-4 text-right control-label col-form-label">NP {{ __('City') }}</label>
                                                <div class="col-sm-8">
                                                    {{--<select name="shipping_city" id="shippingCity">
                                                        @if($user && $user->city && $user->apartment)
                                                            <option checked="checked" data-ref="{{ $user->apartment }}">{{ $user->city }}</option>
                                                        @endif
                                                    </select>
                                                    --}}

                                                    <input type="text" name="shipping_city" id="shippingCity" class="form-control{{ $errors->has('shipping_city') ? ' is-invalid' :'' }}" value="{{ old('shipping_city', ($user ? $user->city : '')) }}">


                                                    @if ($errors->has('shipping_city'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('shipping_city') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="shippingAddress" class="col-sm-4 text-right control-label col-form-label">NP {{ __('Address') }}</label>
                                                <div class="col-sm-8">
                                                    {{--<select name="shipping_address" id="shippingAddress">
                                                        @if($user && $user->address)
                                                            <option checked="checked">{{ $user->address }}</option>
                                                        @endif
                                                    </select>
                                                    --}}
                                                    <input type="text" name="shipping_address" id="shippingAddress" class="form-control{{ $errors->has('shipping_address') ? ' is-invalid' :'' }}" value="{{ old('shipping_address', ($user ? $user->address : '')) }}">

                                                    @if ($errors->has('shipping_address'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('shipping_address') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <input type="hidden" name="shipping_apartment" id="shippingApartment" value="{{ old('shipping_apartment', ($user ? $user->apartment : '')) }}">
                                        </div>
                                    </div>
                            </div>
                            {{--End Shipping info--}}


                            {{--Other info--}}
                            <div class="col-md-4">

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('Other info') }}</h5>

                                            <div class="form-group row">
                                                <label class="col-sm-4 text-right control-label col-form-label">{{ __('Shipping method') }}</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2 form-control custom-select " name="shipping_method">
                                                        @foreach($shipping_methods as $key => $ship_m)
                                                            <option value="{{ $key }}" @if(null !== old('shipping_method') && old('shipping_method') == $key) selected @endif>{{ $ship_m['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 text-right control-label col-form-label">{{ __('Payment method') }}</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2 form-control custom-select " name="payment_method">
                                                        @foreach($payment_methods as $key => $pay_m)
                                                            <option value="{{ $key }}" @if(null !== old('payment_method') && old('payment_method') == $key) selected @endif>{{ $pay_m['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 text-right control-label col-form-label">{{ __('Order status') }}</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2 form-control custom-select " name="order_status_id">
                                                        @foreach($order_statuses as $key => $status)
                                                            @if(!in_array($key, [1,7]))
                                                                <option value="{{ $key }}" @if(null !== old('order_status_id') && old('order_status_id') == $key) selected @endif>{{ __($status) }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 text-right control-label col-form-label">{{ __('Coupon') }}</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2 form-control custom-select " name="coupon_id">
                                                        <option value="">---</option>
                                                        @foreach($coupons as $coupon)
                                                            <option value="{{ $coupon->id }}" @if(null !== old('coupon_id') && old('coupon_id') == $coupon->id) selected @endif>{{ $coupon->translations->first()->name ?? '' }} ({{ $coupon->value . ' ' . $coupon->type }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="comment" class="col-sm-4 text-right control-label col-form-label">{{ __('Comment') }}</label>
                                                <div class="col-sm-8">
                                                    <textarea name="comment" id="comment" class="form-control">{{ (null !== old('comment') && old('comment')) ? old('comment') : '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            {{--End Other info--}}

                        </div>
                        <div class="col-sm-12">
                        <div class="border-bottom">
                            <div class="card-body">
                                <button type="submit" name="update_order" class="btn btn-primary">{{ __('Create') }}</button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script>
        $('#userEmail').on('select2:select', function (e) {
            window.location.href = $('#userEmail').find(":selected").data('link');
        });

        $(".phone-mask").inputmask("+3809999999999", {"placeholder": ""});
    </script>
@endpush
