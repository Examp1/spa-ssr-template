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

                    <h1>Відновлення паролю</h1>

                    <form action="{{ route('auth.password.change') }}" method="POST">
                        @csrf

                        <input type="hidden" name="code" value="{{$code}}">
                        <input type="hidden" name="type" value="{{$type}}">
                        <input type="hidden" name="field" value="{{$field}}">

                    <div class="inputWrap slim @error('password') error tipShown @else error0 tipShown0 @enderror">
                        <input type="password" name="password" id="password" placeholder="Уведіть пароль">
                        <div class="eye">
                            <!-- v-if переключалка -->
                            <img src="img/cab/eye1.svg" alt="" class="pass_show">
                            <img src="img/cab/eye2.svg" alt="" class="pass_hide hidden">
                        </div>
                        <div class="deco"></div>
                        <label for="password">Пароль</label>
                        <div class="botText">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="inputWrap slim @error('password_confirmation') error tipShown @else error0 tipShown0 @enderror">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Повторіть пароль">
                        <div class="eye">
                            <!-- v-if переключалка -->
                            <img src="img/cab/eye1.svg" alt="" class="pass_show">
                            <img src="img/cab/eye2.svg" alt="" class="pass_hide hidden">
                        </div>
                        <div class="deco"></div>
                        <label for="password_confirmation">Пароль ще раз</label>
                        <div class="botText">{{ $errors->first('password_confirmation') }}</div>
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
