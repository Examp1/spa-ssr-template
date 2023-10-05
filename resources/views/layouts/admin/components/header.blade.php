<header class="topbar" data-navbarbg="skin5">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="{{route('admin')}}">
                <!-- Logo icon -->
                <b class="logo-icon ps-2">
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="{{ asset('/images/logo-icon.png') }}" alt="homepage" class="light-logo" />

                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                    <!-- dark Logo text -->
                    @php($logo = app(Setting::class)->get('logotype_admin',config('translatable.locale')))
                    @if($logo)
                        <img src="{{get_image_uri($logo)}}" style="max-width: 105px;max-height: 30px;" alt="homepage" class="light-logo" />
                    @endif

                        </span>
                <!-- Logo icon -->
                <!-- <b class="logo-icon"> -->
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
                {{-- <img src="../../assets/images/chart.png" alt="homepage" class="light-logo" /> --}}

                <!-- </b> -->
                <!--End Logo icon -->
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-start me-auto">
                <li class="nav-item d-none d-lg-block"><a
                        class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                        data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                <!-- ============================================================== -->
                <!-- create new -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <span class="d-none d-md-block">{{ __('Add') }} <i class="fa fa-angle-down"></i></span>
                        <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @can('pages_create')
                        <li><a class="dropdown-item" href="{{route('pages.create')}}">{{ __('Add Page') }}</a></li>
                        @endcan
                        @can('blog_articles_create')
                        <li><a class="dropdown-item" href="{{route('articles.create')}}">{{ __('Add Article') }}</a></li>
                        @endcan
{{--                        <li><hr class="dropdown-divider"></li>--}}
                    </ul>
                </li>
                <!-- ============================================================== -->
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-end">
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
{{--                <li class="nav-item dropdown">--}}
{{--                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">--}}
{{--                        <i class="mdi mdi-bell font-24"></i>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
{{--                        <li><a class="dropdown-item" href="#">Action</a></li>--}}
{{--                        <li><a class="dropdown-item" href="#">Another action</a></li>--}}
{{--                        <li><hr class="dropdown-divider"></li>--}}
{{--                        <li><a class="dropdown-item" href="#">Something else here</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Messages -->
                <!-- ============================================================== -->
{{--                <li class="nav-item dropdown">--}}
{{--                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" id="2" role="button" data-toggle="dropdown" aria-expanded="false">--}}
{{--                        <i class="font-24 mdi mdi-comment-processing"></i>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu dropdown-menu-end mailbox animated bounceInDown" aria-labelledby="2">--}}
{{--                        <ul class="list-style-none">--}}
{{--                            <li>--}}
{{--                                <div class="">--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="link border-top">--}}
{{--                                        <div class="d-flex no-block align-items-center p-10">--}}
{{--                                                    <span class="btn btn-success btn-circle"><i--}}
{{--                                                            class="ti-calendar"></i></span>--}}
{{--                                            <div class="ms-2">--}}
{{--                                                <h5 class="mb-0">Event today</h5>--}}
{{--                                                <span class="mail-desc">Just a reminder that event</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="link border-top">--}}
{{--                                        <div class="d-flex no-block align-items-center p-10">--}}
{{--                                                    <span class="btn btn-info btn-circle"><i--}}
{{--                                                            class="ti-settings"></i></span>--}}
{{--                                            <div class="ms-2">--}}
{{--                                                <h5 class="mb-0">Settings</h5>--}}
{{--                                                <span class="mail-desc">You can customize this template</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="link border-top">--}}
{{--                                        <div class="d-flex no-block align-items-center p-10">--}}
{{--                                                    <span class="btn btn-primary btn-circle"><i--}}
{{--                                                            class="ti-user"></i></span>--}}
{{--                                            <div class="ms-2">--}}
{{--                                                <h5 class="mb-0">Pavan kumar</h5>--}}
{{--                                                <span class="mail-desc">Just see the my admin!</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="link border-top">--}}
{{--                                        <div class="d-flex no-block align-items-center p-10">--}}
{{--                                                    <span class="btn btn-danger btn-circle"><i--}}
{{--                                                            class="fa fa-link"></i></span>--}}
{{--                                            <div class="ms-2">--}}
{{--                                                <h5 class="mb-0">Luanch Admin</h5>--}}
{{--                                                <span class="mail-desc">Just see the my new admin!</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <!-- ============================================================== -->
                <!-- End Messages -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item d-none d-lg-block" title="{{ __('Clear cache') }}">
                    <a class="nav-link waves-effect waves-light" title="Скинути кеш" href="{{route('cache-clear')}}">
                        <i class="mdi mdi-cached font-24"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link waves-effect waves-light" title="Перейти на сайт" href="/" target="_blank">
                        <i class="mdi mdi-eye font-24"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <?php
                    $user = \App\Models\Admin::query()->with('roles')->where('id',\Illuminate\Support\Facades\Auth::user()->id)->first();
                    $arr = explode(' ',$user->name);
                    $fio = '';
                    foreach ($arr as $key => $value)
                    {
                        mb_internal_encoding("UTF-8");
                        $fio .= mb_strtoupper(mb_substr(trim($value),0,1));
                    }
                    ?>
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <div>
                            <span class="user-name">{{$user->name}}</span>
                            {{-- <span class="user-position">{{$user->roles[0]->name}}</span> --}}
                        </div>
                        <span class="logo">{{$fio}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/admin/admins/{{\Illuminate\Support\Facades\Auth::id()}}/edit"><i class="ti-user me-1 ms-1"></i>
                            {{ __('My profile') }}</a>
{{--                        <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet me-1 ms-1"></i>--}}
{{--                            My Balance</a>--}}
{{--                        <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email me-1 ms-1"></i>--}}
{{--                            Inbox</a>--}}
{{--                        <div class="dropdown-divider"></div>--}}
{{--                        <a class="dropdown-item" href="javascript:void(0)"><i--}}
{{--                                class="ti-settings me-1 ms-1"></i> Account Setting</a>--}}
{{--                        <div class="dropdown-divider"></div>--}}
                        <a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                class="fa fa-power-off me-1 ms-1"></i> {{ __('Exit') }}</a>
                        <div class="dropdown-divider"></div>
                    </ul>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
    </nav>
</header>

<style>
    .pro-pic {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pro-pic span.logo {
        display: flex;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rosybrown;
        color: white;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .pro-pic div {
        margin-left: 10px;
    }
    .pro-pic .user-name {
        display: block;
        line-height: 20px;
        color: white;
    }
    .pro-pic .user-position {
        display: block;
        line-height: 20px;
        color: #e3e3e3;
    }
</style>
