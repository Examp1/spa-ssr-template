<input type="hidden" name="_tab" value="{{ $tab }}">
<?php

$defaultLang = config('translatable.locale');
$checkout_shipping = \Owlwebdev\Ecom\Models\Cart::SHIPPING_METHODS;
$formated_shipping = \Owlwebdev\Ecom\Models\Cart::getShippingMethods();
$checkout_payment = \Owlwebdev\Ecom\Models\Cart::PAYMENT_METHODS;
$formated_payment = \Owlwebdev\Ecom\Models\Cart::getPaymentMethods();
$fields = \Owlwebdev\Ecom\Models\Cart::SHIPPING_FIELDS;

$formated_currencies = \Owlwebdev\Ecom\Models\Cart::getCurrencies();

foreach ($localizations as $lang => $lang_name) {
    $checkout_data[$lang] = isset($data[$lang]['checkout']) ? json_decode($data[$lang]['checkout'][0]['value'], true) : [];
}

?>

<h4>Курс валют</h4>

@foreach ($formated_currencies as $code => $currency_data)
    <div class="row mb-3">
        @foreach ($currency_data as $field => $field_data)
            @switch($field)
                @case('status')
                    <div class="col-lg-2 material-switch md-switch">
                        <span class="tolltip" data-text="{{ __($field) }} {{ $code }}"><i class="fa fa-question-circle"
                                aria-hidden="true"></i></span>
                        <span>{{ __($field) }}</span>
                        <input type="hidden" value="0"
                            name="setting_data[{{ $defaultLang }}][checkout][currencies][{{ $code }}][{{ $field }}]">
                        <input value="1" type="checkbox"
                            name="setting_data[{{ $defaultLang }}][checkout][currencies][{{ $code }}][{{ $field }}]"
                            id="{{ $code }}Switch{{ $field }}"
                            {{ $checkout_data[$defaultLang]['currencies'][$code][$field] ?? $field_data ? ' checked' : '' }} />
                        <label for="{{ $code }}Switch{{ $field }}" class="label-success"></label>
                    </div>
                @break

                @default
                    <div class="col-lg-2">
                        <input type="text"
                            name="setting_data[{{ $defaultLang }}][checkout][currencies][{{ $code }}][{{ $field }}]"
                            value="{{ $checkout_data[$defaultLang]['currencies'][$code][$field] ?? $field_data }}"
                            class="form-control" placeholder="{{ __($field) }}">
                    </div>
                @break
            @endswitch
        @endforeach
    </div>
@endforeach

<hr>

<h4>Список міст(великих, або які часто використовуються)</h4>

<div id="cities_container" class="form-group">

    <input type="hidden" name="setting_data[{{ $defaultLang }}][checkout][cities]" value="">

    <select class="select2-field" style="width: 100%">
    </select>

    <div class="items-container w-100 mt-1">
        {{-- @dd($checkout_data[$defaultLang]) --}}
        @if (isset($checkout_data[$defaultLang]['cities']) && !empty($checkout_data[$defaultLang]['cities']))
            @foreach ($checkout_data[$defaultLang]['cities'] as $id => $city_data)
                <div data-item-id="{{ $id }}"
                    class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">

                    @foreach ($localizations as $lang => $lang_name)
                        <div class="col-md-5 mb-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><img src="/images/langs/{{ $lang }}.jpg"
                                            style="width: 20px" alt="{{ $lang }}"></span>
                                </div>
                                <input type="text"
                                    name="setting_data[{{ $lang }}][checkout][cities][{{ $id }}][city_name]"
                                    value="{{ old('setting_data.' . $lang . '.checkout.cities.' . $id . '.city_name', (!empty($checkout_data[$lang]) && array_key_exists('city_name', $checkout_data[$lang]['cities'][$id]) ? $checkout_data[$lang]['cities'][$id]['city_name'] : $field_data['city_name'])) }}"
                                    class="form-control imput_city_name">
                            </div>
                        </div>
                        <div class="col-md-5 mb-1">
                            <div class="input-group">
                                <input type="text"
                                    name="setting_data[{{ $lang }}][checkout][cities][{{ $id }}][city_id]"
                                    value="{{ old('setting_data.' . $lang . '.checkout.cities.' . $id . '.city_id', (!empty($checkout_data[$lang]) && array_key_exists('city_id', $checkout_data[$lang]['cities'][$id]) ? $checkout_data[$lang]['cities'][$id]['city_id'] : $field_data['city_id'])) }}"
                                    class="form-control imput_city_id">
                            </div>
                        </div>
                    @endforeach

                    <div class="col-2">
                        <button type="button"
                            class="btn btn-danger remove-item float-right text-white">{{ __('Remove') }}</button>
                    </div>
                </div>
            @endforeach
        @endif

    </div>

    <template id="cities_template">
        <div data-item-id="#dynamicPlaceholder"
            class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">

            @foreach ($localizations as $lang => $lang_name)
                <div class="col-5">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><img src="/images/langs/{{ $lang }}.jpg"
                                    style="width: 20px" alt="{{ $lang }}"></span>
                        </div>
                        <input type="text" disabled
                            name="setting_data[{{ $lang }}][checkout][cities][#dynamicPlaceholder][city_name]"
                            value="" class="form-control imput_city_name">
                    </div>
                </div>
                <div class="col-5">
                    <div class="input-group">
                        <input type="text" disabled
                            name="setting_data[{{ $lang }}][checkout][cities][#dynamicPlaceholder][city_id]"
                            value="" class="form-control imput_city_id">
                    </div>
                </div>
            @endforeach

            <div class="col-2">
                <button type="button"
                    class="btn btn-danger remove-item float-right text-white">{{ __('Remove') }}</button>
            </div>
        </div>
    </template>
