<?php
/* @var \App\Models\ProductPrices $priceModel */
/* @var boolean $opened */

$r = rand(1000, 9999);
$model_attributes = $model->productAttributes->sortBy('slug')->groupBy('attribute_id');
$selected_price_attributes_ids = $priceModel
    ->attributes()
    ->get()
    ->pluck('id');

?>
<div class="card card-price-element" id="price-{{ $r }}" data-image-class="{{ 'price_image_' . $r }}"
    data-images-class="{{ 'price_images_' . $r }}">
    <input type="hidden" class="data-changed" value="0" />
    <div class="card-header" style="padding: 5px 10px 0 10px;">
        @if (isset($priceModel->name))
            <a data-toggle="collapse" href="#collapseExample{{ $r }}">
                <span style="margin-top: 8px;display: inline-block;font-weight: 600;color: darkslateblue;">
                    {{ $priceModel->name }} {{ $priceModel->code ? '(' . $priceModel->code . ')' : '' }}
                    @foreach ($model_attributes as $attribute_id => $attrs)
                        @if ($attrs->isNotEmpty())
                            @foreach ($attrs as $attribute)
                                @if ($selected_price_attributes_ids->contains($attribute->id))
                                    @switch($attribute->attr_type)
                                        @case('color')
                                            <span> {{ $attribute->value }}</span>
                                        @break

                                        @case('image')
                                            <span> 
                                                <img class="img-thumbnail" style="max-width: 50px; max-height: 50px;"
                                                    src="{{ get_image_uri($attribute->value ?? null) }}" />
                                            </span>
                                        @break

                                        @default
                                            <span> {{ $attribute->value }}</span>
                                    @endswitch
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </span>
            </a>
        @endif

        <span class="btn btn-outline-primary float-right btn-xs @if (!$opened) collapsed @endif"
            data-toggle="collapse" href="#collapseExample{{ $r }}" style="margin-top: 4px; margin-left: 10px"
            title="Сховати">
            <i class="mdi mdi-minus"></i>
        </span>

        <span class="btn btn-danger text-white float-right btn-xs btn-remove-price-elem"
            data-id="{{ $priceModel->id }}" style="margin-top: 4px; margin-left: 10px" title="{{ __('Remove') }}">
            <i class="mdi mdi-delete"></i>
        </span>

        <span class="btn btn-secondary text-white float-right btn-xs btn-copy-price-elem"
            data-id="{{ $priceModel->id }}" style="margin-top: 4px; margin-left: 10px" title="{{ __('Copy') }}">
            <i class="fa fa-copy"></i>
        </span>

        <span class="btn btn-success text-white float-right btn-xs btn-save-price-elem" data-id="{{ $priceModel->id }}"
            style="margin-top: 4px;" title="{{ __('Save') }}">
            <i class="mdi mdi-content-save"></i>
        </span>

        <div class="material-switch float-right" style="margin-right: 10px;vertical-align: middle"
            title="{{ __('Status') }}">
            <input id="someSwitchOptionSuccess_{{ $r }}" class="pf-status pf-field" value="1"
                @if ($priceModel->status) checked @endif type="checkbox" />
            <label for="someSwitchOptionSuccess_{{ $r }}" class="label-success"></label>
        </div>

        <div class="input-group col-md-3 float-right">
            {{-- JIRA --}}
            <div class="input-group-prepend" title="Значення сортування приймається після збереження">
                <span class="input-group-text">{{ __('Sort') }}</span>
            </div>
            <input type="number" placeholder="{{ __('Sort order') }}"
                title="{{ __('Sort order') }}(оновиться після збереження товару або оновлення сторінки)"
                data-title="{{ __('Sort order') }}(оновиться після збереження товару або оновлення сторінки)"
                data-original-title="{{ __('Sort order') }}(оновиться після збереження товару або оновлення сторінки)"
                class="form-control tip pf-order pf-field" value="{{ $priceModel->order }}">
        </div>

        <div class="input-group col-md-2 float-right">
            <div class="input-group-prepend">
                <span class="input-group-text">Кіл.</span>
            </div>
            <input type="number" class="form-control pf-count pf-field" value="{{ $priceModel->count }}">
        </div>


        {{-- <div class="input-group col-md-1 float-right">
            <div class="input-group-prepend">
                <input type="color"
                    placeholder="{{ __('Color') }}"
                    title="{{ __('Color') }}"
                    data-title="{{ __('Color') }}"
                    data-original-title="{{ __('Color') }}"
                    class="form-control tip pf-color pf-field"
                    value="{{ $priceModel->color }}"
                    style="min-width: 4em;">
            </div>
        </div> --}}
        {{-- @if ($priceModel->image)
            <div class="input-group col-md-1 float-right">
                <div class="input-group">
                    <img class="form-control" src="{{ get_image_uri($priceModel->image ?? null) }}">
                </div>
            </div>
        @endif --}}

    </div>
    <div class="card-body collapse @if ($opened) show @endif"
        id="collapseExample{{ $r }}">

        {{-- <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Кількість</span>
                    </div>
                    <input type="number" class="form-control pf-count pf-field"
                        value="{{ $priceModel->count }}">
                </div>
            </div>
        </div> --}}

        {{-- <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Колір</span>
                        <input type="color" class="form-control pf-color pf-field"
                            value="{{ $priceModel->color }}"
                            style="min-width: 4em;">
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Ціна</span>
                        <input type="text" class="form-control pf-price pf-field"
                            value="{{ $priceModel->price }}">
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="form-group row">
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Назва</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control pf-name pf-field" value="{{ $priceModel->name }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Артикул</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ $model->code }}-</span>
                            </div>
                            <input type="text" class="form-control pf-code pf-field" value="{{ $priceModel->code }}">
                        </div>
                        {{-- <input type="text" class="form-control pf-code pf-field" value="{{ $priceModel->code }}"> --}}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Ціна</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control pf-price pf-field" value="{{ $priceModel->price }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Стара ціна</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control pf-old_price pf-field"
                            value="{{ $priceModel->old_price }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">{{ __('Cost') }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control pf-cost pf-field"
                            value="{{ $priceModel->cost }}">
                    </div>
                </div>

                {{-- <div class="form-group row">
                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Колір</label>
                    <div class="col-sm-8">
                        <input type="color" class="form-control pf-color pf-field"
                            value="{{ $priceModel->color }}">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Зображення</label>
                    <div class="col-sm-8">
                        {{ media_preview_box('price_image_' . $r, $priceModel->image ?? null, $errors ?? null) }}
                    </div>
                </div> --}}
            </div>

            <div class="col-md-8 attributes-div-{{ $r }}">
                @foreach ($model_attributes as $attribute_id => $attrs)
                    @if ($attrs->isNotEmpty())
                        <div class="form-group row price_attributes-div {{ $r }}-attr-{{ $attribute_id }}">
                            <label class="col-md-3">{{ $attrs->first()->attr_name }}</label>
                            <div class="col-md-9">
                                @foreach ($attrs as $attribute)
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox"
                                            class="custom-control-input radio-like-checkbox pf-attribute_id pf-field"
                                            data-attribute-class="{{ $r }}-attr-{{ $attribute_id }}"
                                            id="{{ $r }}-attr-{{ $attribute->attr_slug }}-{{ $attribute->id }}"
                                            name="attributes[{{ $attribute->attr_slug }}]"
                                            value="{{ $attribute->id }}"
                                            @if ($selected_price_attributes_ids->contains($attribute->id)) checked @endif>
                                        @switch($attribute->attr_type)
                                            @case('color')
                                                <label class="custom-control-label"
                                                    for="{{ $r }}-attr-{{ $attribute->attr_slug }}-{{ $attribute->id }}">{{ $attribute->value }}</label>
                                            @break

                                            @case('image')
                                                <label class="custom-control-label"
                                                    for="{{ $r }}-attr-{{ $attribute->attr_slug }}-{{ $attribute->id }}">
                                                    <img class="img-thumbnail" style="max-width: 50px; max-height: 50px;"
                                                        src="{{ get_image_uri($attribute->value ?? null) }}" />
                                                </label>
                                            @break

                                            @default
                                                <label class="custom-control-label"
                                                    for="{{ $r }}-attr-{{ $attribute->attr_slug }}-{{ $attribute->id }}">{{ $attribute->value }}</label>
                                        @endswitch
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr />
                    @endif
                @endforeach
            </div>
        </div>

        <hr>
        <div class="images-container {{ 'price_images_' . $r }} row">
            @foreach ($priceModel->images as $image)
                <div class="col-md-3 form-group">

                    <div class="images-element border p-2">
                        {{-- JIRA --}}
                        {{-- тут переделал html структуру чтобы удалени и сортировка стали вверху --}}
                        <div class="row">
                            <div class="input-group mb-2" style="width: auto">
                                <span class="btn btn-danger text-white remove-images-item">
                                    <i class="mdi mdi-delete"></i>
                                    {{-- {{ __('Remove') }} --}}
                                </span>
                            </div>
                            <div class="input-group mb-2" style="width: calc(100% - 60px)">
                                {{-- <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Sort') }}</span>
                                </div> --}}
                                <div class="input-group-prepend" title="Порядок сортування">
                                    <input type="number" class="form-control"
                                        name="price_images[order][{{ $image->id }}]"
                                        value="{{ $image->order }}" />
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="price_images[id][{{ $image->id }}]"
                            value="{{ $image->id }}" />
                        <input type="hidden" name="price_images[price_id][{{ $image->id }}]"
                            value="{{ $image->price_id ?? null }}" />

                        <div class="input-group mb-2 justify-content-center">
                            {{ media_preview_box('price_images[image][' . $image->id . ']', $image->image ?: null, $errors) }}
                        </div>




                    </div>
                </div>
            @endforeach
        </div>
        <template id="price_images_template">
            <div class="col-md-3 form-group">
                <div class="images-element border p-2">
                    <input type="hidden" name="price_images[id][]" value="0" />
                    <input type="hidden" name="price_images[price_id][]" value="null" />
                    <div class="row">
                        <div class="input-group mb-2" style="width: auto">
                            <span class="btn btn-danger text-white remove-images-item">
                                <i class="mdi mdi-delete"></i>
                                {{-- {{ __('Remove') }} --}}
                            </span>
                        </div>
                        <div class="input-group mb-2" style="width: calc(100% - 60px)">
                            {{-- <div class="input-group-prepend">
                                <span class="input-group-text">{{ __('Sort') }}</span>
                            </div> --}}
                            <div class="input-group-prepend">
                                <input type="number" class="form-control" name="price_images[order][]"
                                    value="0" />
                            </div>
                        </div>

                    </div>
                    <div class="input-group mb-2 justify-content-center">
                        {{ media_preview_box('price_images[image][]', null, $errors) }}
                    </div>


                </div>
            </div>
        </template>

        <span data-template="price_images_template" class="btn btn-success text-white btn-xs add-images-element"
            data-product_id="{{ $priceModel->product_id }}">
            <i class="mdi mdi-plus"></i>
            {{ __('Add') }} {{ __('Image') }}
        </span>

    </div>
</div>
