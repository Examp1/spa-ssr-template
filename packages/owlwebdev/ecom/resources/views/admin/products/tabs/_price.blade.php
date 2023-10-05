<div>
    <span class="btn btn-success text-white btn-xs btn-add-price-elem" data-product_id="{{ $model->id }}">
        <i class="mdi mdi-plus"></i>
        {{ __('Add') }}
    </span>

    <span class="btn btn-success text-white btn-xs btn-save-all" data-product_id="{{ $model->id }}">
        <i class="mdi mdi-content-save-all"></i>
        {{ __('Save all') }}
    </span>
</div>

<br>

<div class="prices-container">
    @if (count($model->prices))
        @foreach ($model->prices as $item)
            @include('ecom::admin.products._price_item', [
                'priceModel' => $item,
                'opened' => false,
            ])
        @endforeach
    @endif
</div>
