<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-16x16/portfolio/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-32x32/portfolio/favicon.png" sizes="32x32">
    <link rel="icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-192x192/portfolio/favicon.png" sizes="192x192">
    <link rel="apple-touch-icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-apple-touch-180x180/portfolio/favicon.png" sizes="180x180">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css">
    <link rel="stylesheet" href="{{ asset('/css/front/bootstrap-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/front/main.css') }}">

    <!-- Styles -->
    <style>
        .grecaptcha-badge {
            display: none !important;
        }

        #modalWrapCallbackForm label.error {
            margin: 10px 0;
        }
        @media (max-width: 600px) {
         .col.mobile-service-menu {
             display: block !important;
         }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="mainWrap">
    @include('layouts.mainComponents._topLine')
    @include('layouts.mainComponents._header')
    <main>
        @yield('content')
    </main>
    @include('layouts.mainComponents._footer')
</div>

<div class="modalWrap modalWrapFreeConsultation" style="display: none">
    <div class="modalBody">
        <div class="close close-modal-btn"></div>
        <img src="/img/modalicon.svg" alt="" class="modalIcon">
        <div class="caption">{{__('Application for free consultation accepted')}}</div>
        <div class="text">{{__('Expect an OMI specialist to contact you as soon as possible')}}</div>
        <a href="javascript:void(0)" class="backLnk close-modal-btn">{{__('Back to site')}}</a>
    </div>
</div>

<div class="modalWrap modalWrapCallback" style="display: none;">
    <div class="modalBody">
        <div class="close close-modal-btn"></div>
        <div class="caption">{{__('Enter contact data')}}</div>
        <div class="text">{{__('Fill the form')}}</div>
        <form id="modalWrapCallbackForm">
            @csrf
            <input type="hidden" name="no_recaptcha" value="1">
            @if(Request::segment(1) === 'service')
                <input type="hidden" value="{{$page->name}}" name="service_name">
            @endif
            <input type="text" name="name" placeholder="{{__('Name')}}">
            <input type="text" name="phone" placeholder="{{__('phone number')}}">
            <input type="text" name="email" placeholder="{{__('email')}}">
            <button type="submit">{{__('Send data')}}</button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.pkgd.min.js"></script>
<script src="{{ asset('js/imask.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    var element = $('input[name="phone"]')[0];
    if(element){
        var mask = IMask(element, {mask: '+{38}(000)-000-0000'});
    }

    var element2 = $('input[name="phone"]')[1];

    if(element2){
        var mask2 = IMask(element2, {mask: '+{38}(000)-000-0000'});
    }

    $(".btn-order-service").on('click',function () {
       $('.modalWrapCallback').show();
    });

    $(".close-modal-btn").on('click',function () {
        $(this).closest('.modalWrap').hide();
    })

    $("#bannerContact").validate({
        rules: {
            'phone': {
                required: true,
                minlength: 17
            }
        },
        messages: {
            phone: "{{__('Enter your phone number')}}"
        },
        submitHandler: function (form) {
            grecaptcha.execute().then(function(){
                $.ajax({
                    type: "POST",
                    url: "/add-notice",
                    data: $("#bannerContact").serialize(),
                    beforeSend: function (jqXHR, settings) {

                    },
                    success: function (data) {
                        if(data.success) {
                            $('form input[type="text"]').val('');
                            $('.modalWrapFreeConsultation').show();
                            setTimeout(function() {
                                $('.modalWrapFreeConsultation').hide();
                            }, 3000);
                        }
                    },
                    error: function (jqXHR, text, error) {
                        console.log('Get error!');
                    }
                });
                return false;
            });
        }
    });

    $("#contactForm").validate({
        rules: {
            'email': {
                required: true,
                email: true
            },
            'name': {
                required: true,
                minlength: 2
            },
            'phone': {
                required: true,
                minlength: 17
            }
        },
        messages: {
            phone: "{{__('Enter your phone number')}}",
            name: "{{__('Enter your name')}}",
            email: "{{__('Enter your email')}}",
        },
        submitHandler: function (form) {
            grecaptcha.execute().then(function(){
                $.ajax({
                    type: "POST",
                    url: "/add-notice",
                    data: $("#contactForm").serialize(),
                    beforeSend: function (jqXHR, settings) {

                    },
                    success: function (data) {
                        if(data.success) {
                            $('form input[type="text"]').val('');
                            $('.modalWrapFreeConsultation').show();
                            setTimeout(function() {
                                $('.modalWrapFreeConsultation').hide();
                            }, 3000);
                        }
                    },
                    error: function (jqXHR, text, error) {
                        console.log('Get error!');
                    }
                });
                return false;
            });
        }
    });

    $("#modalWrapCallbackForm").validate({
        rules: {
            'email': {
                required: true,
                email: true
            },
            'name': {
                required: true,
                minlength: 2
            },
            'phone': {
                required: true,
                minlength: 17
            }
        },
        messages: {
            phone: "{{__('Enter your phone number')}}",
            name: "{{__('Enter your name')}}",
            email: "{{__('Enter your email')}}",
        },
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                url: "/add-notice",
                data: $("#modalWrapCallbackForm").serialize(),
                beforeSend: function (jqXHR, settings) {

                },
                success: function (data) {
                    if(data.success) {
                        $('.modalWrapCallback').hide();
                        $('form input[type="text"]').val('');
                        $('.modalWrapFreeConsultation').show();
                        setTimeout(function() {
                            $('.modalWrapFreeConsultation').hide();
                        }, 3000);
                    }
                },
                error: function (jqXHR, text, error) {
                    console.log('Get error!');
                }
            });
        }
    });



    //Header Srcipts
    let hidingTimerId = null;
    $('.catalogLnk, .CatalogMenu').on("mouseover", function(e){
        window.clearTimeout(hidingTimerId);
        $('.CatalogMenu').show();
    });
    $('.catalogLnk, .CatalogMenu').on("mouseleave", function(e){
        hidingTimerId = setTimeout(() => {
            $('.CatalogMenu').hide();
        }, 200);
    });
    $('.mobBurger').on("click", function(e){
        $('.CatalogMenu').toggle();
    });

    // showCaseSlider JS start
    if($(".recomendItemSlides").length){
        var recomendItemSlides = new Flickity( '.recomendItemSlides', {
            prevNextButtons: false,
            pageDots: false
        });
        // recomendItemSlides.playPlayer();
        recomendItemSlides.on( 'change', (index) => {
            let cont = document.querySelector('.controls .slideItems .active');
            if(cont) cont.classList.toggle('active', false);
            let items = document.querySelectorAll('.controls .slideItems .item');
            items[index].classList.toggle('active', true);
            document.querySelector('.controls .counter').innerHTML = `<span>${index+1} </span> / ${items.length}`;
        } );
        document.querySelectorAll('.controls .slideItems .item').forEach(item => {
            item.addEventListener('click', e => {
                let i = Array.prototype.indexOf.call(document.querySelectorAll('.controls .slideItems .item'), e.target);
                recomendItemSlides.select( i || 0, true, false )
            });
        });
        document.querySelector('.controls .backbtn').addEventListener('click', () => {
            recomendItemSlides.previous( true, false );
        });
        document.querySelector('.controls .forwardbtn').addEventListener('click', () => {
            recomendItemSlides.next( true, false );
        });

        document.querySelectorAll('.slide .packages button').forEach(item => {
            item.addEventListener('click', e => {
                let p = e.target.dataset.price;
                let m = e.target.dataset.min;
                e.target.closest('.packageSelect').querySelector('.price span').innerHTML = p;
                e.target.closest('.slide').querySelector('.bottext.data-min').innerHTML = m;
                e.target.closest('.packages').querySelectorAll('button').forEach(item => {
                    item.classList.toggle('active', false);
                });
                e.target.classList.toggle('active', true);
            });
        });
        // showCaseSlider JS end
    }
</script>
@stack('scripts')
</body>
</html>
