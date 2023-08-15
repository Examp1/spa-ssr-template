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
                    <h1>Раді вітати в Наш газ! 👋</h1>

                    <form action="{{ route('login') }}" class="form" method="POST">
                        @csrf

                    <div class="inputWrap slim @error('email') error tipShown @else error0 tipShown0 @enderror">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="@error('email') error @enderror" placeholder="Електронна пошта" autocomplete="email" autofocus>
                        <div class="deco"></div>
                        <label for="email">email</label>
                        <div class="botText">{{ $errors->first('email') }}</div>
                    </div>
                    <div class="inputWrap slim @error('password') error tipShown @else error0 tipShown0 @enderror">
                        <input type="password" name="password" id="password" placeholder="Уведіть пароль" autocomplete="current-password">
                        <div class="eye">
                            <!-- v-if переключалка -->
                            <img src="img/cab/eye1.svg" alt="" class="pass_show">
                            <img src="img/cab/eye2.svg" alt="" class="pass_hide hidden">
                        </div>
                        <div class="deco"></div>
                        <label for="password">Пароль</label>
                        <div class="botText">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="passForgotten">
                        <a href="{{ route('auth.password.reset') }}">Забули пароль?</a>
                    </div>

                    <div class="checkBoxWrap">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <div class="deco">
                            <img src="img/cab/Rectangle 331.svg" alt="">
                        </div>
                        <span>Запам’ятати мене</span>
                    </div>

                    <div class="lnkWrp">
                        <a href="{{route('register')}}" class="accExist">Ще не зареєстровані?</a>
                        <a href="javascript:void(0)" class="controlBtn disabled0" onclick="$(this).closest('form').submit()">Увійти</a>
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

@push('styles')
    <style>
        .hidden {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".pass_show").on('click',function () {
                $(this).siblings('img').removeClass('hidden');
                $(this).addClass('hidden');

                $(this).closest('.inputWrap').find('input').prop('type','text');
            });

            $(".pass_hide").on('click',function () {
                $(this).siblings('img').removeClass('hidden');
                $(this).addClass('hidden');

                $(this).closest('.inputWrap').find('input').prop('type','password');
            });
        });
    </script>
@endpush
