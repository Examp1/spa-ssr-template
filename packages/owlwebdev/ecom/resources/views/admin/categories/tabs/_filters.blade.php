<?php
// dd($model->filters);
?>
<div class="filters-container row">
    @foreach ($model->filters as $key => $filter)
        <div class="form-group col-md-4 mb-1">
            @foreach ($localizations as $key => $lang)
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><img
                            src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                            alt="{{ $key }}"></span>
                    </div>
                    <input type="text" name="filters[name_{{ $key }}][]" class="form-control" placeholder="{{ __('name') }}"
                        required
                        value="{{ $filter->translate($key)->name }}">
                </div>
            @endforeach

            <div class="input-group-prepend mb-1" title="link">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ __('Link') }}</span>
                </div>
                <input type="text" class="form-control" name="filters[link][]" value="{{ $filter->link }}" required/>
            </div>

            <div class="input-group-prepend mb-1" title="{{ __('Sort order') }}">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ __('Sort order') }}</span>
                </div>
                <input type="number" class="form-control" name="filters[order][]" value="{{ $filter->order }}"/>
            </div>

            <input type="hidden" name="filters[id][]" class="form-control"
                value="{{ $filter->id }}">

            <div class="input-group mb-2" style="width: auto;">
                <span class="btn btn-danger text-white remove-filter-item">
                    <i class="mdi mdi-delete"></i>

                </span>
            </div>
        </div>
    @endforeach
</div>
<template id="filters_template">
    <div class="form-group col-md-4 mb-1">
        @foreach ($localizations as $key => $lang)
            <div class="input-group mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text"><img
                            src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                            alt="{{ $key }}"></span>
                </div>
                <input type="text" name="filters[name_{{ $key }}][]" class="form-control" placeholder="{{ __('name') }}"
                    required
                    value="">
            </div>
        @endforeach

        <div class="input-group-prepend mb-1" title="{{ __('Link') }}">
            <div class="input-group-prepend">
                    <span class="input-group-text">{{ __('Link') }}</span>
            </div>
            <input type="text" class="form-control" name="filters[link][]" value="" required/>
        </div>

        <div class="input-group-prepend mb-1" title="{{ __('Sort order') }}">
            <div class="input-group-prepend">
                    <span class="input-group-text">{{ __('Sort order') }}</span>
            </div>
            <input type="number" class="form-control" name="filters[order][]" value="25"/>
        </div>

        <input type="hidden" name="filters[id][]" class="form-control"
            value="0">

        <div class="input-group mb-2" style="width: auto;">
            <span class="btn btn-danger text-white remove-filter-item">
                <i class="mdi mdi-delete"></i>
                {{ __('Remove') }}
            </span>
        </div>
    </div>
</template>
<span class="btn btn-success text-white btn-xs add-filter-element">
    <i class="mdi mdi-plus"></i>
    {{ __('Add') }} {{ __('Filter') }}
</span>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-filter-element', function() {
                let $container = $(this).parent().find('.filters-container');
                let $cloneElem = $(this).parent().find('template');

                $container.append($cloneElem.html());
            });

            $(document).on('click', '.remove-filter-item', function() {
                $(this).closest('.form-group').remove();
            });
        });
    </script>
@endpush
