@can('setting_categories_view')
    <li class="sidebar-item @if(in_array(Request::segment(4),['categories'])) active @endif">
        <a class="sidebar-link waves-effect waves-dark sidebar-link"
            href="/admin/settings/categories"
            aria-expanded="false">
            <i class="mdi mdi-checkbox-blank-circle-outline"></i>
            <span class="hide-menu">Категорії продуктів</span>
        </a>
    </li>
@endcan
@can('setting_products_view')
    <li class="sidebar-item @if(in_array(Request::segment(4),['products'])) active @endif">
        <a class="sidebar-link waves-effect waves-dark sidebar-link"
            href="/admin/settings/products"
            aria-expanded="false">
            <i class="mdi mdi-checkbox-blank-circle-outline"></i>
            <span class="hide-menu">{{ __('Products') }}</span>
        </a>
    </li>
@endcan
@can('setting_checkout_view')
    <li class="sidebar-item @if(in_array(Request::segment(4),['checkout'])) active @endif">
        <a class="sidebar-link waves-effect waves-dark sidebar-link"
            href="/admin/settings/checkout"
            aria-expanded="false">
            <i class="mdi mdi-cart"></i>
            <span class="hide-menu">{{ __('Checkout') }}</span>
        </a>
    </li>
@endcan
