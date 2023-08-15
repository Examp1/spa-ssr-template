<div class="row" style="margin-top: 5px">
    <label for="{{ $field_id }}" class="col-3 text-right">{{__('Rules')}}</label>
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample{{ $field_id }}" role="button" aria-expanded="false" aria-controls="collapseExample{{ $field_id }}">
                    {{__('Show')}}
                </a>
            </div>
            <div class="card-body collapse" id="collapseExample{{ $field_id }}">
                <h4>{{__('Rules')}}</h4>
                <div class="row" style="margin-top: 5px">
                    <label for="{{ $field_id }}" class="col-3 text-right">{{__('Required field')}}</label>
                    <div class="col-9">
                        <div class="material-switch pull-left">
                            <input name="data[{{$field_id}}][rules][required]" value="0" type="hidden" />
                            <input id="SwitchOptionRulesRequired_{{ $field_id }}" name="data[{{$field_id}}][rules][required]" value="1" type="checkbox" @if(isset($field['rules']['required']) && $field['rules']['required']) checked @endif />
                            <label for="SwitchOptionRulesRequired_{{ $field_id }}" class="label-success"></label>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px">
                    <label for="{{ $field_id }}" class="col-3 text-right">E-mail</label>
                    <div class="col-9">
                        <div class="material-switch pull-left">
                            <input name="data[{{$field_id}}][rules][email]" value="0" type="hidden" />
                            <input id="SwitchOptionRulesEmail_{{ $field_id }}" name="data[{{$field_id}}][rules][email]" value="1" type="checkbox" @if(isset($field['rules']['email']) && $field['rules']['email']) checked @endif />
                            <label for="SwitchOptionRulesEmail_{{ $field_id }}" class="label-success"></label>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px">
                    <label for="{{ $field_id }}" class="col-3 text-right">{{__('Minimum value')}}</label>
                    <div class="col-9">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" name="data[{{$field_id}}][rules][min]" value="{{$field['rules']['min'] ?? '' }}" id="{{ $field_id }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px">
                    <label for="{{ $field_id }}" class="col-3 text-right">{{__('Maximum value')}}</label>
                    <div class="col-9">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" name="data[{{$field_id}}][rules][max]" value="{{$field['rules']['max'] ?? '' }}" id="{{ $field_id }}" class="form-control">
                        </div>
                    </div>
                </div>

                <h4>{{__('Message')}}</h4>

                <div class="row" style="margin-top: 5px">
                    <label class="col-3 text-right">{{__('Required field')}}</label>
                    <div class="col-9">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" name="data[{{$field_id}}][messages][required]" value="{{$field['messages']['required'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 5px">
                    <label class="col-3 text-right">E-mail</label>
                    <div class="col-9">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" name="data[{{$field_id}}][messages][email]" value="{{$field['messages']['email'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 5px">
                    <label class="col-3 text-right">{{__('Minimum value')}}</label>
                    <div class="col-9">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" name="data[{{$field_id}}][messages][min]" value="{{$field['messages']['min'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 5px">
                    <label class="col-3 text-right">{{__('Maximum value')}}</label>
                    <div class="col-9">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" name="data[{{$field_id}}][messages][max]" value="{{$field['messages']['max'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
