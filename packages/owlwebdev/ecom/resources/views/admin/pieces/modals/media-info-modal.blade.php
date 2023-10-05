<div class="owl-modal-container" id="{{$modal_id}}">
    <div class="owl-modal">
        <div class="owl-modal-header">
            {{$title}}
            <i class="fa fa-times owl-modal-close-btn"></i>
        </div>
        <div class="owl-modal-body">
            <b>{{ __('File name') }}:</b> {{$name}}<br>
            <b>{{ __('File type') }}:</b> {{$mime}}<br>
            <b>{{ __('Created') }}:</b> {{$created_at}}<br>
            <b>{{ __('File dimentions') }}:</b> {{$width}} x {{$height}} {{ __('pixels') }}<br>
            <b>{{ __('File size') }}:</b> {{$size}} KB<br>

            <b>{{ __('Link') }}:</b> {{$path}}

            @if($alt_name)
                <hr><br>
                <div class="row">
                    <div class="col-sm-3 text-right">
                        <label>Alt</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" value="{{$alt_value}}" data-id="{{$modal_id}}" class="form-control owl-modal-alt-input">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <span data-id="{{$modal_id}}" class="btn btn-md btn-success float-right text-white owl-modal-save-alt-btn" style="margin-top: 20px">{{ __('Save') }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
