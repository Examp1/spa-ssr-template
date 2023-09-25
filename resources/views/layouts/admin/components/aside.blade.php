<?php

use Illuminate\Support\Facades\Request;

?>

<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-4">
                <li class="sidebar-item @if(in_array(Request::segment(1),['admin']) && Request::segment(2) == '') active @endif">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin')}}"
                       aria-expanded="false">
                        <i class="mdi mdi-blur-linear"></i>
                        <span class="hide-menu">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                @if(auth()->user()->can('admins_view') || auth()->user()->can('roles_view') || auth()->user()->can('permission_groups_view') || auth()->user()->can('permissions_view'))
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                           aria-expanded="false">
                            <i class="mdi mdi-account"></i>
                            <span class="hide-menu">{{ __('Admins') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            @can('admins_view')
                                <li class="sidebar-item @if(in_array(Request::segment(2),['admins'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('admins.index')}}" aria-expanded="false">
                                        <i class="mdi mdi-account"></i>
                                        <span class="hide-menu">{{ __('Admins') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('roles_view')
                                <li class="sidebar-item @if(in_array(Request::segment(2),['roles'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('roles.index')}}" aria-expanded="false">
                                        <i class="mdi mdi-account-multiple"></i>
                                        <span class="hide-menu">{{ __('Roles') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('permission_groups_view')
                                <li class="sidebar-item @if(in_array(Request::segment(3),['permission-groups'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('permission-groups.index')}}" aria-expanded="false">
                                        <i class="fa fa-list-alt"></i>
                                        <span class="hide-menu">{{ __('Permissions Groups') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('permissions_view')
                                <li class="sidebar-item @if(in_array(Request::segment(3),['permissions'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('permissions.index')}}" aria-expanded="false">
                                        <i class="fa fa-check-circle"></i>
                                        <span class="hide-menu">{{ __('Permissions') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @can('pages_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['pages'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('pages.index')}}"
                           aria-expanded="false">
                            <i class="mdi mdi-book-open-page-variant"></i>
                            <span class="hide-menu">{{ __('Pages') }}</span>
                        </a>
                    </li>
                @endcan

                <li class="sidebar-item @if(in_array(Request::segment(2),['landings'])) active @endif">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('landing.index')}}"
                       aria-expanded="false">
                        <i class="mdi mdi-book-open-page-variant"></i>
                        <span class="hide-menu">Лендінги</span>
                    </a>
                </li>

                @if(
                auth()->user()->can('blog_articles_view')
                || auth()->user()->can('blog_tags_view')
                || auth()->user()->can('blog_subscribe_view')
                || auth()->user()->can('blog_category_view')
                )
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                           aria-expanded="false">
                            <i class="mdi mdi-newspaper"></i>
                            <span class="hide-menu">{{ __('Blog') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            @can('blog_articles_view')
                                <li class="sidebar-item @if(in_array(Request::segment(3),['articles'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('articles.index')}}" aria-expanded="false">
                                        <i class="mdi mdi-message-outline"></i>
                                        <span class="hide-menu">{{ __('Articles') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('blog_category_view')
                                <li class="sidebar-item @if(in_array(Request::segment(3),['categories'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('blog.categories.index')}}" aria-expanded="false">
                                        <i class="mdi mdi-format-list-bulleted"></i>
                                        <span class="hide-menu">{{ __('Categories') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('blog_tags_view')
                                <li class="sidebar-item @if(in_array(Request::segment(3),['tags'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('blog.tags.index')}}" aria-expanded="false">
                                        <i class="mdi mdi-tag"></i>
                                        <span class="hide-menu">{{ __('Tags') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('blog_subscribe_view')
                                <li class="sidebar-item @if(in_array(Request::segment(3),['subscribe'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('subscribe.index')}}" aria-expanded="false">
                                        <i class="mdi mdi-email"></i>
                                        <span class="hide-menu">{{ __('Subscriptions') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('widgets_view') || auth()->user()->can('menu_view'))
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                           aria-expanded="false">
                            <i class="mdi mdi-format-paint"></i>
                            <span class="hide-menu">{{ __('Appearance') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            @can('widgets_view')
                                <li class="sidebar-item @if(in_array(Request::segment(2),['widgets'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/widgets?lang={{config('translatable.locale')}}"
                                       aria-expanded="false">
                                        <i class="mdi mdi-widgets"></i>
                                        <span class="hide-menu">{{ __('Widgets') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('forms_view')
                                <li class="sidebar-item @if(in_array(Request::segment(2),['forms'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/forms?lang={{config('translatable.locale')}}"
                                       aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu">{{ __('Forms') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('menu_view')
                                <li class="sidebar-item @if(in_array(Request::segment(2),['menu'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="{{route('menu.index')}}" aria-expanded="false">
                                        <i class="mdi mdi-menu"></i>
                                        <span class="hide-menu">{{ __('Menu') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @can('multimedia_view')
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="mdi mdi-image-album"></i>
                            <span class="hide-menu">{{ __('Multimedia') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item @if(in_array(Request::segment(3),['images'])) active @endif">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('multimedia.images')}}" aria-expanded="false">
                                    <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                    <span class="hide-menu">{{ __('Images') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item @if(in_array(Request::segment(3),['files'])) active @endif">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('multimedia.files')}}" aria-expanded="false">
                                    <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                    <span class="hide-menu">{{ __('Files') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @if(
                auth()->user()->can('setting_main_view')
                || auth()->user()->can('setting_contacts_view')
                || auth()->user()->can('setting_blog_view')
                || auth()->user()->can('setting_page_view')
                || auth()->user()->can('setting_landing_view')
                || auth()->user()->can('setting_blogcategories_view')
                || auth()->user()->can('setting_blogtags_view')
                || auth()->user()->can('setting_theme_view')
                )
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                           aria-expanded="false">
                            <i class="mdi mdi-settings"></i>
                            <span class="hide-menu">{{ __('Settings') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            @can('setting_main_view')
                                <li class="sidebar-item @if(in_array(Request::segment(4),['main'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/settings/main"
                                       aria-expanded="false">
                                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        <span class="hide-menu">{{ __('General') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('setting_contacts_view')
                                <li class="sidebar-item @if(in_array(Request::segment(4),['contacts'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/settings/contacts" aria-expanded="false">
                                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        <span class="hide-menu">{{ __('Contacts') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('setting_blog_view')
                                <li class="sidebar-item @if(in_array(Request::segment(4),['blog'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/settings/blog"
                                       aria-expanded="false">
                                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        <span class="hide-menu">{{ __('Blog articles') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('setting_blogcategories_view')
                                <li class="sidebar-item @if(in_array(Request::segment(4),['blogcategories'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/settings/blogcategories"
                                       aria-expanded="false">
                                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        <span class="hide-menu">{{ __('Blog Categories') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('setting_blogtags_view')
                                <li class="sidebar-item @if(in_array(Request::segment(4),['blogtags'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/settings/blogtags"
                                       aria-expanded="false">
                                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        <span class="hide-menu">{{ __('Blog Tags') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('setting_page_view')
                                <li class="sidebar-item @if(in_array(Request::segment(4),['page'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/settings/page"
                                       aria-expanded="false">
                                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        <span class="hide-menu">{{ __('Pages') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('setting_theme_view')
                                <li class="sidebar-item @if(in_array(Request::segment(4),['theme'])) active @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                       href="/admin/settings/theme"
                                       aria-expanded="false">
                                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        <span class="hide-menu">{{ __('Theme') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                <li class="sidebar-item @if(in_array(Request::segment(2),['mailgun-test'])) active @endif">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('mailgun-test.index')}}"
                       aria-expanded="false">
                        <i class="mdi mdi-mailbox"></i>
                        <span class="hide-menu">Mailgun test</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
