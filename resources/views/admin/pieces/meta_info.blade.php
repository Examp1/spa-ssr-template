<div style="display: flex; gap: 5px">
    @switch($status)
        @case('0')
            <span class="fa fa-times" style="color: #da542e; font-size: 16px" title="Данные не заполнены"></span>
        @break
        @case('1')
            <span class="fa fa-pencil" style="color: #28b779; font-size: 16px" title="Данные заполнены вручную"></span>
        @break
        @case('2')
            <span class="fa fa-android" style="color: #28b779; font-size: 16px" title="Данные сгенерированы"></span>
        @break
    @endswitch
    @if($mAutoGen)
        <span class="fa fa-magic" style="color: #28b779; font-size: 16px" title="Автогенерация разрешена"></span>
    @else
        <span class="fa fa-magic" style="color: #da542e; font-size: 16px" title="Автогенерация запрещена"></span>
    @endif
</div>
