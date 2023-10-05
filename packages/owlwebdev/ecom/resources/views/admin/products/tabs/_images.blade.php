<div class="p-t-20 p-b-20">
    <div class="images-container row">
        @foreach($images as $image)
            <div class="col-md-3 form-group">
                <div class="images-element border p-2">
                    <div class="row">
                        <div class="input-group mb-2" style="width: auto">
                            <span class="btn btn-danger text-white remove-images-item">
                                <i class="mdi mdi-delete"></i>
                                {{-- {{ __('Remove') }} --}}
                            </span>
                        </div>
                        <div class="input-group mb-2" style="width: calc(100% - 60px)">
                            <input type="hidden" name="images[id][{{ $image->id }}]" value="{{ $image->id }}"/>

                            {{-- <div class="input-group-prepend">
                                <span class="input-group-text">{{ __('Sort') }}</span>
                            </div> --}}
                            <div class="input-group-prepend" title="Порядок сортування">
                                <input type="number" class="form-control" name="images[order][{{ $image->id }}]" value="{{ $image->order }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-2" style="justify-content: center;">
                        {{ media_preview_box('images[image][' . $image->id . ']', $image->image ?: null, $errors) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <template id="images_template">
        <div class="col-md-3 form-group">
            <div class="images-element border p-2">
                <div class="row">
                    <div class="input-group mb-2" style="width: auto;">
                        <span class="btn btn-danger text-white remove-images-item">
                            <i class="mdi mdi-delete"></i>
                            {{-- {{ __('Remove') }} --}}
                        </span>
                    </div>
                    <div class="input-group mb-2" style="width: calc(100% - 60px)">
                        <input type="hidden" name="images[id][]" value="0"/>

                        {{-- <div class="input-group-prepend">
                            <span class="input-group-text">{{ __('Sort') }}</span>
                        </div> --}}
                        <div class="input-group-prepend" title="Порядок сортування">
                            <input type="number" class="form-control" name="images[order][]" value="0"/>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-2" style="justify-content: center;">
                    {{ media_preview_box('images[image][]', null, $errors) }}
                </div>

            </div>
        </div>
    </template>

    <span data-template="images_template" class="btn btn-success text-white btn-xs add-images-element" data-product_id="{{$model->id}}">
        <i class="mdi mdi-plus"></i>
        {{ __('Add') }} {{ __('Image') }}
    </span>
</div>
