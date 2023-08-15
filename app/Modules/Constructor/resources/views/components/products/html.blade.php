@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    <div class="row">
        <div class="col-12">
            <div class="form-group input-group-sm mb-12">
                <input type="text" placeholder="{{ $params['labels']['title'] }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">
                @error(constructor_field_name_dot($key, 'content.title'))
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-12">
            <div class="form-group input-group-sm mb-12">
                <input type="text" placeholder="{{ $params['labels']['subtitle'] }}" class="form-control @error(constructor_field_name_dot($key, 'content.subtitle')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.subtitle') }}" value="{{ old(constructor_field_name_dot($key, 'content.subtitle'), $content['subtitle'] ?? '') }}">
                @error(constructor_field_name_dot($key, 'content.title'))
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="input-group">
            <div style="display: none;">
                <div data-item-id="#imageInputPlaceholder1" class="product-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                    <div class="col-10">
                        <label>{{ $params['labels']['product_id'] }}</label>
                        <select name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][product_id]" class="select2-field-pr" style="width: 100%">
                            <option value="">---</option>
                            @foreach(\App\Models\Products::query()->active()->get() as $product)
                                <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-2">
                        <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

            <div class="product-list-container w-100">
                @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                    <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                        <div class="col-10">
                            <label>{{ $params['labels']['product_id'] }}</label>
                            <?php $sel = $value['product_id'] ?? '';?>
                            <select name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][product_id]" class="select2-field" style="width: 100%">
                                <option value="">---</option>
                                @foreach(\App\Models\Products::query()->active()->get() as $product)
                                    <option value="{{$product->id}}" @if($sel == $product->id) selected @endif>{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-1">
                            <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="button" class="btn btn-info btn-sm add-product-list-item_{{$lang}} d-block mt-2">Добавить</button>
    </div>
</div>

@include('constructor::layouts.footer')
