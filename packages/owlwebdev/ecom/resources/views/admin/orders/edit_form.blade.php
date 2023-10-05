<form method="POST" id="order_form" action="{{ route('orders.update', $order) }}">
    @csrf

    @method('put')

    <input type="hidden" name="user[id]" value="{{ $order->user->id ?? '' }}">
    <div class="row ml-3 mr-3">


        {{-- Customer info block --}}
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('User info') }}</h5>

                    <div class="form-group row">
                        <label for="userName"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Name') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="user[name]" id="userName"
                                class="form-control{{ $errors->has('user.name') ? ' is-invalid' : '' }}"
                                value="{{ old('user.name', $order->user->name ?? '') }}" disabled>

                            @if ($errors->has('user.name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user.name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="userLastname"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Lastname') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="user[lastname]" id="userName"
                                class="form-control{{ $errors->has('user.lastname') ? ' is-invalid' : '' }}"
                                value="{{ old('user.lastname', $order->user->lastname ?? '') }}" disabled>

                            @if ($errors->has('user.lastname'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user.lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="userEmail"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Email') }}</label>
                        <div class="col-sm-8">

                            <input type="email" name="user[email]" id="userEmail"
                                class="form-control{{ $errors->has('user.email') ? ' is-invalid' : '' }}"
                                value="{{ old('user.email', $order->user->email ?? '') }}" disabled>

                            @if ($errors->has('user.email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user.email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="userPhone"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Phone') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="user[phone]" id="userPhone"
                                class="form-control{{ $errors->has('user.phone') ? ' is-invalid' : '' }}"
                                value="{{ old('user.phone', $order->user->phone ?? '') }}" disabled>

                            @if ($errors->has('user.phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user.phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="form-group row"> --}}
                    {{-- <label for="userCountry" class="col-sm-4 text-right control-label col-form-label">{{ __('Country') }}</label> --}}
                    {{-- <div class="col-sm-8"> --}}
                    {{-- <input type="text" name="user[country]" id="userCountry" class="form-control{{ $errors->has('user.country') ? ' is-invalid' :'' }}" value="{{ old('user.country', $order->user->country ?? '') }}"> --}}

                    {{-- @if ($errors->has('user.country')) --}}
                    {{-- <span class="invalid-feedback" role="alert"> --}}
                    {{-- <strong>{{ $errors->first('user.country') }}</strong> --}}
                    {{-- </span> --}}
                    {{-- @endif --}}
                    {{-- </div> --}}
                    {{-- </div> --}}

                    {{-- <div class="form-group row">
                        <label for="userCity" class="col-sm-4 text-right control-label col-form-label">{{ __('City') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="user[city]" id="userCity" class="form-control{{ $errors->has('user.city') ? ' is-invalid' :'' }}" value="{{ old('user.city', $order->user->city ?? '') }}">

                            @if ($errors->has('user.city'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user.city') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div> --}}

                    {{-- <div class="form-group row"> --}}
                    {{-- <label for="userAddress" class="col-sm-4 text-right control-label col-form-label">{{ __('Address') }}</label> --}}
                    {{-- <div class="col-sm-8"> --}}
                    {{-- <input type="text" name="user[address]" id="userAddress" class="form-control{{ $errors->has('user.address') ? ' is-invalid' :'' }}" value="{{ old('user.address', $order->user->address ?? '') }}"> --}}

                    {{-- @if ($errors->has('user.address')) --}}
                    {{-- <span class="invalid-feedback" role="alert"> --}}
                    {{-- <strong>{{ $errors->first('user.address') }}</strong> --}}
                    {{-- </span> --}}
                    {{-- @endif --}}
                    {{-- </div> --}}
                    {{-- </div> --}}

                    {{-- <div class="form-group row"> --}}
                    {{-- <label for="userPostCode" class="col-sm-4 text-right control-label col-form-label">{{ __('Post Code') }}</label> --}}
                    {{-- <div class="col-sm-8"> --}}
                    {{-- <input type="text" name="user[postcode]" id="userPostCode" class="form-control{{ $errors->has('user.postcode') ? ' is-invalid' :'' }}" value="{{ old('user.postcode', $order->user->postcode ?? '') }}"> --}}

                    {{-- @if ($errors->has('user.postcode')) --}}
                    {{-- <span class="invalid-feedback" role="alert"> --}}
                    {{-- <strong>{{ $errors->first('user.postcode') }}</strong> --}}
                    {{-- </span> --}}
                    {{-- @endif --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
            </div>
        </div>
        {{-- End Customer info block --}}


        {{-- Shipping info --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Shipping info') }}</h5>

                    <div class="form-group row ">
                        <label for="shippingName"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Name') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_name" id="shippingName"
                                class="form-control{{ $errors->has('shipping_name') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_name', $order->shipping_name ?? ($order->user ? $order->user->name : '')) }}">

                            @if ($errors->has('shipping_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label for="shippingLastname"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Lastname') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_lastname" id="shippingName"
                                class="form-control{{ $errors->has('shipping_lastname') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_lastname', $order->shipping_lastname ?? ($order->user ? $order->user->lastname : '')) }}">

                            @if ($errors->has('shipping_lastname'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label for="shippingEmail"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Email') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_email" id="shippingEmail"
                                class="form-control{{ $errors->has('shipping_email') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_email', $order->shipping_email ?? ($order->user ? $order->user->email : '')) }}">

                            @if ($errors->has('shipping_email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label for="shippingPhone"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Phone') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_phone" id="shippingPhone"
                                class="form-control{{ $errors->has('shipping_phone') ? ' is-invalid' : '' }} phone-mask"
                                value="{{ old('shipping_phone', $order->shipping_phone ?? ($order->user ? $order->user->phone : '')) }}">

                            @if ($errors->has('shipping_phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingCity"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('City') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_city" id="shippingCity"
                                class="form-control{{ $errors->has('shipping_city') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_city', $order->shipping_city ?? '') }}">

                            @if ($errors->has('shipping_city'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_city') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingCity"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('City') }} ID</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_city_id" id="shippingCity"
                                class="form-control{{ $errors->has('shipping_city_id') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_city_id', $order->shipping_city_id ?? '') }}">

                            @if ($errors->has('shipping_city_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_city_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingAddress"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Address') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_address" id="shippingAddress"
                                class="form-control{{ $errors->has('shipping_address') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_address', $order->shipping_address ?? '') }}">

                            @if ($errors->has('shipping_address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingStreet"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Street') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_street" id="shippingAddress"
                                class="form-control{{ $errors->has('shipping_street') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_street', $order->shipping_street ?? '') }}">

                            @if ($errors->has('shipping_street'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_street') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingHouse"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('House') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_house" id="shippingAddress"
                                class="form-control{{ $errors->has('shipping_house') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_house', $order->shipping_house ?? '') }}">

                            @if ($errors->has('shipping_house'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_house') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingAddress"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Apartment') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_apartment" id="shippingAddress"
                                class="form-control{{ $errors->has('shipping_apartment') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_apartment', $order->shipping_apartment ?? '') }}">

                            @if ($errors->has('shipping_apartment'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_apartment') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingCountry"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Country') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_country" id="shippingCountry"
                                class="form-control{{ $errors->has('shipping_country') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_country', $order->shipping_country ?? '') }}">
                            @if ($errors->has('shipping_country'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_country') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingProvince"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Province') }}</label>
                        <div class="col-sm-8">

                            @if (isset($countries[$order->shipping_country]['provinces']))
                                <select class="form-control select2 form-control custom-select " id="shippingProvince"
                                    name="shipping_province">
                                    @foreach ($countries[$order->shipping_country]['provinces'] as $key => $province)
                                        <option value="{{ $key }}"
                                            @if (isset($order->shipping_province) && $order->shipping_province == $key) selected @endif>
                                            {{ $province }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="shipping_province"
                                    class="form-control{{ $errors->has('shipping_province') ? ' is-invalid' : '' }}"
                                    value="{{ old('shipping_province', $order->shipping_province ?? '') }}">
                            @endif

                            @if ($errors->has('shipping_province'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_province') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingPostCode"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Post Code') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_postcode" id="shippingPostCode"
                                class="form-control{{ $errors->has('shipping_postcode') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_postcode', $order->shipping_postcode ?? '') }}">

                            @if ($errors->has('shipping_postcode'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_postcode') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingBranch"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Branch') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_branch" id="shippingBranch"
                                class="form-control{{ $errors->has('shipping_branch') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_branch', $order->shipping_branch ?? '') }}">

                            @if ($errors->has('shipping_branch'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_branch') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingBranch"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Branch') }} ID</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping_branch_id" id="shippingBranch"
                                class="form-control{{ $errors->has('shipping_branch_id') ? ' is-invalid' : '' }}"
                                value="{{ old('shipping_branch_id', $order->shipping_branch_id ?? '') }}">

                            @if ($errors->has('shipping_branch_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('shipping_branch_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- End Shipping info --}}

        {{-- Billing info --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Billing info') }}</h5>

                    <div class="form-group row ">
                        <label for="billingName"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Name') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_name" id="billingName"
                                class="form-control{{ $errors->has('billing_name') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_name', $order->billing_name ?? ($order->user ? $order->user->name : '')) }}">

                            @if ($errors->has('billing_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label for="billingLastname"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Lastname') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_lastname" id="billingLastname"
                                class="form-control{{ $errors->has('billing_lastname') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_lastname', $order->billing_lastname ?? ($order->user ? $order->user->lastname : '')) }}">

                            @if ($errors->has('billing_lastname'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label for="billingEmail"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Email') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_email" id="billingEmail"
                                class="form-control{{ $errors->has('billing_email') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_email', $order->billing_email ?? ($order->user ? $order->user->email : '')) }}">

                            @if ($errors->has('billing_email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label for="billingPhone"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Phone') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_phone" id="billingPhone"
                                class="form-control{{ $errors->has('billing_phone') ? ' is-invalid' : '' }} phone-mask"
                                value="{{ old('billing_phone', $order->billing_phone ?? ($order->user ? $order->user->phone : '')) }}">

                            @if ($errors->has('billing_phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="billingCity"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('City') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_city" id="billingCity"
                                class="form-control{{ $errors->has('billing_city') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_city', $order->billing_city ?? '') }}">

                            @if ($errors->has('billing_city'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_city') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="billingAddress"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Address') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_address" id="billingAddress"
                                class="form-control{{ $errors->has('billing_address') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_address', $order->billing_address ?? '') }}">

                            @if ($errors->has('billing_address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="billingAddress"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Apartment') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_apartment" id="billingAddress"
                                class="form-control{{ $errors->has('billing_apartment') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_apartment', $order->billing_apartment ?? '') }}">

                            @if ($errors->has('billing_apartment'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_apartment') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="billingCountry"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Country') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_country" id="billingCountry"
                                class="form-control{{ $errors->has('billing_country') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_country', $order->billing_country ?? '') }}">
                            @if ($errors->has('billing_country'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_country') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="billingProvince"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Province') }}</label>
                        <div class="col-sm-8">

                            @if (isset($countries[$order->billing_country]['provinces']))
                                <select class="form-control select2 form-control custom-select " id="billingProvince"
                                    name="billing_province">
                                    @foreach ($countries[$order->billing_country]['provinces'] as $key => $province)
                                        <option value="{{ $key }}"
                                            @if (isset($order->billing_province) && $order->billing_province == $key) selected @endif>
                                            {{ $province }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="billing_province"
                                    class="form-control{{ $errors->has('billing_province') ? ' is-invalid' : '' }}"
                                    value="{{ old('billing_province', $order->billing_province ?? '') }}">
                            @endif

                            @if ($errors->has('billing_province'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_province') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="billingPostCode"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Post Code') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="billing_postcode" id="billingPostCode"
                                class="form-control{{ $errors->has('billing_postcode') ? ' is-invalid' : '' }}"
                                value="{{ old('billing_postcode', $order->billing_postcode ?? '') }}">

                            @if ($errors->has('billing_postcode'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('billing_postcode') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- End Billing info --}}


        {{-- Other info --}}
        <div class="col-md-4">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Other info') }}</h5>

                    <div class="form-group row">
                        <label
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Shipping method') }}</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 form-control custom-select" name="shipping_method">
                                @foreach ($shipping_methods as $key => $ship_m)
                                    <option value="{{ $key }}"
                                        @if (isset($order->shipping_method) && $order->shipping_method == $key) selected @endif>
                                        {{ $ship_m['name'] ?: $ship_m['code'] }}</option>
                                @endforeach
                            </select>
                            {{-- @if (isset($order->shipping_method) && isset($shipping_methods[$order->shipping_method]))
                                <input type="text" name="shipping_method_name" class="form-control"
                                    value="{{ $shipping_methods[$order->shipping_method]['name'] }}" readonly>
                                <input type="hidden" name="shipping_method" class="form-control"
                                    value="{{ $order->shipping_method }}" readonly>
                            @else
                                <input type="text" name="shipping_method" class="form-control"
                                    value="{{ $order->shipping_method }}" readonly>
                            @endif --}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Payment method') }}</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 form-control custom-select " name="payment_method">
                                @foreach ($payment_methods as $key => $pay_m)
                                    <option value="{{ $key }}"
                                        @if (isset($order->payment_method) && $order->payment_method == $key) selected @endif>{{ $pay_m['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- @if (isset($order->payment_method) && isset($payment_methods[$order->payment_method]))
                                <input type="text" name="payment_method_name" class="form-control"
                                    value="{{ $payment_methods[$order->payment_method]['name'] }}" readonly>
                                <input type="hidden" name="payment_method" class="form-control"
                                    value="{{ $order->payment_method }}" readonly>
                            @else
                                <input type="text" name="payment_method" class="form-control"
                                    value="{{ $order->payment_method }}" readonly>
                            @endif --}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Order status') }}</label>
                        <div class="col-sm-8">
                            <select id="status_select" class="form-control select2 form-control custom-select"
                                name="order_status_id">
                                @foreach ($statuses as $key => $status)
                                    {{-- if status droped allow only Wait status --}}
                                    @if (isset($order->order_status_id) && $order->order_status_id == 1 && !in_array($key, [1, 2, 3]))
                                        {{-- Dropped -> Wait or Cancel --}}
                                        @continue
                                    @endif
                                    {{-- if status canceled allow only Wait status --}}
                                    @if (isset($order->order_status_id) && $order->order_status_id == 3 && !in_array($key, [2, 3]))
                                        {{-- Canceled -> Wait only --}}
                                        @continue
                                    @endif

                                    {{-- hide Droped status --}}
                                    @if (in_array($key, [1]))
                                        @continue
                                    @endif

                                    <option value="{{ $key }}"
                                        @if (isset($order->order_status_id) && $order->order_status_id == $key) selected @endif>{{ __($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 text-right control-label col-form-label">{{ __('Coupon') }}</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 form-control custom-select " name="coupon_id">
                                <option value="">---</option>
                                @foreach ($coupons as $coupon)
                                    <option value="{{ $coupon->id }}"
                                        @if ($order->coupon_id == $coupon->id) selected @endif>
                                        {{ $coupon->translations->first()->name ?? '' }}
                                        ({{ $coupon->value . ' ' . $coupon->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 text-right control-label col-form-label">{{ __('Discount') }}</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 form-control custom-select " name="discount_id">
                                <option value="">---</option>
                                @foreach ($discounts as $discount)
                                    <option value="{{ $discount->id }}"
                                        @if ($order->discount_id == $discount->id) selected @endif>
                                        {{ $discount->translations->first()->name ?? '' }}
                                        (-{{ $discount->percentage }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingPostCode"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Subtotal price') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="subtotal" class="form-control"
                                value="{{ $order->subtotal ?? 0 }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingPostCode"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Shipping price') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="shipping" id="shippingPostCode" class="form-control"
                                value="{{ $order->shipping ?? 0 }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shippingPostCode"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Total price') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="total" id="shippingPostCode" class="form-control"
                                value="{{ $order->total ?? 0 }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="trackingCode"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Tracking id') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="tracking" id="tracking" class="form-control"
                                value="{{ $order->tracking ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="comment"
                            class="col-sm-4 text-right control-label col-form-label">{{ __('Comment') }}</label>
                        <div class="col-sm-8">
                            <textarea name="comment" id="comment" class="form-control">{{ $order->comment ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Other info --}}

    </div>
    <div class="col-sm-12">
        <div class="border-bottom">
            <div class="card-body">
                <button type="submit" name="update_order" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</form>
@if (!empty($order->utm_data))
    <?php
    $utm_data = json_decode($order->utm_data, true);
    ?>
    <div class="row ml-3 mr-3">
        <div class="col-sm-12">
            <h6>{{ __('UTM') }}</h6>
            <table id="ordersTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>utm_source</th>
                        <th>utm_medium</th>
                        <th>utm_campaign</th>
                        <th>start visit</th>
                        <th>time</th>
                    </tr>
                </thead>
                <tr>
                    <td>{{ $utm_data['utm']['utm_source'] ?? '' }}</td>
                    <td>{{ $utm_data['utm']['utm_medium'] ?? '' }}</td>
                    <td>{{ $utm_data['utm']['utm_campaign'] ?? '' }}</td>
                    <td>{{ $utm_data['created_at'] ?? '' }}</td>
                    <td>{{ $utm_data['time'] ?? '' }}</td>
                </tr>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
@endif


@push('scripts')
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script>
        $(function() {
            $(".phone-mask").inputmask("+3809999999999", {
                "placeholder": ""
            });

            const track = $('#tracking').val();

            $(document).on('submit', '#order_form', function(e) {
                e.preventDefault();

                if ($('#tracking').val() != track && $('#status_select').val() !=
                    '9') { // 9 = "Shipped" status
                    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
                    Swal.fire({
                        title: 'Змінити статус замовлення на {{ __('In Progress') }}?', // 9 = "Shipped" status
                        //html: "<span>" + message + "</span>",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonText: '{{ __('No') }}',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __('Yes') }}'
                    }).then(function(result) {
                        if (result.value) {
                            $('#status_select').val(9).trigger('change'); // 9 = "Shipped" status
                        }
                        $('#order_form')[0].submit();
                    });
                } else {
                    $('#order_form')[0].submit();
                }

            });

        });
    </script>
@endpush
