@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('orders.index') }}">{{ __('Orders') }}</a></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('orders_create')
                        <a href="{{ route('orders.create') }}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">

                    <form action="" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-1">
                                <label>{{ __('Statuses') }}</label>
                                <select id="sizesSelect" name="status_id" class="form-control">
                                    <option value="">---</option>
                                    @foreach($statuses as $key => $status)
                                        @if(!in_array($key, [1,7]))
                                            <option value="{{ $key }}" @if(old('status_id', request()->input('status_id')) == $key) selected @endif>{{ __($status) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Search') }}</label>
                                <input type="text" name="search" class="form-control" value="{{ old('search', request()->input('search')) }}" placeholder="{{ __('Phone') }}, {{ __('Name') }}, {{ __('email') }}">
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success form-control">{{ __('Find') }}</button>
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <a href="{{ route('orders.index') }}" class="btn btn-danger form-control">{{ __('Clear') }}</a>
                            </div>

                            {{-- <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                @if ($show === 'dropped')
                                    <a href="{{ route('orders.index') }}" class="btn btn-outline-info form-control">{{ __('All Orders') }}</a>
                                @else
                                    <a href="{{ route('orders.index', ['cart' => 'dropped']) }}" class="btn btn-outline-info form-control">{{ __('Dropped Cart') }}</a>
                                @endif
                            </div> --}}

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                @if($show === 'trash')
                                    <a href="{{ route('orders.index') }}" class="btn btn-outline-info form-control">{{ __('All Orders') }}</a>
                                @else
                                    <a href="{{ route('orders.index', ['cart' => 'trash']) }}" class="btn btn-outline-info form-control">{{ __('Deleted') }}</a>
                                @endif

                            </div>
                        </div>
                    </form>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th class="text-center font-weight-bold">{{ __('ID') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Status') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Name') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Phone') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Created at') }}</th>
                                <th class="text-center font-weight-bold">{{ __('total') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $order)
                                <tr data-id="{{ $order->id }}">
                                    <td>
                                        <input type="checkbox" class="checkbox-item" data-check="{{ $order->id }}"
                                            name="check[]" value="{{ $order->id }}">
                                    </td>
                                    <td class="text-center">
                                        @can('orders_edit')
                                            <a href="{{ route('orders.edit', $order) }}">
                                                {{ $order->id }}
                                            </a>
                                        @else
                                            {{ $order->id }}
                                        @endcan

                                    </td>
                                    <td class="text-center">
                                        @switch($order->order_status_id)
                                            @case(1)
                                                @if ($order->notified)
                                                    <span class="badge badge-success font-weight-bold">{{ __('Notified') }}</span>
                                                @else
                                                    <span class="badge badge-dark font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                                @endif
                                            @break

                                            @case(2)
                                                <span class="badge badge-warning font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                            @break

                                            @case(3)
                                            @case(8)
                                                <span class="badge badge-danger font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                            @break

                                            @case(4)
                                                <span class="badge badge-info font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                            @break

                                            @case(5)
                                                <span class="badge badge-secondary font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                            @break

                                            @case(6)
                                                <span class="badge badge-success font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                            @break

                                            @case(7)
                                                <span class="badge badge-danger font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    <td class="text-center">{{ $order->shipping_name }}</td>
                                    <td class="text-center">{{ $order->shipping_phone }}</td>
                                    <td class="text-center">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">{{ $order->total ?? 0 }}</td>

                                    <td class="text-center">

                                        @if($order->order_status_id == 7)
                                        <form action="{{ route('orders.destroy', $order) }}" method="post">
                                        @else
                                        <form action="{{ route('orders.trash', $order) }}" method="post">
                                        @endif
                                            @csrf
                                            @method('DELETE')

                                            {{-- TODO: Create notify functional --}}
                                            {{-- @if ($order->order_status_id == 1)
                                                <button type="button" data-url="{{ route('orders.notify-create', ['order' => $order->id]) }}" data-id="{{ $order->id }}" data-target="#c_modal" class="btn btn-info btn-sm ml-1 notify-droped"><i class="far fa-envelope"></i></button>
                                            @endif --}}

                                            @can('orders_edit')
                                                <a class="btn btn-md btn-primary" href="{{ route('orders.edit', $order) }}"><i class="fas fa-edit"></i></a>
                                            @endcan

                                            @can('orders_view')
                                                <a href="{{ $order->frontLink(true) }}" target="_blank" class="btn btn-info  btn-md" title="{{ __('Preview') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan

                                            <a class="btn btn-md btn-warning" href="{{ route('orders.invoice-create', ['id' => $order->id]) }}"><i class="fa fa-file-pdf-o"></i></a>

                                            @can('orders_delete')
                                                <a href="javascript:void(0)" title="{{ __('Remove') }}"
                                                    class="btn btn-danger btn-md delete-item-btn text-white">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">{{ __('No data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <form action="{{ route('orders.delete-selected') }}" method="post" id="delete_sel_form">
                        @csrf
                        <input type="hidden" name="ids">
                    </form>
                    <form action="{{ route('orders.update-selected') }}" method="post" id="update_sel_form">
                        @csrf
                        <input type="hidden" name="ids">
                        <input type="hidden" name="status_id">
                    </form>
                    <div class="row">
                        <div class="col-sm-12">
                            <label style="margin-right: 15px">{{ __('With selected:') }}</label>

                            <span class="fa fa-trash btn alert-danger btn-xs btn-delete-checked"
                                title="{{ __('Remove') }}"></span>
                            <span> | </span>
                            <span>
                                <select id="status_change" class="form-control" style="display: inline-block; width: auto;">
                                    @foreach($statuses as $key => $status)
                                        @if(in_array($key, \Owlwebdev\Ecom\Models\Order::PUBLIC_STATUSES))
                                            <option value="{{ $key }}" @if(old('status_id', request()->input('status_id')) == $key) selected @endif>{{ __('Change status to') }} {{ __($status) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </span>
                            <span class="fa fa-floppy-o btn alert-success btn-xs btn-update-checked"
                                title="{{ __('Change status') }}"></span>

                        </div>
                    </div>

                    <div class="mt-5 row">
                        <div class="col-sm-6 col-md-2">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries
                        </div>
                        <div class="col-sm-6 col-md-10">
                            {{ $orders->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- TODO: Create notify functional --}}
<!-- Modal -->
{{-- <div class="modal fade" id="c_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notify user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ _('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="c_modal_content">
                Loading...
            </div>
            <div class="modal-footer">
                <div class="invalid-feedback" id="c_feedback">
                    Please correct the error
                </div>
                <button type="button" class="btn btn-secondary" id="c_close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary notify_send">Send</button>
            </div>

        </div>
    </div>
</div> --}}
<!-- END Modal -->
@endsection

@push('scripts')
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

<script type="text/javascript">
$( document ).ready(function() {

        //droped
        $('.notify-droped').on('click', function(event){
            event.preventDefault();
            var myModal = $(this).data('target');
            $(myModal+ '_content').html('{{ _('Loading...') }}');//reset
            var remote_content = $(this).data('url');

            //$(myModal+ '_content').load(remote_content);
            $.ajax({url: remote_content, success: function(result){
                $(myModal+ '_content').html(result);
            }});
            $('.notify_send').removeAttr('disabled');
            $(myModal).modal('show');

        });

        $('.notify_send').on("click", function () {
           $('.notify_send').attr('disabled','disabled');
            $('#c_feedback').html('').hide();//reset
            var id = $('#notify_form').data('id');
            var data = new FormData($('#notify_form')[0]);
            $.ajax({
                url: '/admin/orders/' + id + '/notify',
                type: 'POST',
                headers: {
                    'X-CSRF-Token': safeData.csrf_token
                },
                cache: false,
                processData: false,
                contentType: false,
                data: data,
                dataType: 'JSON',
                success: function (response) {
                    if(response.success){
                        $('#c_close').click();
                    } else {
                        $('.notify_send').removeAttr('disabled');
                        $('#c_feedback').html(response.error).show();
                    }
                },
                error: function (response) {
                    var errors = response.responseJSON;

                    errorsHtml = '';
                    $.each(errors.errors,function (k,v) {
                        errorsHtml += v + ' ';
                    });
                    $('.notify_send').removeAttr('disabled');
                    $('#c_feedback').html(errorsHtml).show();
                }
            });
        });

});
</script>
@endpush
