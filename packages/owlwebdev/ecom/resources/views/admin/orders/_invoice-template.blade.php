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

        table.no-border tr td, table.no-border tr th {
            border:none;
        }

        table tr td, table tr th {
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
            <img src="{{get_image_uri($logo)}}" style="width: 100px; height: auto">
        </td>
        <td style="text-align: right">
            <p>{{$contacts['address']}}</p>
            <p>{{$contacts['email']}}</p>
            @if(isset($contacts['phones']) && is_array($contacts['phones']) && count($contacts['phones']) )
                @foreach($contacts['phones'] as $phone)
                    <p>{{$phone['number']}}</p>
                @endforeach
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Клієнт</b>
            <p>{{$order->user->name}}</p>
            <p>{{$order->user->email}}</p>
            <p>{{$order->user->phone}}</p>

            <br>
            <b>Спосіб доставки</b>
            @isset(\Owlwebdev\Ecom\Models\Cart::getShippingMethods()[$order->shipping_method]['name'])
                <p><{{\Owlwebdev\Ecom\Models\Cart::getShippingMethods()[$order->shipping_method]['name']}}</p>
            @endisset
            <p>{{$order->shipping_city}}</p>
            <p>{{$order->shipping_address}}</p>
            <p>{{$order->shipping_phone}}</p>


            @if($order->comment)
                <br>
                <b>Коментар</b>
                <p>{{$order->comment}}</p>
            @endif

            <br>
            Замовлення № {{$order->id}}<br>від {{$order->created_at->format('d.m.Y')}}
        </td>
    </tr>
</table>
<br>
<table style="border: 1px solid #ccc" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th style="text-align: left">Назва товару</th>
            <th style="text-align: center">Вартість</th>
            <th style="text-align: center">Кількість</th>
            <th style="text-align: center">Сума</th>
        </tr>
    </thead>
    @if(count($order->products))
    <tbody>
        @foreach($order->products as $product)
            <?php $price = $product->special > 0 ? $product->special : $product->price;  ?>
            <tr>
                <td>{{$product->name}}</td>
                <td style="text-align: center">{{number_format($price,'2','.',' ')}}</td>
                <td style="text-align: center">{{$product->count}}</td>
                <td style="text-align: center">{{number_format($price * $product->count,'2','.',' ')}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: right"><b>Всього</b></td>
            <td style="text-align: center">{{number_format($order->full_subtotal_price,'2','.',' ')}}</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right"><b>Знижка</b></td>
            <td style="text-align: center">{{$order->discount}}</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right"><b>До сплати</b></td>
            <td style="text-align: center">{{number_format($order->total,'2','.',' ')}}</td>
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
            <span style="display: inline-block; text-align: left; width: 200px;border-top:1px solid #ccc">Підпис</span>
        </td>
    </tr>
</table>
</body>
</html>
