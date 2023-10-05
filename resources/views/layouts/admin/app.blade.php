<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('matrix/images/favicon.png') }}">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('matrix/libs/flot/css/float-chart.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('matrix/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/reStyle.css') }}" rel="stylesheet">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />

    <link href="{{ asset('css/admin/inter-link.css') }}" rel="stylesheet">
    <!-- summernote -->
    {{-- <link rel="stylesheet" href="{{ asset("assets/plugins/summernote/summernote-bs4.css") }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <!-- Code Mirror Styles -->
    {{-- <link rel="stylesheet" href="{{ asset('vendor/admin/codemirror/lib/codemirror.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('myLib/media-owl-modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/icomoon2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/new-icons.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
    <style>
        .card {
            box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

        .card.card-outline {}

        .card.card-outline.card-info {
            border-top: 3px solid #17a2b8;
        }
    </style>
    @stack('styles')
</head>

<body>
    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        @include('layouts.admin.components.header')

        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @include('layouts.admin.components.aside')

        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            @yield('breadcrumb')
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid container-custom1">
                @include('layouts.admin.components._errors')
                @yield('content')
            </div>

            <footer class="footer text-center">
                <strong>Copyright &copy; {{ date('Y') }} <a
                        href="{{ url('/admin') }}">{{ env('APP_NAME') }}</a>.</strong>
                All rights reserved.
                <b>Version</b> 1.0.0
            </footer>
        </div>
    </div>

    <form action="" method="post" id="form_copy_model_lang" style="display: none">
        @csrf
        <input type="hidden" name="model_id" id="form_copy__model_id">
        <input type="hidden" name="from" id="form_copy__from">
        <input type="hidden" name="to" id="form_copy__to">
        <input type="hidden" name="constructor_replace" id="form_copy__constructor_replace">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('matrix/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('myLib/media-owl-modal/js.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Latest compiled JavaScript -->
    {{-- <script src="{{ asset('vendor/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    {{-- <script src="{{ asset('matrix/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script> --}}
    <script src="{{ asset('matrix/extra-libs/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('matrix/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('matrix/js/sidebarmenu.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/slugify/slugify.min.js"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('matrix/js/custom.min.js') }}"></script>
    <!--This page JavaScript -->
    {{-- <script src="matrix/js/pages/dashboards/dashboard1.js"></script> --}}
    <!-- Charts js Files -->
    <script src="{{ asset('matrix/libs/flot/excanvas.js') }}"></script>
    <script src="{{ asset('matrix/libs/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('matrix/libs/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('matrix/libs/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('matrix/libs/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('matrix/libs/flot/jquery.flot.crosshair.js') }}"></script>
    {{-- <script src="{{ asset('matrix/libs/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script> --}}

    {{-- <script src="{{ asset('matrix/js/pages/chart/chart-page-init.js') }}"></script> --}}
    <!-- Summernote -->
    {{-- <script src="{{ asset("assets/plugins/summernote/summernote-bs4.js") }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script src="{{ asset('js/summernote_plugins/summernote-uk-UA.js') }}"></script>
    <script src="{{ asset('js/summernote_plugins/summernote_specialchars.js') }}"></script>
    <script src="{{ asset('js/summernote_plugins/summernote_lfm.js') }}"></script>
    <script src="{{ asset('js/summernote_plugins/summernote_video.js') }}"></script>
    <script src="{{ asset('js/summernote_plugins/btn_replace.js') }}"></script>
    <script src="{{ asset('js/summernote_plugins/btn_replace_tags.js') }}"></script>
    <script src="{{ asset('js/summernote_plugins/btn_replace_tags_all.js') }}"></script>
    <script src="{{ asset('js/summernote_plugins/btn_more.js') }}"></script>

    <script>
        const summernote_options = {
            lang: 'uk-UA',
            height: 250,
            minHeight: null,
            maxHeight: null,
            toolbar: [
                ['undoredo', ['undo', 'redo']],
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear',
                    'tags_replace', 'tags_replace_all'
                ]],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['height', ['height']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table', 'specialchars']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']],
                ['popovers', ['img', 'myVideo']],
                ['typography', ['typography','rdm']]
            ],
            buttons: {
                img: LFMButton,
                myVideo: VideoButton,
                typography: ReplaceButton,
                tags_replace: ReplaceButtonTags,
                tags_replace_all: ReplaceButtonTagsAll,
                rdm: MoreButton
            },
            icons: {
                clear: '<i class="fa fa-video-camera"></i>',
            },
            styleTags: [
                'p', { title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote' }, 'pre', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
        };

        $('.editor').summernote(summernote_options);
    </script>
    <script type="text/javascript">
        const mixedMode = {
            name: "htmlmixed",
            scriptTypes: [{
                    matches: /\/x-handlebars-template|\/x-mustache/i,
                    mode: null
                },
                {
                    matches: /(text|application)\/(x-)?vb(a|script)/i,
                    mode: "vbscript"
                }
            ]
        };
    </script>
    <!-- Open media window start -->
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script type="text/javascript">
        (function() {
            const imagePlaceholder = '{{ asset('/images/no-image.png') }}';
            const videoPlaceholder = '{{ asset('vendor/admin/images/video-placeholder.png') }}';
            const filePlaceholder = '{{ asset('images/no-file.svg') }}';
            const pdfPlaceholder = '{{ asset('images/pdf-file.jpg') }}';
            const docPlaceholder = '{{ asset('images/doc-file.jpg') }}';
            const xlsPlaceholder = '{{ asset('images/excel-file.png') }}';
            const media_prefix = '{{ config('filesystems.disks.public.url') }}/media';

            $(document).on('click', '.choice-media, .choice-file', function() {
                const trigger = this;
                const media_selection = $(trigger).hasClass('choice-media');
                const file_selection = $(trigger).hasClass('choice-file');

                let type = 'image';

                if (file_selection) type = 'file';

                window.open('/filemanager?type=' + type, 'FileManager', 'width=900,height=600');

                window.SetUrl = function(items) {
                    const file_path = items.map(function(item) {
                        item.url_absolute = item.url;
                        item.url = item.url.replace(media_prefix, '');

                        return item;
                    });

                    if (file_path.length > 0) {
                        const fullFileUrl = file_path[0].url_absolute;
                        const fileUrl = file_path[0].url;

                        if (media_selection) {
                            const mediaWrapper = $(trigger).parents('.media-wrapper');

                            if (file_path[0].is_image) {
                                if (fullFileUrl.split('.').pop().toLowerCase() == 'svg') {
                                    $(mediaWrapper).find('.image-tag').attr('src', '/storage/media' + fileUrl);
                                } else {
                                    $(mediaWrapper).find('.image-tag').attr('src', fullFileUrl);
                                }

                                $(mediaWrapper).find('.media-input').val(fileUrl);
                            }

                            if (file_path[0].is_file) {
                                if (['mp4', 'm4v', '3gp'].indexOf(fullFileUrl.split('.').pop().toLowerCase()) !== -1) {
                                    $(imageWrapper).find('.image-tag').attr('src', videoPlaceholder);
                                } else {
                                    $(trigger).parent().parent().find('.file-path-field').val(fileUrl);
                                }

                                $(mediaWrapper).find('.media-input').val(fileUrl);
                            }
                        }

                        if (file_selection) {
                            const mediaWrapper = $(trigger).parents('.media-wrapper');
                            const inputFilePath = $(trigger).parents('.media-wrapper').find('.file-path-field');

                            if (inputFilePath) {
                                // inputFilePath.val('/storage/files/uploads/' + file_path[0].name);
                                let parts = file_path[0].url.split('storage/files');

                                inputFilePath.val(parts[1]);

                                if (['pdf'].indexOf(fullFileUrl.split('.').pop().toLowerCase()) !== -1) {
                                    $(mediaWrapper).find('.image-tag').attr('src', pdfPlaceholder);
                                }

                                if (['docx'].indexOf(fullFileUrl.split('.').pop().toLowerCase()) !== -1) {
                                    $(mediaWrapper).find('.image-tag').attr('src', docPlaceholder);
                                }

                                if (['doc'].indexOf(fullFileUrl.split('.').pop().toLowerCase()) !== -1) {
                                    $(mediaWrapper).find('.image-tag').attr('src', docPlaceholder);
                                }

                                if (['xlsx'].indexOf(fullFileUrl.split('.').pop().toLowerCase()) !== -1) {
                                    $(mediaWrapper).find('.image-tag').attr('src', xlsPlaceholder);
                                }

                                mediaWrapper.find('.img-thumbnail').attr('title',file_path[0].name);
                            }
                        }
                    }
                };
            });

            $(document).on('click', '.remove-media', function() {
                const mediaWrapper = $(this).parents('.media-wrapper');

                $(mediaWrapper).find('.image-tag').attr('src', imagePlaceholder);
                $(mediaWrapper).find('.media-input').val('');
            });

            $(document).on('click', '.remove-file', function() {
                const mediaWrapper = $(this).parents('.media-wrapper');

                $(mediaWrapper).find('.image-tag').attr('src', filePlaceholder);
                $(mediaWrapper).find('.image-tag').attr('title','Файл не выбран');
                $(mediaWrapper).find('.file-path-field').val('');
            });

            $(document).on('click', '.info-media', function() {
                const mediaWrapper = $(this).parents('.media-wrapper');
                let path = $(mediaWrapper).find('.media-input').val();

                let modal_id = 'media_info_' + Date.now();

                $(mediaWrapper).addClass('data-id-' + modal_id);

                let alt_name = $(mediaWrapper).find('.media-input-alt').attr('name');
                let alt_value = $(mediaWrapper).find('.media-input-alt').val();

                $.ajax({
                    url: "{{ route('multimedia.get-info') }}",
                    type: "get",
                    dataType: "json",
                    data: {
                        path: path,
                        modal_id: modal_id,
                        alt_name: alt_name,
                        alt_value: alt_value,
                    },
                    success: function(res) {
                        if (res.success) {
                            $("body").append(res.modal);
                        }
                    }
                });
            });
        })();
    </script>
    <script>
        function formatStateIcon (state) {
            if (!state.id) {
                return state.text;
            }
            if(state.element.dataset.icon && state.element.dataset.icon !== "non"){
                var $state = $(
                    '<span class="'+state.element.dataset.icon+'"></span>'
                );
            } else {
                var $state = $(
                    '<span> ' + state.text + '</span>'
                );
            }
            return $state;
        };

        $(document).ready(function () {
            setTimeout(function () {
                $(".btn-icon-select2-ready").each(function () {
                    $(this).select2({
                        templateResult: formatStateIcon,
                        templateSelection: formatStateIcon,
                    });
                });
            },500);
        });
    </script>
    @stack('scripts')
</body>

</html>
