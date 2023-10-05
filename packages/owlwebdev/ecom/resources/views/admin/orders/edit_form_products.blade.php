<div class="row ml-3 mr-3">
    <div class="col-sm-12">
        <label for="colorValue" class="control-label col-form-label">{{ __('Products') }}</label>

        <div class="alert alert-warning" role="alert">
            Щоб змінити кількість або додати товари, спочатку необхідно змінити статус замовлення на “Скасовано”, після редагування необхідно змінити статус на "Очікує".
        </div>

        <table id="ordersTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="font-weight-bold">{{ __('ID') }}</th>
                <th class="font-weight-bold">{{ __('Name') }}</th>
                <th class="font-weight-bold">{{ __('Quantity') }}</th>
                <th class="text-center font-weight-bold">{{ __('Unit price') }}</th>
                <th class="text-center font-weight-bold">{{ __('Total') }}</th>
                {{-- <th class="text-center font-weight-bold">{{ __('Actions') }}</th> --}}
            </tr>
            </thead>

            <tbody class="products-container">
            @forelse($order->products as $product)
                <tr>
                    <td>
                        @can('products_edit')
                            <a href="{{ route('products.edit', $product->product_id) }}">
                                {{ $product->product_id }}
                            </a>
                        @else
                            {{ $product->product_id }}
                        @endcan
                    </td>

                    <td>
                        <p>
                            @can('products_edit')
                                <a href="{{ route('products.edit', $product->product_id) }}">
                                    {{ $product->name }}
                                </a>
                            @else
                                {{ $product->name }}
                            @endcan

                            @if(!empty($product->option_data) && $product->option_data !== '[]')
                                <?php
                                    $option_name = '';
                                    $option = json_decode($product->option_data);

                                    if (!empty($option)) {
                                        $option_name = $option->name;
                                        if (isset($option->product_attributes)) {
                                            $option_name .= ': ';
                                            foreach ($option->product_attributes as $attr) {
                                                $option_name .= ' ' . ($attr->alt ? : $attr->value);

                                            }
                                        }
                                    }
                                ?>
                                ({{ $option_name }})
                            @endif
                        </p>
                    </td>

                    <td>
                        @if ($order->id && $order->order_status_id == 3)
                            <form class="form-horizontal" method="POST" action="{{ route('orders.products.update', $product) }}">
                            @csrf
                            <?php
                                $max = $product->count;
                                if ($product->option_id !== null && $product->product) {
                                    $price = $product->product->prices()->where('count', '>', 0)->active()->find($product->option_id);
                                    if ($price) {
                                        $max = $price->count;
                                    }
                                } elseif($product->product) {
                                    $max = $product->product->quantity??0;
                                }
                            ?>
                            <input type="number" name="count" value="{{ $product->count }}" max="{{ $max }}">

                            <button type="submit" class="btn btn-info btn-sm">
                                <i class="mdi mdi-refresh"></i>
                            </button>
                        </form>
                        @else
                            <b>{{ $product->count }}</b>
                        @endif

                    </td>
                    <td class="text-center">
                        {{ $product->current_price_without_discount }}
                    </td>
                    <td class="text-center">{{ $product->total_price_without_discount }}</td>
                    <td class="text-center">
                        @if ($order->id && $order->order_status_id == 3)
                            <form class="form-horizontal" method="POST" action="{{ route('orders.products.delete', $product) }}">
                                @csrf

                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="mdi mdi-delete-empty"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('No products') }}</td>
                </tr>
            @endforelse
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Всього</th>
                    <th class="text-center font-weight-bold">{{ $order->subtotal_price_without_discount }}</th>
                    <th></th>
                </tr>

                @if ($order->discount)
                    <tr>
                        <th colspan="4" class="text-right">{{ __('Coupon') }},{{ __('Discount') }}</th>
                        <th class="text-center font-weight-bold">{{ $order->discount }}</th>
                        <th></th>
                    </tr>
                @endif

                <tr>
                    <th colspan="4" class="text-right">{{ __('Total price') }}</th>
                    <th class="text-center font-weight-bold">{{ $order->total ?? 0 }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>




    </div>

    @if ($order->id && $order->order_status_id == 3)
    <div class="col-12 mb-3">
        <form id="addProductForm" class="form-horizontal" method="POST" action="{{ route('orders.products.store') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">
            <input type="hidden" id="option_id" name="option_id" value="{{ $option_id ?? '' }}"/>
            <input type="hidden" id="option_data" name="option_data" value="{{ $option_data ?? '' }}"/>

            <div class="form-group row">
                <div class="col-sm-8">
                    <select class="select2" id="product_select" name="product_id">
                        <?php
                            $max = false;
                            $option_id = '';
                            $option_data = '';
                        ?>
                        @foreach($products as $key => $prod)
                            @if ($prod->prices->isNotEmpty())
                                @foreach ($prod->prices as $option)
                                    <?php
                                        if($max == false) {
                                            $max = $option->count;
                                            $option_id = $option->id;
                                            $option_data = json_encode($option);
                                        }

                                    ?>
                                    <option
                                        value="{{ $prod->id }}"
                                        data-option_id="{{ $option->id }}"
                                        data-option_data="{{ json_encode($option) }}"
                                        data-option_max="{{ $option->count }}"
                                        >
                                            {{ $prod->translate()->name }}({{ $option->name }})({{ $option->count }})
                                    </option>
                                @endforeach
                            @else
                                @php($max = $prod->quantity)
                                <option value="{{ $prod->id }}" data-option_max="{{ $prod->quantity }}">{{ $prod->translate()->name }}</option>
                            @endif
                            <?php
                                $max = true; // take options from first product
                            ?>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="number" id="product_quantity" class="" name="count" value="0" max="{{ $max }}" min="0">

                    <button type="submit" class="btn btn-success">{{ __('Add product') }}</button>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready( function () {
            $('.select2').select2({
                minimumResultsForSearch: 2
            });
        });

        $(document).on('change', '#product_select', function() {
            //reset options
            let option_id = '';
            let option_data = '';

            if ($(this).find(":selected").data('option_id') !== undefined) {
                option_id = $(this).find(":selected").data('option_id');
                option_data = $(this).find(":selected").data('option_data');
            }

            option_max = $(this).find(":selected").data('option_max');

            $('#option_id').val(option_id);
            $('#option_data').val(JSON.stringify(option_data));
            $('#product_quantity').val(0);
            $('#product_quantity').attr("max", option_max);

        });
    </script>
@endpush
