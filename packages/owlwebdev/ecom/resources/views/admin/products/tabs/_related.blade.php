<ul class="nav nav-tabs">
    @foreach ($relations as $key => $rel)
        <li class="nav-item">
            <a class="nav-link @if ($key == 'one') active @endif" data-toggle="tab"
                href="#relation_{{ $key }}">
                <span class="hidden-sm-up"></span> <span class="hidden-xs-down">{{ $rel['name'] }}</span>
            </a>
        </li>
    @endforeach
</ul>

<br>
<div class="tab-content">
    @foreach ($relations as $key => $rel)
        <div class="tab-pane p-t-20 p-b-20 @if ($key == 'one') active @endif"
            id="relation_{{ $key }}">
            @include('ecom::admin.products._related_item', [
                'field' => $rel['field'],
                'selected' => $rel['selected_related'],
                'products' => $products,
            ])

            <span data-template="{{ $key }}_template"
                class="btn btn-success text-white btn-xs add-related-element" data-product_id="{{ $model->id }}">
                <i class="mdi mdi-plus"></i>
                {{ __('Add') }}
            </span>

        </div>
    @endforeach
</div>


@push('scripts')
    <script>
        $(document).on('click', '.add-related-element', function() {
            let $container = $(this).parent().find('.related-container');
            let $cloneElem = $(this).parent().find('template');

            $container.append($cloneElem.html());
            $(".select2").select2();
        });

        $(document).on('click', '.remove-related-item', function() {
            $(this).closest('.form-group').remove();
        });
    </script>
@endpush
