<?php
$attrNames = \Owlwebdev\Ecom\Models\Translations\AttributeTranslation::query()
    ->where('lang', app()->getLocale())
    ->pluck('name', 'attribute_id')
    ->toArray();

$selected = \Owlwebdev\Ecom\Models\ProductAttributes::query()
    ->where('product_id', $model->id)
    ->get();

$existAttrIds = [];
?>

@foreach ($model->categories as $key => $category)
    @if (!$category->attributeGroup)
        @continue
    @endif

    @foreach ($category->attributeGroup->attributes as $attr)
        @if (!in_array($attr->id, $existAttrIds))
            <div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label>{{ $attrNames[$attr->id] }}</label>
                    </div>
                </div>
            </div>

            <div class="attr-container attr-containerNew">

                <?php
                $selected_in_group = $selected->where('attribute_id', $attr->id)->groupBy('group');
                $total = $selected_in_group->count();
                $col = 0;
                ?>

                @foreach ($selected_in_group as $group => $group_items)
                    <div class="form-group row attr-element">
                        @foreach ($localizations as $key => $lang)
                            <?php
                            $sel = $group_items->where('lang', $key)->first();
                            ?>
                            @if ($sel)
                                <div class="col-md-4 mb-1">
                                    <div class="input-group {{ $attr->type }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><img
                                                    src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                    alt="{{ $key }}"></span>
                                        </div>
                                        @switch($attr->type)
                                            @case('color')
                                                <input type="color" name="attr[{{ $attr->id }}][val][]"
                                                    placeholder="{{ __('Color') }}" title="{{ __('Color') }}"
                                                    data-title="{{ __('Color') }}" data-original-title="{{ __('Color') }}"
                                                    data-lang="{{ $key }}" data-attribute_id="{{ $attr->id }}"
                                                    class="form-control tip" value="{{ $sel->value }}"
                                                    style="min-width: 4em;">
                                            @break

                                            @case('image')
                                            <input type="text" name="attr[{{ $attr->id }}][alt][]"
                                                placeholder="{{ __('name') }}" value="{{ $sel->alt }}"
                                                class="form-control">
                                                {{ media_preview_box('attr[' . $attr->id . '][val][]', $sel->value ?? null, $errors ?? null) }}
                                            @break

                                            <br>

                                            @default
                                                <input type="text" name="attr[{{ $attr->id }}][val][]"
                                                    value="{{ $sel->value }}" class="form-control with-hint-list"
                                                    list="autocompleteList" data-lang="{{ $key }}"
                                                    data-attribute_id="{{ $attr->id }}" autoComplete="off">
                                        @endswitch
                                    </div>

                                    <input type="hidden" name="attr[{{ $attr->id }}][id][]" class="form-control"
                                        value="{{ $attr->id }}">
                                    <input type="hidden" name="attr[{{ $attr->id }}][lang][]" class="form-control"
                                        value="{{ $key }}">
                                    <input type="hidden" name="attr[{{ $attr->id }}][group][]"
                                        class="form-control group_value" value="{{ $group }}">
                                </div>
                            @else
                                <div class="col-md-4 mb-1">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><img
                                                    src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                    alt="{{ $key }}"></span>
                                        </div>

                                        @switch($attr->type)
                                            @case('color')
                                                <input type="color" name="attr[{{ $attr->id }}][val][]"
                                                    placeholder="{{ __('Color') }}" title="{{ __('Color') }}"
                                                    data-title="{{ __('Color') }}" data-original-title="{{ __('Color') }}"
                                                    data-lang="{{ $key }}" data-attribute_id="{{ $attr->id }}"
                                                    class="form-control tip" style="min-width: 4em;">
                                            @break

                                            @case('image')
                                                {{ media_preview_box('attr[' . $attr->id . '][val][]', null, $errors ?? null) }}
                                                <input type="text" name="attr[{{ $attr->id }}][alt][]"
                                                    placeholder="{{ __('name') }}" class="form-control mt-3 mb-1 ml-1">
                                            @break

                                            @default
                                                <input type="text" name="attr[{{ $attr->id }}][val][]"
                                                    class="form-control with-hint-list" list="autocompleteList"
                                                    data-lang="{{ $key }}" data-attribute_id="{{ $attr->id }}"
                                                    autoComplete="off" placeholder="Почніть вводити значення"
                                                    autoComplete="off">
                                        @endswitch
                                    </div>

                                    <input type="hidden" name="attr[{{ $attr->id }}][id][]" class="form-control"
                                        value="{{ $attr->id }}">
                                    <input type="hidden" name="attr[{{ $attr->id }}][lang][]" class="form-control"
                                        value="{{ $key }}">
                                    <input type="hidden" name="attr[{{ $attr->id }}][group][]"
                                        class="form-control group_value" value="{{ $group }}">
                                </div>
                            @endif
                        @endforeach

                        <?php
                        $col++;
                        ?>

                        <div class="col-md-4 mb-1 btns-group">
                            @if ($col !== 1)
                                <span class="btn btn-danger text-white remove-attr-element">
                                    <i class="mdi mdi-minus"></i>
                                </span>
                            @endif
                            @if ($total == $col)
                                <span class="btn btn-primary add-attr-element">
                                    <i class="mdi mdi-plus"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if ($selected_in_group->isEmpty())
                    <div class="form-group row attr-element">
                        @foreach ($localizations as $key => $lang)
                            <div class="col-md-4 mb-1">
                                <div class="input-group {{ $attr->type }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><img src="/images/langs/{{ $key }}.jpg"
                                                style="width: 20px" alt="{{ $key }}"></span>
                                    </div>

                                    @switch($attr->type)
                                        @case('color')
                                            <input type="color" name="attr[{{ $attr->id }}][val][]"
                                                placeholder="{{ __('Color') }}" title="{{ __('Color') }}"
                                                data-title="{{ __('Color') }}" data-original-title="{{ __('Color') }}"
                                                data-lang="{{ $key }}" data-attribute_id="{{ $attr->id }}"
                                                class="form-control tip" style="min-width: 4em;">
                                        @break

                                        @case('image')
                                        <input type="text" name="attr[{{ $attr->id }}][alt][]"
                                        placeholder="{{ __('name') }}" class="form-control">
                                        {{ media_preview_box('attr[' . $attr->id . '][val][]', null, $errors ?? null) }}
                                        @break

                                        @default
                                            <input type="text" name="attr[{{ $attr->id }}][val][]"
                                                class="form-control with-hint-list" list="autocompleteList"
                                                data-lang="{{ $key }}" data-attribute_id="{{ $attr->id }}"
                                                autoComplete="off" placeholder="Почніть вводити значення" autoComplete="off">
                                    @endswitch
                                </div>

                                <input type="hidden" name="attr[{{ $attr->id }}][id][]" class="form-control"
                                    value="{{ $attr->id }}">
                                <input type="hidden" name="attr[{{ $attr->id }}][lang][]" class="form-control"
                                    value="{{ $key }}">
                                <input type="hidden" name="attr[{{ $attr->id }}][group][]" class="form-control"
                                    value="null">
                            </div>
                        @endforeach

                        <div class="col-md-4 mb-1 btns-group">
                            <span class="btn btn-primary add-attr-element">
                                <i class="mdi mdi-plus"></i>
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <?php $existAttrIds[] = $attr->id; ?>
    @endforeach
@endforeach

<datalist id="autocompleteList">
</datalist>

@push('scripts')
    <script>
        $(document).on("focus click", "input.with-hint-list", function(e) {
            $("#autocompleteList").empty();
        });
        $(document).on("input", ".with-hint-list", function(e) {
            var _this = $(this);
            var input = _this.val();

            if (input.length >= 1) {
                $.ajax({
                    dataType: "json",
                    type: 'GET',
                    async: true,
                    url: '/admin/attributes/' + _this.data('attribute_id') + '/' + _this.data('lang') +
                        '/values',
                    success: function(data) {
                        $("#autocompleteList").empty();
                        for (i = 0; i < data.length; i++) {
                            $("#autocompleteList").append('<option value="' + data[i] + '">' + data[i] +
                                '</option>');
                        }
                    }
                });
            }
        });
    </script>
@endpush