</div>

<hr>

<h4>Способи доставки і оплати</h4>

<div class="row">
    <div class="col-md-6 shipping-container">
        @foreach ($checkout_shipping as $code => $shipping_data)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $shipping_data['name'] ?? '' }}</h5>
                    <div class="form-group row">
                        @foreach ($shipping_data as $field => $field_data)
                            @if ($field == 'code')
                                <input type="hidden"
                                    name="setting_data[{{ $lang }}][checkout][shipping][{{ $code }}][{{ $field }}]"
                                    value="{{ $checkout_data[$lang]['shipping'][$code][$field] ?? $field_data }}">
                                @continue
                            @endif
                            <label class="col-md-3 m-t-15">{{ __($field) }}</label>
                            <div class="col-md-9 mb-2">
                                @foreach ($localizations as $lang => $lang_name)
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><img
                                                    src="/images/langs/{{ $lang }}.jpg" style="width: 20px"
                                                    alt="{{ $lang }}"></span>
                                        </div>

                                        @switch($field)
                                            @case('description')
                                                <textarea name="setting_data[{{ $lang }}][checkout][shipping][{{ $code }}][{{ $field }}]"
                                                    id="checkout_shipping_{{ $code }}_{{ $field }}_{{ $lang }}"
                                                    class="form-control{{ $errors->has('setting_data.' . $lang . '.checkout_shipping_' . $code . '_' . $field) ? ' is-invalid' : '' }}">{{ old('setting_data.' . $lang . '.checkout.shipping.' . $code . '.' . $field, !empty($checkout_data[$lang]) && isset($checkout_data[$lang]['shipping'][$code]) && array_key_exists($field, $checkout_data[$lang]['shipping'][$code]) ? $checkout_data[$lang]['shipping'][$code][$field] : $field_data) }}</textarea>
                                            @break

                                            @default
                                                <input type="text" {{ $field == 'code' ? 'disabled' : '' }}
                                                    name="setting_data[{{ $lang }}][checkout][shipping][{{ $code }}][{{ $field }}]"
                                                    value="{{ old('setting_data.' . $lang . '.checkout.shipping.' . $code . '.' . $field, !empty($checkout_data[$lang]) && isset($checkout_data[$lang]['shipping'][$code]) && array_key_exists($field, $checkout_data[$lang]['shipping'][$code]) ? $checkout_data[$lang]['shipping'][$code][$field] : $field_data) }}"
                                                    id="checkout_shipping_{{ $code }}_{{ $field }}_{{ $lang }}"
                                                    class="form-control{{ $errors->has('setting_data.' . $lang . '.checkout_shipping_' . $code . '_' . $field) ? ' is-invalid' : '' }}">
                                            @break
                                        @endswitch

                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Icon --}}
                        <div class="form-group row">
                            <label class="col-md-3">{{ __('Icon') }}</label>
                            <div class="col-md-9">
                                {{ media_preview_box('setting_data[' . $defaultLang . '][checkout][shipping][' . $code . '][icon]', old('setting_data.' . $defaultLang . '.checkout.shipping.' . $code . '.icon', $checkout_data[$defaultLang]['shipping'][$code]['icon'] ?? '')) }}
                            </div>
                        </div>

                        {{-- Payments --}}
                        <input type="hidden"
                            name="setting_data[{{ $defaultLang }}][checkout][shipping][{{ $code }}][payments]"
                            value="">
                        <div class="form-group row">
                            <label class="col-md-3">{{ __('Avalible Payment methods') }}</label>
                            <div class="col-md-9">
                                @foreach ($formated_payment as $pay_code => $payment_data)
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox"
                                            name="setting_data[{{ $defaultLang }}][checkout][shipping][{{ $code }}][payments][]"
                                            value="{{ $pay_code }}"
                                            {{ isset($checkout_data[$defaultLang]['shipping'][$code]['payments']) && in_array($pay_code, $checkout_data[$defaultLang]['shipping'][$code]['payments']) ? 'checked' : '' }}
                                            class="custom-control-input"
                                            id="pay_status_for_{{ $pay_code }}_on_{{ $code }}">
                                        <label class="custom-control-label"
                                            for="pay_status_for_{{ $pay_code }}_on_{{ $code }}">{{ $payment_data['name'] }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- fields --}}
                <h5 class="card-title text-center mt-2">{{ __('Fields') }}</h5>
                <input type="hidden"
                    name="setting_data[{{ $defaultLang }}][checkout][shipping][{{ $code }}][custom_fields]"
                    value="">
                <div class="border-top">
                    <div class="card-body">
                        <div class="container">
                            @if (isset($checkout_data[$defaultLang]['shipping'][$code]['custom_fields']) &&
                                    !empty($checkout_data[$defaultLang]['shipping'][$code]['custom_fields']))
                                @foreach ($checkout_data[$defaultLang]['shipping'][$code]['custom_fields'] as $custom_field_code => $custom_field)
                                    <div class="form-group row border-bottom"
                                        data-item-id="{{ $custom_field_code }}">
                                        <label class="col-md-3 mb-2">{{ __('Type') }}</label>
                                        <div class="col-md-9 mb-2">
                                            <select
                                                name="setting_data[{{ $defaultLang }}][checkout][shipping][{{ $code }}][custom_fields][{{ $custom_field_code }}][type]"
                                                class="select2">
                                                <option value="input"
                                                    {{ isset($custom_field['type']) && $custom_field['type'] == 'input' ? 'selected' : '' }}>
                                                    {{ __('Text input') }}</option>
                                                {{-- <option value="copy" {{ (isset($custom_field['type']) && $custom_field['type'] == 'copy')  ? 'selected' : '' }}>{{ __('Info with copy button') }}</option> --}}
                                                <option value="nova_city"
                                                    {{ isset($custom_field['type']) && $custom_field['type'] == 'nova_city' ? 'selected' : '' }}>
                                                    {{ __('NP city') }}</option>
                                                <option value="nova_branch"
                                                    {{ isset($custom_field['type']) && $custom_field['type'] == 'nova_branch' ? 'selected' : '' }}>
                                                    {{ __('NP branch') }}</option>
                                            </select>
                                        </div>
                                        <label class="col-md-3 mb-2">{{ __('Save to') }}</label>
                                        <div class="col-md-9 mb-2">
                                            <select
                                                name="setting_data[{{ $defaultLang }}][checkout][shipping][{{ $code }}][custom_fields][{{ $custom_field_code }}][save_as]"
                                                class="select2">
                                                @foreach ($fields as $f)
                                                    <option value="{{ $f }}"
                                                        {{ isset($custom_field['save_as']) && $custom_field['save_as'] == $f ? 'selected' : '' }}>
                                                        {{ __($f) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-md-3 mb-2">{{ __('name') }}</label>
                                        <div class="col-md-9 mb-2">
                                            @foreach ($localizations as $lang => $lang_name)
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><img
                                                                src="/images/langs/{{ $lang }}.jpg"
                                                                style="width: 20px" alt="{{ $lang }}"></span>
                                                    </div>
                                                    <input type="text"
                                                        name="setting_data[{{ $lang }}][checkout][shipping][{{ $code }}][custom_fields][{{ $custom_field_code }}][name]"
                                                        value="{{ old('setting_data.' . $lang . '.checkout.shipping.' . $code . '.custom_fields.' . $custom_field_code . '.name', $checkout_data[$lang]['shipping'][$code]['custom_fields'][$custom_field_code]['name'] ?? '') }}"
                                                        class="form-control">
                                                </div>
                                            @endforeach
                                        </div>
                                        <label class="col-md-3 mb-2">{{ __('Text') }}</label>
                                        <div class="col-md-9 mb-2">
                                            @foreach ($localizations as $lang => $lang_name)
                                                <div class="input-group  mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><img
                                                                src="/images/langs/{{ $lang }}.jpg"
                                                                style="width: 20px" alt="{{ $lang }}"></span>
                                                    </div>
                                                    <input type="text"
                                                        name="setting_data[{{ $lang }}][checkout][shipping][{{ $code }}][custom_fields][{{ $custom_field_code }}][text]"
                                                        value="{{ old('setting_data.' . $lang . '.checkout.shipping.' . $code . '.custom_fields.' . $custom_field_code . '.text', $checkout_data[$lang]['shipping'][$code]['custom_fields'][$custom_field_code]['text'] ?? '') }}"
                                                        class="form-control">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="col-md-12 btn btn-danger remove-field-element">
                                            <i class="mdi mdi-delete"></i>
                                            {{ __('Remove') }}
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <template>
                            <div class="form-group row" data-item-id="#dynamicPlaceholder">
                                <label class="col-md-3 mb-2">{{ __('Type') }}</label>
                                <div class="col-md-9 mb-2">
                                    <select
                                        name="setting_data[{{ $defaultLang }}][checkout][shipping][{{ $code }}][custom_fields][#dynamicPlaceholder][type]"
                                        class="select2">
                                        <option value="input">{{ __('Text input') }}</option>
                                        {{-- <option value="copy">{{ __('Info with copy button') }}</option> --}}
                                        <option value="nova_city">{{ __('NP city') }}</option>
                                        <option value="nova_branch">{{ __('NP branch') }}</option>
                                    </select>
                                </div>
                                <label class="col-md-3 mb-2">{{ __('Save to') }}</label>
                                <div class="col-md-9 mb-2">
                                    <select
                                        name="setting_data[{{ $defaultLang }}][checkout][shipping][{{ $code }}][custom_fields][#dynamicPlaceholder][save_as]"
                                        class="select2">
                                        @foreach ($fields as $f)
                                            <option value="{{ $f }}">{{ __($f) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-3 mb-2">{{ __('name') }}</label>
                                <div class="col-md-9 mb-2">
                                    @foreach ($localizations as $lang => $lang_name)
                                        <div class="input-group  mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="/images/langs/{{ $lang }}.jpg"
                                                        style="width: 20px" alt="{{ $lang }}"></span>
                                            </div>
                                            <input type="text"
                                                name="setting_data[{{ $lang }}][checkout][shipping][{{ $code }}][custom_fields][#dynamicPlaceholder][name]"
                                                value="" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                                <label class="col-md-3 mb-2">{{ __('Text') }}</label>
                                <div class="col-md-9 mb-2">
                                    @foreach ($localizations as $lang => $lang_name)
                                        <div class="input-group  mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="/images/langs/{{ $lang }}.jpg"
                                                        style="width: 20px" alt="{{ $lang }}"></span>
                                            </div>
                                            <input type="text"
                                                name="setting_data[{{ $lang }}][checkout][shipping][{{ $code }}][custom_fields][#dynamicPlaceholder][text]"
                                                value="" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="col-md-12 btn btn-danger remove-field-element">
                                    <i class="mdi mdi-delete"></i>
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </template>
                        <button type="button" class="btn btn-success add-field-element">
                            <i class="mdi mdi-plus"></i>
                            {{ __('Add') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-md-6 payment-container">
        @foreach ($checkout_payment as $code => $payment_data)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $payment_data['name'] ?? '' }}</h5>
                    <div class="form-group row">
                        @foreach ($payment_data as $field => $field_data)
                            {{-- Image --}}
                            @if ($field == 'icon')
                                <label class="col-md-3">{{ __('Icon') }}</label>
                                <div class="col-md-9">
                                    {{ media_preview_box('setting_data[' . $defaultLang . '][checkout][payment][' . $code . '][icon]', old('setting_data.' . $defaultLang . '.checkout.payment.' . $code . '.icon', $checkout_data[$defaultLang]['payment'][$code]['icon'] ?? '')) }}
                                </div>
                                @continue
                            @endif

                            {{-- Code --}}
                            @if ($field == 'code')
                                <input type="hidden"
                                    name="setting_data[{{ $lang }}][checkout][payment][{{ $code }}][{{ $field }}]"
                                    value="{{ $checkout_data[$lang]['payment'][$code][$field] ?? $field_data }}">
                                @continue
                            @endif

                            {{-- Fields --}}
                            <label class="col-md-3 m-t-15">{{ __($field) }}</label>
                            <div class="col-md-9 mb-2">
                                @foreach ($localizations as $lang => $lang_name)
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><img
                                                    src="/images/langs/{{ $lang }}.jpg" style="width: 20px"
                                                    alt="{{ $lang }}"></span>
                                        </div>
                                        @switch($field)
                                            @case('description')
                                                <textarea name="setting_data[{{ $lang }}][checkout][payment][{{ $code }}][{{ $field }}]"
                                                    id="checkout_payment_{{ $code }}_{{ $field }}_{{ $lang }}"
                                                    class="form-control{{ $errors->has('setting_data.' . $lang . '.checkout_payment_' . $code . '_' . $field) ? ' is-invalid' : '' }}">{{ old('setting_data.' . $lang . '.checkout.payment.' . $code . '.' . $field, !empty($checkout_data[$lang]) && array_key_exists($code, $checkout_data[$lang]['payment']) && array_key_exists($field, $checkout_data[$lang]['payment'][$code]) ? $checkout_data[$lang]['payment'][$code][$field] : $field_data) }}</textarea>
                                            @break

                                            @default
                                                <input type="text"
                                                    name="setting_data[{{ $lang }}][checkout][payment][{{ $code }}][{{ $field }}]"
                                                    value="{{ old('setting_data.' . $lang . '.checkout.payment.' . $code . '.' . $field, $checkout_data[$lang]['payment'][$code][$field] ?? $field_data) }}"
                                                    id="checkout_payment_{{ $code }}_{{ $field }}_{{ $lang }}"
                                                    class="form-control{{ $errors->has('setting_data.' . $lang . '.checkout_payment_' . $code . '_' . $field) ? ' is-invalid' : '' }}">
                                            @break
                                        @endswitch
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Shippings --}}
                        {{-- <input type="hidden"
                        name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][shippings]"
                        value="">
                    <div class="form-group row">
                        <label class="col-md-3">{{ __('Avalible shipping methods') }}</label>
                        <div class="col-md-9">
                            @foreach ($formated_shipping as $pay_code => $shipping_data)
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox"
                                        name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][shippings][]"
                                        value="{{ $pay_code }}"
                                        {{  (isset($checkout_data[$defaultLang]['payment'][$code]['shippings']) && in_array($pay_code, $checkout_data[$defaultLang]['payment'][$code]['shippings'])) ? 'checked' : '' }}
                                        class="custom-control-input"
                                        id="pay_status_for_{{ $pay_code }}_on_{{ $code }}">
                                    <label class="custom-control-label" for="pay_status_for_{{ $pay_code }}_on_{{ $code }}">{{ $shipping_data['name'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}
                    </div>
                </div>
                {{-- fields --}}
                <h5 class="card-title text-center mt-2">{{ __('Fields') }}</h5>
                <input type="hidden"
                    name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][custom_fields]"
                    value="">
                <div class="border-top">
                    <div class="card-body">
                        <div class="container">
                            @if (isset($checkout_data[$defaultLang]['payment'][$code]['custom_fields']) &&
                                    !empty($checkout_data[$defaultLang]['payment'][$code]['custom_fields']))
                                @foreach ($checkout_data[$defaultLang]['payment'][$code]['custom_fields'] as $custom_field_code => $custom_field)
                                    <div class="form-group row border-bottom"
                                        data-item-id="{{ $custom_field_code }}">
                                        <input type="hidden"
                                            name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][custom_fields][{{ $custom_field_code }}][type]"
                                            value="copy">
                                        {{-- <label class="col-md-3 mb-2">{{ __('Type') }}</label>
                                    <div class="col-md-9 mb-2">
                                        <select name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][custom_fields][{{ $custom_field_code }}][type]" class="select2">
                                            <option value="input" {{ (isset($custom_field['type']) && $custom_field['type'] == 'input')  ? 'selected' : '' }}>{{ __('Text input') }}</option>
                                            <option value="copy" {{ (isset($custom_field['type']) && $custom_field['type'] == 'copy')  ? 'selected' : '' }}>{{ __('Info with copy button') }}</option>
                                            <option value="nova_city" {{ (isset($custom_field['type']) && $custom_field['type'] == 'nova_city')  ? 'selected' : '' }}>{{ __('NP city') }}</option>
                                            <option value="nova_branch" {{ (isset($custom_field['type']) && $custom_field['type'] == 'nova_branch')  ? 'selected' : '' }}>{{ __('NP branch') }}</option>
                                        </select>
                                    </div> --}}

                                        {{-- <label class="col-md-3 mb-2">{{ __('Save to') }}</label>
                                    <div class="col-md-9 mb-2">
                                        <select name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][custom_fields][{{ $custom_field_code }}][save_as]" class="select2">
                                            @foreach ($fields as $f)
                                                <option value="{{ $f }}" {{ (isset($custom_field['save_as']) && $custom_field['save_as'] == $f)  ? 'selected' : '' }}>{{ __($f) }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                        <label class="col-md-3 mb-2">{{ __('name') }}</label>
                                        <div class="col-md-9 mb-2">
                                            @foreach ($localizations as $lang => $lang_name)
                                                <div class="input-group mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><img
                                                                src="/images/langs/{{ $lang }}.jpg"
                                                                style="width: 20px"
                                                                alt="{{ $lang }}"></span>
                                                    </div>
                                                    <input type="text"
                                                        name="setting_data[{{ $lang }}][checkout][payment][{{ $code }}][custom_fields][{{ $custom_field_code }}][name]"
                                                        value="{{ old('setting_data.' . $lang . '.checkout.payment.' . $code . '.custom_fields.' . $custom_field_code . '.name', $checkout_data[$lang]['payment'][$code]['custom_fields'][$custom_field_code]['name'] ?? '') }}"
                                                        class="form-control">
                                                </div>
                                            @endforeach
                                        </div>
                                        <label class="col-md-3 mb-2">{{ __('Text') }}</label>
                                        <div class="col-md-9 mb-2">
                                            @foreach ($localizations as $lang => $lang_name)
                                                <div class="input-group mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><img
                                                                src="/images/langs/{{ $lang }}.jpg"
                                                                style="width: 20px"
                                                                alt="{{ $lang }}"></span>
                                                    </div>
                                                    <input type="text"
                                                        name="setting_data[{{ $lang }}][checkout][payment][{{ $code }}][custom_fields][{{ $custom_field_code }}][text]"
                                                        value="{{ old('setting_data.' . $lang . '.checkout.payment.' . $code . '.custom_fields.' . $custom_field_code . '.text', $checkout_data[$lang]['payment'][$code]['custom_fields'][$custom_field_code]['text'] ?? '') }}"
                                                        class="form-control">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="col-md-12 btn btn-danger remove-field-element">
                                            <i class="mdi mdi-delete"></i>
                                            {{ __('Remove') }}
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <template>
                            <div class="form-group row" data-item-id="#dynamicPlaceholder">
                                <input type="hidden"
                                    name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][custom_fields][#dynamicPlaceholder][type]"
                                    value="copy">
                                {{-- <label class="col-md-3 mb-2">{{ __('Type') }}</label>
                            <div class="col-md-9 mb-2">
                                <select name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][custom_fields][#dynamicPlaceholder][type]" class="select2">
                                    <option value="input">{{ __('Text input') }}</option>
                                    <option value="copy">{{ __('Info with copy button') }}</option>
                                    <option value="nova_city">{{ __('NP city') }}</option>
                                    <option value="nova_branch">{{ __('NP branch') }}</option>
                                </select>
                            </div>
                            <label class="col-md-3 mb-2">{{ __('Save to') }}</label>
                            <div class="col-md-9 mb-2">
                                <select name="setting_data[{{ $defaultLang }}][checkout][payment][{{ $code }}][custom_fields][#dynamicPlaceholder][save_as]" class="select2">
                                    @foreach ($fields as $f)
                                        <option value="{{ $f }}">{{ __($f) }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                                <label class="col-md-3 mb-2">{{ __('name') }}</label>
                                <div class="col-md-9 mb-2">
                                    @foreach ($localizations as $lang => $lang_name)
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="/images/langs/{{ $lang }}.jpg"
                                                        style="width: 20px" alt="{{ $lang }}"></span>
                                            </div>
                                            <input type="text"
                                                name="setting_data[{{ $lang }}][checkout][payment][{{ $code }}][custom_fields][#dynamicPlaceholder][name]"
                                                value="" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                                <label class="col-md-3 mb-2">{{ __('Text') }}</label>
                                <div class="col-md-9 mb-2">
                                    @foreach ($localizations as $lang => $lang_name)
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="/images/langs/{{ $lang }}.jpg"
                                                        style="width: 20px" alt="{{ $lang }}"></span>
                                            </div>
                                            <input type="text"
                                                name="setting_data[{{ $lang }}][checkout][payment][{{ $code }}][custom_fields][#dynamicPlaceholder][text]"
                                                value="" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="col-md-12 btn btn-danger remove-field-element">
                                    <i class="mdi mdi-delete"></i>
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </template>
                        <button type="button" class="btn btn-success add-field-element">
                            <i class="mdi mdi-plus"></i>
                            {{ __('Add') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var select2Field = $('.select2-field');

            select2Field.select2({
                placeholder: 'Пошук, оберть щоб додати',
                ajax: {
                    url: 'https://good.owlweb.com.ua/api/v1/novaposhta/search/city',
                    type: 'post',
                    delay: 250,
                    dataType: 'json',
                    contentType: 'application/json',
                    data: function(params) {
                        return '{"search":"' + params.term + '"}';
                    },
                    processResults: function(result) {
                        var data = result.data.map(function(item) {
                            return {
                                id: item.ref,
                                text: item.description
                            };
                        });
                        return {
                            results: data
                        };
                    }
                },
                templateResult: function(data) {
                    if (data.loading) {
                        return data.text;
                    }
                    var $result = $("<span></span>");
                    $result.text(data.text);
                    $result.attr("data-ref", data.ref);
                    return $result;
                }
            });

            select2Field.on('change', function(e) {
                var data = select2Field.select2('data')[0];
                console.log("Item selected", data);

                const parent = $('#cities_container');
                const template = parent.find('template');
                const container = parent.find('.items-container');

                const city_name = data.text;
                const city_id = data.id;

                create_item_from_template_v2(template, container, '#dynamicPlaceholder', city_name, city_id);

                container.find('input, textarea').prop('disabled', false);
            });

        });



        // cities delete
        $(document).on('click', '.remove-item', function() {
            $(this).parents('.item-group').remove();
        });

        $(document).on('click', '.add-field-element', function() {
            let container = $(this).parent().find('.container');
            let cloneElem = $(this).parent().find('template');
            let placeholder = '#dynamicPlaceholder';

            container.append(cloneElem.html().replace_all(placeholder, get_item_id(container.children())));
        });

        $(document).on('click', '.remove-field-element', function() {
            $(this).closest('.form-group').remove();
        });

        function create_item_from_template_v2(template, container, placeholder, city_name, city_id) {
            const clone = template.prop('content').cloneNode(true);

            const $clone = $(clone);

            $clone.find('.imput_city_name').val(city_name);
            $clone.find('.imput_city_id').val(city_id);

            clone.querySelector('.item-template').setAttribute('data-item-id', get_item_id(container.children()))

            $clone.find('input').each(function() {
                this.name = this.name.replace(placeholder, get_item_id(container.children()));
            });

            container.append($clone);
        }
    </script>
@endpush
