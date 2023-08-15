@extends('layouts.auth.app')

@section('content')
    <div class="main">
        <div class="leftSide">
            <a href="#" class="logo">
                <img src="img/cab/logo.svg" alt="">
            </a>
            <div class="priceTxt">Ціна на вересень</div>
            <div class="priceVal">6.73<span> ₴/м3</span></div>
        </div>
        <div class="centerSide">
            <div class="authWrp">
                <div class="auth">
                    <div class="headLnks">
                        <a href="{{route('register')}}">Реєстрація</a>
                        <a href="{{route('login')}}">Вхід</a>
                    </div>

                    <h1>Відновлення по номеру телефону</h1>

                    <form method="POST">
                        @csrf

                    <div class="inputWrap slim @if(\Illuminate\Support\Facades\Session::has('error')) error tipShown @else error0 tipShown0 @endif">
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="@error('phone') error @enderror" placeholder="Уведіть номер" autofocus>
                        <div class="deco"></div>
                        <label for="phone">номер телефону</label>
                        <div class="botText">{{ \Illuminate\Support\Facades\Session::get('error') }} </div>
                    </div>

                    <div class="lnkWrp">
                        <div></div>
                        <a href="javascript:void(0)" class="controlBtn disabled0" onclick="$(this).closest('form').submit()">Відправити</a>
                    </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="rightSide">
            <div></div>
            <div>
                <div class="contactWrp">
                    <a href="#">
                        <img src="img/cab/proneic.svg" alt=""> 800 000 00 00
                    </a>
                    <a href="#">
                        <img src="img/cab/logos_telegram.svg" alt=""> Telegram
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
