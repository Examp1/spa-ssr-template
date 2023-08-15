<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#pills_main_{{ $key }}_{{$lang}}" title="Головне">
            <i class="fa fa-list"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#pills_setting_{{ $key }}_{{$lang}}" title="Налаштування">
            <i class="fa fa-cog"></i>
        </a>
    </li>
    @isset($btns_hide)
    @else
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#pills_btns_{{ $key }}_{{$lang}}" title="Кнопки">
                <i class="fa fa-square"></i>
            </a>
        </li>
    @endif
</ul>
