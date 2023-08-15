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
                    <h1>Уведіть код із SMS</h1>

                    <form action="{{route('auth.phone-confirm-code')}}" method="POST">
                        @csrf

                    <div class="inputWrap slim @error('code') error tipShown @else error0 tipShown0 @enderror">
                        <input type="text" id="i1" name="code" placeholder="Х Х Х Х Х Х">
                        <div class="deco"></div>
                        <div class="botText">{{ $errors->first('code') }}</div>
                    </div>

                    <div class="lnkWrp">
                        <div></div>
                        <a href="javascript:void(0)" class="controlBtn disabled0" onclick="$(this).closest('form').submit()">Підтвердити</a>
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
