<input type="hidden" name="_tab" value="{{ $tab }}">

<ul class="nav nav-tabs" role="tablist">
    @foreach ($localizations as $key => $lang)
        <li class="nav-item">
            <a class="nav-link @if (config('translatable.locale') == $key) active @endif" data-toggle="tab"
                href="#main_lang_{{ $key }}" role="tab">
                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                        src="/images/langs/{{ $key }}.jpg" style="width: 20px" alt="{{ $key }}">
                    {{ $lang }}</span>
            </a>
        </li>
    @endforeach
</ul>

<br>

<div class="tab-content">
    @foreach ($localizations as $key => $catLang)
        <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
            id="main_lang_{{ $key }}" role="tabpanel">
            @php($contacts = isset($data[$key]['contacts']) ? json_decode($data[$key]['contacts'][0]['value'], true) : [])
            <ul class="nav nav-tabs nav-tabs-{{ $key }}-contacts" role="tablist">
                @if (count($contacts))
                    @foreach ($contacts as $contactKey => $contact)
                        <li class="nav-item nav-item-contact">
                            <a class="nav-link @if ($contactKey == 0) active @endif" data-toggle="tab"
                                href="#contact_block_{{ $key }}_{{ $contactKey }}" role="tab">
                                {{ $contactKey + 1 }}
                            </a>
                        </li>
                    @endforeach
                @endif
                <li class="nav-item">
                    <a class="nav-link add_contact_block @if (count($contacts) == 0) active @endif"
                        data-lang="{{ $key }}" href="javascript:void(0)" title="{{ __('Add') }}">
                        <i class="fa fa-plus"></i>
                    </a>
                </li>
            </ul>

            <br>

            <div class="tab-content tab-content-{{ $key }}-contacts">
                @if (count($contacts))
                    @foreach ($contacts as $contactKey => $contact)
                        <div class="tab-pane p-t-20 p-b-20 @if ($contactKey == 0) active @endif"
                            id="contact_block_{{ $key }}_{{ $contactKey }}" role="tabpanel">
                            @include('admin.settings.contacts._contact_item', [
                                'lang' => $key,
                                'countContactBlock' => $contactKey,
                                'contact' => $contact,
                            ])

                            <span class="btn btn-danger text-white remove-block" data-lang="{{ $key }}"
                                data-contact_key="{{ $contactKey }}">{{ __('Remove') }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".add_contact_block").on('click', function() {
                let lang = $(this).data('lang');
                let countContactBlock = $(".nav-tabs-" + lang + "-contacts .nav-item-contact").length;
                $.ajax({
                    url: "{{ route('settings.contacts.add') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        lang: lang,
                        countContactBlock: countContactBlock
                    },
                    success: function(res) {
                        let navItem =
                            '<li class="nav-item nav-item-contact"><a class="nav-link active" data-toggle="tab" href="#contact_block_' +
                            lang + '_' + countContactBlock + '" role="tab">' + (
                                countContactBlock + 1) + '</a></li>';
                        let tabPane =
                            '<div class="tab-pane p-t-20 p-b-20 active" id="contact_block_' +
                            lang + '_' + countContactBlock + '" role="tabpanel">' + res +
                            '</div>';
                        $(".nav-tabs-" + lang + "-contacts li a").removeClass("active");
                        $(".tab-content-" + lang + "-contacts .tab-pane").removeClass("active");

                        $(".nav-tabs-" + lang + "-contacts .nav-item:last").before(navItem);
                        $(".tab-content-" + lang + "-contacts").append(tabPane);
                        $(".mtype").trigger('change');
                    }
                });
            });

            $(document).on('click', ".remove-block", function() {
                let $btn = $(this);
                let lang = $btn.data('lang');
                let count_contact_block = $btn.data('contact_key');

                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: "{{ __('Are you trying to delete?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('Yes') }}',
                    cancelButtonText: '{{ __('No') }}'
                }).then((result) => {
                    if (result.value) {
                        $("#remove_contact_block_form__field_lang").val(lang);
                        $("#remove_contact_block_form__field_count_contact_block").val(
                            count_contact_block);
                        $("#remove_contact_block_form").submit();
                    }
                });
            });

            $(document).on('click', '.add-item-array-field', function() {
                let field_name = $(this).data('field_name');
                let lang = $(this).data('lang');
                let count_contact_block = $(this).data('count_contact_block');
                const template = $(this).parent().find('.item-' + field_name + '-template-none-' + lang +
                    '-' + count_contact_block);
                const container = $(this).parent().find('.items-' + field_name + '-container-' + lang +
                    '-' + count_contact_block);

                create_item(template, container, '#dynamicListPlaceholder');

                container.find('input, textarea').prop('disabled', false);
            });

            $(document).on('click', '.remove-item', function() {
                $(this).parents('.item-group').remove();
            });

            $(document).on("change", ".is-main-input", function() {
                let lang = $(this).data('lang');
                let this_status = $(this).prop('checked');

                if (!this_status) return;

                $(".is-main-input-" + lang).prop('checked', false);
                $(this).prop('checked', true);
            });

            $(document).on('click', '.add-item-messengers', function() {
                const template = $(this).parent().find('.item-messengers-template-none');
                const container = $(this).parent().find('.items-messengers-container');

                create_item(template, container, '#dynamicListPlaceholder');

                container.find('input, textarea').prop('disabled', false);
            });

            $(document).on('change', '.mphone', function() {
                let phone = $(this).val();
                let type = $(this).closest('.row').find('.mtype').val();
                let link = '';

                switch (type) {
                    case "telegram":
                        link = "tg://resolve?domain=" + phone;
                        break;
                    case "facebook_messenger":
                        link = "https://www.messenger.com/t/" + phone;
                        break;
                    case "whats_app":
                        link = "https://api.whatsapp.com/send?phone=" + phone;
                        break;
                    case "viber":
                        link = "viber://chat?number=%2B" + phone;
                        break;
                }

                $(this).siblings('.mlink').val(link);
            });

            $(document).on('change', '.mtype', function() {
                let type = $(this).val();
                let placeholder = '';

                switch (type) {
                    case "telegram":
                        placeholder = "Псевдонім, наприклад: Nick0000";
                        break;
                    case "facebook_messenger":
                        placeholder = "ID профілю, наприклад: 000000000000000";
                        break;
                    case "whats_app":
                        placeholder = "Телефон у форматі: 380ХХХХХХХХХ";
                        break;
                    case "viber":
                        placeholder = "Телефон у форматі: 380ХХХХХХХХХ";
                        break;
                }

                $(this).closest('.row').find('.mphone').attr('placeholder', placeholder);
            });

            $(".mtype").trigger('change');

            $(document).on('click', '.remove-item-messenger', function() {
                $(this).closest('.item-messenger2').remove();
            });
        });
    </script>
@endpush
