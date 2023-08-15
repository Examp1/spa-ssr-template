@extends('layouts.app')

@section('content')
    {{-- Contacts page --}}
    @if ($page->id == 29)
        <section class="contactSection">
            <div class="container">
                <h1>{{ $page->getTranslation(app()->getLocale())->title }}</h1>
                <div class="twocolDiv">
                    <div>
                        <div class="map" id="map"></div>
                    </div>
                    <div>
                        <div class="contactinfo">
                            <div>
                                <div class="contactItem">
                                    <div class="label">{{ __('Phone') }}</div>
                                    <?php $phones = json_decode(app(Setting::class)->get('phones'), true); ?>
                                    <a href="tel:{{ $phones[1]['number'] }}"
                                        class="field">{{ $phones[1]['number'] }}</a>
                                </div>
                                <div class="contactItem">
                                    <div class="label">{{ __('email') }}</div>
                                    <div class="field">{{ app(Setting::class)->get('email') }}</div>
                                </div>
                                <div class="contactItem">
                                    <div class="label">{{ __('Address') }}</div>
                                    <div class="field">{{ app(Setting::class)->get('address') }}</div>
                                </div>
                            </div>
                            <div>
                                <?php $schedules = json_decode(app(Setting::class)->get('schedules'), true); ?>
                                <div class="contactTextItem">{{ __('Working hours:') }}</div>
                                @if ($schedules && count($schedules))
                                    @foreach ($schedules as $item)
                                        <div class="contactTextItem">{{ $item['label'] }} @if (!empty($item['time']))<br>{{ $item['time'] }} @endif</div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    @if (!in_array($page->id, [30, 5]))
        <section class="payAndShipping">
            <div class="container">
                @if(isset($page->getTranslation(app()->getLocale())->title) && !in_array($page->id,[30,5]))
                <h1>{{ $page->getTranslation(app()->getLocale())->title }}</h1>
    @endif

    <div class="articleDiv">
        @if ($page->getTranslation(app()->getLocale())->description !== '<p><br></p>')
            {!! $page->getTranslation(app()->getLocale())->description !!}
        @endif

        @include('front.site.layouts.includes.constructor', ['constructor' =>
        $page->getTranslation(app()->getLocale())->constructor->data, 'catalog' => 'pages::site.components.'])
    </div>
    </div>
    </section>
@else
    @if ($page->getTranslation(app()->getLocale())->description !== '<p><br></p>')
        {!! $page->getTranslation(app()->getLocale())->description !!}
    @endif

    @include('front.site.layouts.includes.constructor', ['constructor' =>
    $page->getTranslation(app()->getLocale())->constructor->data, 'catalog' => 'pages::site.components.'])
    @endif


@endsection

@push('scripts')
    @if ($page->id == 29)
        <script
                src="https://maps.googleapis.com/maps/api/js?key={{ app(Setting::class)->get('maps_api_key', config('app.locale')) }}">
        </script>
        <script>
            function setMarkers(map, locations) {
                //Определяем область показа маркеров
                var latlngbounds = new google.maps.LatLngBounds();
                for (var i = 0; i < places.length; i++) {
                    var myLatLng = new google.maps.LatLng(locations[i][1], locations[i][2]);
                    //Добавляем координаты маркера в область
                    latlngbounds.extend(myLatLng);
                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                        title: locations[i][0],
                    });
                }
                //Центрируем и масштабируем карту
                //map.setCenter( latlngbounds.getCenter(), map.fitBounds(latlngbounds));
            };

            var places = [
                ["", {{ app(Setting::class)->get('maps_coord_lat', config('app.locale')) }},
                    {{ app(Setting::class)->get('maps_coord_lng', config('app.locale')) }}
                ]
            ];

            function initMap() {
                var latlng = new google.maps.LatLng({{ app(Setting::class)->get('maps_coord_lat', config('app.locale')) }},
                    {{ app(Setting::class)->get('maps_coord_lng', config('app.locale')) }});
                var myOptions = {
                    zoom: 17,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById("map"),
                    myOptions);

                setMarkers(map, places);
            }

            $(document).ready(function() {
                initMap();
            });
        </script>
    @endif
@endpush
