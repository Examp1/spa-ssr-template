<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        body {
            color: #333333;
            font-family: DejaVu Sans, sans-serif;
            background-color: white;
            padding: 20px 15px;
        }

        table {
            width: 100%;
        }

        table.no-border tr td,
        table.no-border tr th {
            border: none;
        }

        hr.mt {
            border: none;
            border-bottom: 1px solid #ccc;
            margin-top: 40px;
        }

        hr.mb {
            border: none;
            border-bottom: 1px solid #ccc;
            margin-bottom: 35px;
        }

        table.border-vertical {
            margin-top: 30px;
            margin-bottom: 25px;
            border-collapse: collapse;
            /* border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc; */
        }

        table.border-vertical tr td {
            border: none;
            vertical-align: top;
        }

        table tr td,
        table tr th {
            border: 1px solid #ccc;
            padding: 5px;
        }

        table tr th {
            font-weight: normal;
        }
    </style>
</head>

<body>
    <table class="no-border">
        <tr>
            <td>
                <img src="{{ get_image_uri($logo) }}" style="width: 70px; height: 70px">
            </td>
            <td style="text-align: right">
                <p>{{ $contacts['address'] }}</p>
                <p>{{ $contacts['email'] }}</p>
                @if (isset($contacts['phones']) && is_array($contacts['phones']) && count($contacts['phones']))
                    @foreach ($contacts['phones'] as $phone)
                        <p>{{ $phone['number'] }}</p>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
    <hr class="mt">
    <table class="border-vertical">
        <tr>
            <td>
                <b>Клієнт</b>
                <p>{{ $order->user->name }} {{ $order->user->lastname }} {{ $order->user->surname }}</p>
                <p>{{ $order->user->email }}</p>
                <p>{{ $order->user->phone }}</p>
            </td>
            <td>
                <b>Інформація про доставку</b>

                @isset(\Owlwebdev\Ecom\Models\Cart::getShippingMethods()[$order->shipping_method]['name'])
                    <p><{{ \Owlwebdev\Ecom\Models\Cart::getShippingMethods()[$order->shipping_method]['name'] }}</p>
                @endisset

                <p>{{ $order->shipping_name }} {{ $order->shipping_lastname }} {{ $order->shipping_surname }}</p>
                <p>{{ $order->shipping_phone }}</p>
                <p>{{ $order->shipping_city }}</p>
                <p>{{ $order->shipping_branch }}</p>
                @php
                    $addr = implode(', ', array_filter([
                        $order->shipping_street,
                        $order->shipping_house,
                        $order->shipping_apartment,
                        $order->shipping_postcode,
                    ]));
                @endphp
                <p>{{ $addr }}</p>

            </td>
        </tr>
    </table>
    <hr class="mb">
    @if ($order->comment)
        <p style="margin-bottom: 10px;"><b>Коментар</b>: {{ $order->comment }}</p>
    @endif
    <br>
    Замовлення № {{ $order->id }}<br>від {{ $order->created_at->format('d.m.Y') }}
    <table style="border: 1px solid #ccc; margin-top: 25px;" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="text-align: left">Назва товару</th>
                <th style="text-align: center">Вартість</th>
                <th style="text-align: center">Кількість</th>
                <th style="text-align: center">Сума</th>
            </tr>
        </thead>
        @if (count($order->products))
            <tbody>
                @foreach ($order->products as $product)
                    <?php //$price = $product->special > 0 ? $product->special : $product->price;
                    ?>
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td style="text-align: center">{{ $product->current_price_without_discount }}</td>
                        <td style="text-align: center">{{ $product->count }}</td>
                        <td style="text-align: center">{{ $product->total_price_without_discount }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right"><b>Всього</b></td>
                    <td style="text-align: center">{{ $order->subtotal_price_without_discount }}</td>
                </tr>

                @if($order->discount)
                    <tr>
                        <td colspan="3" style="text-align: right"><b>Знижка</b></td>
                        <td style="text-align: center">{{ $order->discount }}</td>
                    </tr>
                @endif

                <tr>
                    <td colspan="3" style="text-align: right"><b>{{ __('Total price') }}</b></td>
                    <td style="text-align: center">{{ number_format($order->total, '2', '.', ' ') }}</td>
                </tr>
            </tbody>
        @endif
    </table>

    <br><br><br><br><br>
    <table class="no-border">
        <tr>
            <td style="width: 33%"></td>
            <td style="width: 33%"></td>
            <td style="width: 33%">
                <img src="{{ asset('/images/invoice.jpg') }}" style="width: 100px; height: auto"><br><br>
                <span
                    style="display: inline-block; text-align: left; width: 200px;border-top:1px solid #ccc">Підпис</span>
            </td>
        </tr>
    </table>
</body>

</html>
