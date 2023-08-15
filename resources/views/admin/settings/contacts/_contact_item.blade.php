<div class="form-group row">
    <label class="col-md-3 text-right">Головний блок</label>
    <div class="col-md-9">
        <div class="material-switch pull-left">
            <input id="someSwitchOptionSuccess_is_main_{{ $lang }}_{{ $countContactBlock }}"
                name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][is_main]"
                value="1"
                type="checkbox"
                class="is-main-input is-main-input-{{ $lang }}"
                data-lang="{{ $lang }}"
                @if ((count($contact) == 0 && $countContactBlock == 0) || (isset($contact['is_main']) && $contact['is_main'])) checked @endif
            />
            <label for="someSwitchOptionSuccess_is_main_{{ $lang }}_{{ $countContactBlock }}"
                class="label-success"></label>
        </div>
    </div>
</div>

{{-- address ------------------------------------------------------------------------------------------------------- --}}
@include('admin.settings.contacts.fields.text', [
    'fieldTitle'        => 'Назва',
    'fieldName'         => 'name',
    'lang'              => $lang,
    'countContactBlock' => $countContactBlock,
    'contact'           => $contact,
])
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

{{-- address ------------------------------------------------------------------------------------------------------- --}}
@include('admin.settings.contacts.fields.text', [
    'fieldTitle'        => 'Адреса',
    'fieldName'         => 'address',
    'lang'              => $lang,
    'countContactBlock' => $countContactBlock,
    'contact'           => $contact,
])
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

{{-- E-mail -------------------------------------------------------------------------------------------------------- --}}
@include('admin.settings.contacts.fields.text', [
    'fieldTitle'        => 'E-mail',
    'fieldName'         => 'email',
    'lang'              => $lang,
    'countContactBlock' => $countContactBlock,
    'contact'           => $contact,
])
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

{{-- Phones -------------------------------------------------------------------------------------------------------- --}}
@include('admin.settings.contacts.fields.array', [
    'fieldTitle'         => 'Телефон',
    'fieldName'          => 'phones',
    'fields'             => [
        [
            'fieldType'  => 'text',
            'fieldTitle' => 'Заголовок',
            'fieldName'  => 'label',
        ],
        [
            'fieldType'  => 'text',
            'fieldTitle' => 'Номер',
            'fieldName'  => 'number',
        ],
    ],
    'lang'               => $lang,
    'countContactBlock'  => $countContactBlock,
    'contact'            => $contact,
])
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

{{-- Schedule ------------------------------------------------------------------------------------------------------ --}}
@include('admin.settings.contacts.fields.array', [
    'fieldTitle'         => 'Робочий графік',
    'fieldName'          => 'schedule',
    'fields'             => [
        [
            'fieldType'  => 'text',
            'fieldTitle' => 'Заголовок',
            'fieldName'  => 'label',
        ],
        [
            'fieldType'  => 'text',
            'fieldTitle' => 'Час',
            'fieldName'  => 'time',
        ],
    ],
    'lang'               => $lang,
    'countContactBlock'  => $countContactBlock,
    'contact'            => $contact,
])
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

{{-- Socials ------------------------------------------------------------------------------------------------------- --}}
@include('admin.settings.contacts.fields.array', [
    'fieldTitle'         => 'Соц. мережі',
    'fieldName'          => 'socials',
    'fields'             => [
        [
            'fieldType'  => 'text',
            'fieldTitle' => __("Link"),
            'fieldName'  => 'link',
        ],
        [
            'fieldType'  => 'image',
            'fieldTitle' => '',
            'fieldName'  => 'image',
        ],
        [
            'fieldType'  => 'image',
            'fieldTitle' => '',
            'fieldName'  => 'image2',
        ],
    ],
    'lang'               => $lang,
    'countContactBlock'  => $countContactBlock,
    'contact'            => $contact,
])
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

{{-- MapsMarks ------------------------------------------------------------------------------------------------------ --}}
@include('admin.settings.contacts.fields.array', [
    'fieldTitle'         => 'Мітки на карті',
    'fieldName'          => 'maps_marks',
    'fields'             => [
        [
            'fieldType'  => 'text',
            'fieldTitle' => 'Координати lat',
            'fieldName'  => 'lat',
        ],
        [
            'fieldType'  => 'text',
            'fieldTitle' => 'Координати lng',
            'fieldName'  => 'lng',
        ],
    ],
    'lang'               => $lang,
    'countContactBlock'  => $countContactBlock,
    'contact'            => $contact,
])
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

<div class="form-group row">
    <label class="col-md-3 text-right">Месенджери</label>
    <div class="col-md-9">
        <div class="item-messenger">
            <div style="display: none;">
                <div data-item-id="#dynamicListPlaceholder" class="item-messengers-template-none item-messenger2"
                    style="margin: 10px 0">
                    <div class="row">
                        <div class="col-md-5">
                            <select
                                name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][messengers][#dynamicListPlaceholder][type]"
                                class="form-control mtype">
                                <option value="telegram">Telegram</option>
                                <option value="facebook_messenger">Facebook Messenger</option>
                                <option value="whats_app">WhatsApp</option>
                                <option value="viber">Viber</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="hidden" class="mlink"
                                name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][messengers][#dynamicListPlaceholder][link]">
                            <input type="text" placeholder="Телефон"
                                name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][messengers][#dynamicListPlaceholder][phone]"
                                class="form-control mphone" disabled="">
                        </div>
                        <div class="col-md-2">
                            <div class="input-group-append">
                                <button type="button"
                                    class="btn btn-danger remove-item-messenger text-white">{{ __('Remove') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden"
                name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][messengers]"
                value="">

            @php($dataMessengers = $contact['messengers'] ?? [])
            <div class="items-messengers-container w-100">
                @foreach ($dataMessengers as $k => $value)
                    <div data-item-id="{{ $k }}" class="item-template item-group item-messenger2"
                        style="margin: 10px 0">
                        <div class="row">
                            <div class="col-md-5">
                                <select
                                    name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][messengers][{{ $k }}][type]"
                                    class="form-control mtype">
                                    <option value="telegram" @if ($value['type'] == 'telegram') selected @endif>Telegram
                                    </option>
                                    <option value="facebook_messenger"
                                        @if ($value['type'] == 'facebook_messenger') selected @endif>Facebook Messenger</option>
                                    <option value="whats_app" @if ($value['type'] == 'whats_app') selected @endif>WhatsApp
                                    </option>
                                    <option value="viber" @if ($value['type'] == 'viber') selected @endif>Viber
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="hidden"
                                    name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][messengers][{{ $k }}][link]"
                                    value="{{ $value['link'] ?? '' }}" class="mlink">
                                <input type="text" placeholder="Телефон"
                                    name="setting_data[{{ $lang }}][contacts][{{ $countContactBlock }}][messengers][{{ $k }}][phone]"
                                    value="{{ $value['phone'] ?? '' }}" class="form-control mphone">
                            </div>
                            <div class="col-md-2">
                                <div class="input-group-append">
                                    <button type="button"
                                        class="btn btn-danger remove-item-messenger text-white">{{ __('Remove') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="button" class="btn btn-info btn-sm add-item-messengers">{{ __('Create') }}</button>
    </div>
</div>
