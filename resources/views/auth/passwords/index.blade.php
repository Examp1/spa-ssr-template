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

                    <h1>Оберіть спосіб відновлення пароля</h1>

                    <a href="{{route('auth.password.phone')}}" class="wideActionLink">
                        <span class="txt">За номером телефону</span>
                        <img src="img/cab/rightArr.svg" alt="">
                    </a>
                    <a href="{{route('auth.password.email')}}" class="wideActionLink">
                        <span class="txt">За електронною поштою</span>
                        <img src="img/cab/rightArr.svg" alt="">
                    </a>
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
