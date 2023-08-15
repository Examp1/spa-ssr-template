@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="topInfo">
        <h1>{{ __('Dashboard') }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="adminLinksWrp">
                <div class="admLinkGroup">
                    <div class="admLink blue-br">
                        <a href="{{env('APP_URL')}}/admin/blog/articles"><i class="mdi mdi-newspaper"></i>
                            {{ __('Blog') }}
                        </a>
                    </div>
                    <div class="admLink blue">
                        <a href="{{env('APP_URL')}}/admin/categories"><i class="mdi mdi-format-list-bulleted"></i>
                            {{ __('Categories') }}
                        </a>
                    </div>
                    <div class="admLink yellow">
                        <a href="{{env('APP_URL')}}/admin/blog/subscribe"><i class="mdi mdi-email"></i>
                            {{ __('Subscriptions') }}
                        </a>
                    </div>
                </div>
                <div class="admLinkGroup">
                    <div class="admLink yellow">
                        <a href="{{env('APP_URL')}}/admin/settings/main"><i class="mdi mdi-settings"></i>
                            {{ __('Settings') }}
                        </a>
                    </div>
                    <div class="admLink blue">
                        <a href="{{env('APP_URL')}}/admin/settings/contacts"><i class="mdi mdi-checkbox-blank-circle-outline"></i>
                            {{ __('Contacts') }}
                        </a>
                    </div>
                    <div class="admLink blue-br">
                        <a href="#"><i class="ri-layout-6-fill"></i>
                            {{ __('Comming soon') }}
                        </a>
                    </div>
                </div>
                <div class="admLinkGroup">
                    <div class="admLink red">
                        <a href="#"><i class="mdi mdi-folder-account"></i>
                            {{ __('Comming soon') }}
                        </a>
                    </div>
                    <div class="admLink green">
                        <a href="#"><i class="fa fa-plus"></i>
                            {{ __('Comming soon') }}
                        </a>
                    </div>
                    <div class="admLink blue">
                        <a href="#"><i class="mdi mdi-book-open"></i>
                            {{ __('Comming soon') }}
                        </a>
                    </div>
                </div>
                <div class="admLinkGroup">
                    <div class="admLink blue">
                        <a href="{{env('APP_URL')}}/admins/create"><i class="fa fa-plus"></i>
                            {{ __('Create user') }}
                        </a>
                    </div>
                    <div class="admLink green">
                        <a href="#"><i class="ri-layout-6-fill"></i>
                            {{ __('Comming soon') }}
                        </a>
                    </div>
                    <div class="admLink red">
                        <a href="#"><i class="ri-layout-6-fill"></i>
                            {{ __('Comming soon') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="chart">
                <img src="../matrix/images/chart.png" alt="chart" style="width: 100%">
            </div>
            <div class="owlwebBlock">
                <h2>OwlWeb</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="adminLinksWrp">
                            <div class="admLinkGroup">
                                <div class="admLink blue-br">
                                    <a href="http://owlweb.com.ua/" target="_blank"><i class="ri-home-8-line"></i>
                                        Home
                                    </a>
                                </div>
                            </div>
                            <div class="admLinkGroup">
                                <div class="admLink blue">
                                    <a href="#" target="_blank"><i class="ri-settings-3-line"></i>
                                        About constructor
                                    </a>
                                </div>
                            </div>
                            <div class="admLinkGroup">
                                <div class="admLink yellow">
                                    <a href="https://owlweb.com.ua/services/tehnichna-pidtrimka" target="_blank"><i class="ri-questionnaire-line"></i>
                                        Support
                                    </a>
                                </div>
                            </div>
                            <div class="admLinkGroup">
                                <div class="admLink green">
                                    <a href="https://owlweb.com.ua/portfolio" target="_blank"><i class="ri-image-2-line"></i>
                                        Portfolio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="recommend gray">
                            <p class="title">
                                🔸Порекомендувати другу🔸
                            </p>
                            <p class="desc">
                                Рекомендуєш команду OwlWeb - додаєш до карми +++
                            </p>
                            <hr>
                            <a href="#" id="copyUrl" class="btnCopy"><i class="ri-file-copy-line"></i>Лінк на сайт розробника </a>
                            <input id="copyInpt" type="text"></input>
                            <div class="copy">Сopy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
