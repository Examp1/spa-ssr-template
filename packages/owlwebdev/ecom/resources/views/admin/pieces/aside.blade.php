@if(
    auth()->user()->can('categories_view')
    || auth()->user()->can('products_view')
    || auth()->user()->can('filters_view')
    || auth()->user()->can('attribute_groups_view')
    || auth()->user()->can('attributes_view')
    || auth()->user()->can('coupons_view')
    || auth()->user()->can('discounts_view')
    || auth()->user()->can('size_grids_view')
    )
        <li class="sidebar-item">
            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                aria-expanded="true">
                <i class="mdi mdi-newspaper"></i>
                <span class="hide-menu">{{ __('Catalog') }}</span>
            </a>
            <ul aria-expanded="true" class="collapse first-level in">
                @can('categories_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['categories'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{route('categories.index')}}" aria-expanded="false">
                            <i class="mdi mdi-format-list-bulleted"></i>
                            <span class="hide-menu">{{ __('Categories') }}</span>
                        </a>
                    </li>
                @endcan
                @can('products_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['filters'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{route('products.index')}}" aria-expanded="false">
                            <i class="mdi mdi-gift"></i>
                            <span class="hide-menu">{{ __('Products') }}</span>
                        </a>
                    </li>
                @endcan
                @can('filters_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['filters'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{route('filters.index')}}" aria-expanded="false">
                            <i class="mdi mdi-tag"></i>
                            <span class="hide-menu">{{ __('Filters') }}</span>
                        </a>
                    </li>
                @endcan
                @can('attribute_groups_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['attribute_groups'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{route('attribute_groups.index')}}" aria-expanded="false">
                            <i class="mdi mdi-checkbox-multiple-blank-outline"></i>
                            <span class="hide-menu">{{ __('Attribute groups') }}</span>
                        </a>
                    </li>
                @endcan
                @can('attributes_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['attributes'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{route('attributes.index')}}" aria-expanded="false">
                            <i class="mdi mdi-checkbox-marked-outline"></i>
                            <span class="hide-menu">{{ __('Attributes') }}</span>
                        </a>
                    </li>
                @endcan
                @can('coupons_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['coupons'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{route('coupons.index')}}" aria-expanded="false">
                            <i class="mdi mdi-ticket-percent"></i>
                            <span class="hide-menu">{{ __('Coupons') }}</span>
                        </a>
                    </li>
                @endcan
                @can('discounts_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['discounts'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{route('discounts.index')}}" aria-expanded="false">
                            <i class="mdi mdi-ticket-account"></i>
                            <span class="hide-menu">{{ __('Discounts') }}</span>
                        </a>
                    </li>
                @endcan
                @can('size_grids_view')
                    <li class="sidebar-item @if(in_array(Request::segment(2),['size-grid'])) active @endif">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                           href="{{route('size-grid.index')}}" aria-expanded="false">
                            <i class="mdi mdi-grid"></i>
                            <span class="hide-menu">{{ __('Size grids') }}</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endif

    @can('orders_view')
        <li class="sidebar-item @if(in_array(Request::segment(3),['orders'])) active @endif">
            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                href="{{route('orders.index')}}"
                aria-expanded="false">
                <i class="mdi mdi-cart"></i>
                <span class="hide-menu">{{ __('Orders') }}</span>
            </a>
        </li>
        <li class="sidebar-item @if(in_array(Request::segment(3),['orders-stats'])) active @endif">
            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                href="{{route('orders.stats')}}"
                aria-expanded="false">
                <i class="mdi mdi-chart-line"></i>
                <span class="hide-menu">{{ __('Orders statistics') }}</span>
            </a>
        </li>
    @endcan

    @can('users_view')
        <li class="sidebar-item @if(in_array(Request::segment(3),['users'])) active @endif">
            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                href="{{route('users.index')}}"
                aria-expanded="false">
                <i class="mdi mdi-account-multiple"></i>
                <span class="hide-menu">{{ __('Users') }}</span>
            </a>
        </li>
    @endcan

    @can('reviews_view')
        <li class="sidebar-item @if(in_array(Request::segment(3),['reviews'])) active @endif">
            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                href="{{route('reviews.index')}}"
                aria-expanded="false">
                <i class="mdi mdi-comment"></i>
                <span class="hide-menu">{{ __('Reviews') }}</span>
            </a>
        </li>
    @endcan

    @can('search_history_view')
        <li class="sidebar-item @if(in_array(Request::segment(2),['search-history'])) active @endif">
            <a class="sidebar-link waves-effect waves-dark sidebar-link"
               href="{{route('search-history.index')}}" aria-expanded="false">
                <i class="mdi mdi-search-web"></i>
                <span class="hide-menu">{{ __('Search history') }}</span>
            </a>
        </li>
    @endcan
