{{-- Catalog --}}
@if (isset($asiderSections['info']['blocks']['category_id']['show']) && $asiderSections['info']['blocks']['category_id']['show'] !== false)
    @php($category_field = $asiderSections['info']['blocks']['category_id']['field_name'])
    <div class="section_item">
        <div class="input-group">
            <label>{{ __($asiderSections['info']['blocks']['category_id']['title']) }}</label>
            <select name="{{ $category_field }}" class="select2" required
                style="width: 100%">
                <option value="">---</option>
                {!!\Owlwebdev\Ecom\Models\Category::getOptionsHTML($model->category_id) !!}
            </select>
        </div>
    </div>
@endif

@if (isset($asiderSections['info']['blocks']['product_categories']['show']) && $asiderSections['info']['blocks']['product_categories']['show'] !== false)
    @php($categories_field = $asiderSections['info']['blocks']['product_categories']['field_name'])
    <div class="section_item">
        <div class="input-group">
            <label>{{ __($asiderSections['info']['blocks']['product_categories']['title']) }}</label>
            <select name="{{ $categories_field }}" multiple="multiple" class="select2"
                style="width: 100%">
                {!!\Owlwebdev\Ecom\Models\Category::getOptionsHTML($model->categories->pluck('id')->toArray()) !!}
            </select>
        </div>
    </div>
@endif

@if (isset($asiderSections['info']['blocks']['parent_id']['show']) && $asiderSections['info']['blocks']['parent_id']['show'] !== false)
    @php($parent_category_field = $asiderSections['info']['blocks']['parent_id']['field_name'])
    <div class="section_item">
        <div class="input-group">
            <label>{{ __($asiderSections['info']['blocks']['parent_id']['title']) }}</label>
            <select name="{{ $parent_category_field }}">
                <option value="0">{{ __('No category') }}</option>
                @include('ecom::admin.categories.node')
            </select>
        </div>
    </div>
@endif

@if (isset($asiderSections['info']['blocks']['product_guide']['show']) && $asiderSections['info']['blocks']['product_guide']['show'] !== false)
    @php($field = $asiderSections['info']['blocks']['product_guide']['field_name'])
    <div class="section_item">
        <div class="input-group">
            <label>{{ __($asiderSections['info']['blocks']['product_guide']['title']) }}</label>
            <select name="{{ $field }}">
                <option value="0">---</option>
                @foreach (\App\Models\Pages::query()->get() as $item)
                    <option value="{{$item->id}}" @if (old($field, $model->$field ?? '') == $item->id) selected @endif>{{ $item->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

@if (isset($asiderSections['info']['blocks']['attribute_group_id']['show']) &&
        $asiderSections['info']['blocks']['attribute_group_id']['show'] !== false)
    @php($field_name = $asiderSections['info']['blocks']['attribute_group_id']['field_name'])
    @php($groups = \Owlwebdev\Ecom\Models\AttributeGroup::all()->pluck('name', 'id'))
    <div class="section_item">
        <div class="input-group">
            <label>{{ __($asiderSections['info']['blocks']['attribute_group_id']['title']) }}</label>
            <select name="{{ $field_name }}">
                <option value="0">---</option>
                @foreach ($groups as $group_id => $group_name)
                    <option value="{{ $group_id }}" @if (old($field_name, $model->$field_name ?? '') == $group_id) selected @endif>
                        {{ $group_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif

@if (isset($asiderSections['info']['blocks']['size_grid_id']['show']) && $asiderSections['info']['blocks']['size_grid_id']['show'] !== false)
    @php($field = $asiderSections['info']['blocks']['size_grid_id']['field_name'])
    <div class="section_item">
        <div class="input-group">
            <label>{{ __($asiderSections['info']['blocks']['size_grid_id']['title']) }}</label>
            <select name="{{ $field }}">
                <option value="0">---</option>
                @foreach(\Owlwebdev\Ecom\Models\SizeGrid::query()->get() as $item)
                    <option value="{{$item->id}}" @if (old($field, $model->$field ?? '') == $item->id) selected @endif>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

@php($fields = ['code', 'quantity', 'price', 'old_price', 'cost', 'currency'])
@foreach ($fields as $field)
    @if (isset($asiderSections['info']['blocks'][$field]['show']) && $asiderSections['info']['blocks'][$field]['show'] !== false)
        @php($field_name = $asiderSections['info']['blocks'][$field]['field_name'])
        @php($type = $asiderSections['info']['blocks'][$field]['type'] ?? 'text')
        @php($title = __($asiderSections['info']['blocks'][$field]['title']) ?? '')
        @php($list = $asiderSections['info']['blocks'][$field]['list'] ?? [])

        <div class="section_item">
            <div class="input-group">
                <label>{{ $title }}</label>
                @switch($type)
                    @case('number')
                        <input type="number" name="{{ $field_name }}"
                            value="{{ old($field_name, $model->$field_name ?? '') }}">
                    @break

                    @case('text')
                        <input type="text" name="{{ $field_name }}"
                            value="{{ old($field_name, $model->$field_name ?? '') }}">
                    @break

                    @case('currency')
                        <select name="{{ $field_name }}">
                            @foreach (\Owlwebdev\Ecom\Models\Cart::getCurrencies() as $code => $currency)
                                <option value="{{ $code }}"
                                    @if (old($field_name, $model->$field_name ?? config('app.currency')) == $code) selected @endif>{{ $code }}
                                </option>
                            @endforeach
                        </select>
                    @break
                @endswitch
            </div>
        </div>

        @if ($errors->has($field_name))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($field_name) }}</strong>
            </span>
        @endif
    @endif
@endforeach
