<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Setting\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Owlwebdev\Ecom\Models\Order;
use Owlwebdev\Ecom\Models\OrderProduct;
use Owlwebdev\Ecom\Models\Cart;
use Owlwebdev\Ecom\Models\Product;
use App\Models\User;
use Owlwebdev\Ecom\Models\Coupon;
use Owlwebdev\Ecom\Http\Requests\OrderRequest;
use Owlwebdev\Ecom\Http\Requests\OrderStatusRequest;
use Owlwebdev\Ecom\Models\Discount;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:orders_view')->only('index');
        $this->middleware('permission:orders_create')->only('create', 'store');
        $this->middleware('permission:orders_edit')->only('edit', 'update');
        $this->middleware('permission:orders_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $statuses = Order::STATUSES;

        $query = Order::query()->orderBy('created_at', 'desc');

        if ($request->has('cart') && $request->input('cart') === 'dropped') {
            $query->where('order_status_id', 1); //dropped
        } elseif ($request->has('cart') && $request->input('cart') === 'trash') {
            $query->where('order_status_id', 7); //trash
        }

        $query->when($request->input('status_id'), function ($query) use ($request) {
            $query->where('order_status_id', $request->input('status_id'));
        });

        $query->when($request->input('search'), function ($query) use ($request) {
            $search_text = $request->input('search');

            $query->where(function ($query) use ($search_text) {
                $query->orWhere('id', $search_text);

                $query->orWhereHas('user', function ($query) use ($search_text) {
                    $query->where('name', 'like', '%' . $search_text . '%');
                    $query->orWhere('email', 'like', '%' . $search_text . '%');
                    $query->orWhere('phone', 'like', '%' . $search_text . '%');
                });
            });
        });

        if (empty($request->input('search')) && empty($request->input('cart'))) {
            $query->whereNotIn('order_status_id', [1, 7]); //hide dropped, waiting, canceled, trash
        }

        $orders = $query->paginate(20);

        $show = '';
        if ($request->has('cart') && $request->input('cart') === 'dropped') {
            $show = 'dropped';
        } elseif ($request->has('cart') && $request->input('cart') === 'trash') {
            $show = 'trash';
        }

        return view('ecom::admin.orders.index', [
            'orders'   => $orders,
            'statuses' => $statuses,
            'show'     => $show,
        ]);
    }

    public function stats(Request $request)
    {
        $statuses = Order::STATUSES;
        $order_status = $request->get('status_id');
        $status = $request->get('status');
        $categories = $request->get('categories');
        $quantity = $request->get('quantity');

        if ($request->has('period')) {
            $p     = explode(' - ', $request->get('period'));
            $pFrom = $p[0];
            $pTo   = $p[1];
        } else {
            $pFrom = Carbon::today()->addWeek(-1)->format("Y-m-d");
            $pTo   = Carbon::today()->format("Y-m-d");
        }

        $dateTimeFrom = $pFrom . ' 00:00:00';
        $dateTimeTo = $pTo . ' 23:59:59';

        $query = Product::query()
            // ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where(function ($q) use ($status, $categories, $quantity, $order_status, $dateTimeFrom, $dateTimeTo) {
                $q->whereHas('ordered', function ($order_product_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {
                    if (!empty($order_status) || ($dateTimeFrom && $dateTimeTo)) {
                        $order_product_query->whereHas('order', function ($order_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {
                            $order_query->where(function ($order_where_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {

                                if (!empty($order_status)) {
                                    $order_where_query->where('order_status_id', $order_status);
                                }

                                if ($dateTimeFrom && $dateTimeTo) {
                                    $order_where_query->whereBetween('created_at', [Carbon::parse($dateTimeFrom), Carbon::parse($dateTimeTo)]);
                                }
                            });
                        });
                    }
                    $order_product_query->orderBy('option_id');
                });

                if ($status != '') {
                    $q->where('products.status', $status);
                }

                if ($quantity != '') {
                    switch ($quantity) {
                        case '0':
                            $q->where('products.quantity', 0);
                            break;
                        case '1':
                            $q->where('products.quantity', '>=', 1);
                            break;
                        case '10':
                            $q->where('products.quantity', '<=', 10);
                            break;
                    }
                }

                if (!empty($categories) && is_array($categories)) {
                    $q->whereHas('categories', function ($category_query) use ($categories) {
                        $category_query->whereIn('categories.id', $categories);
                    });
                }
            })
            ->with([
                'ordered' => function ($order_product_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {
                    if (!empty($order_status) || ($dateTimeFrom && $dateTimeTo)) {
                        $order_product_query->whereHas('order', function ($order_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {
                            $order_query->where(function ($order_where_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {

                                if (!empty($order_status)) {
                                    $order_where_query->where('order_status_id', $order_status);
                                }

                                if ($dateTimeFrom && $dateTimeTo) {
                                    $order_where_query->whereBetween('created_at', [Carbon::parse($dateTimeFrom), Carbon::parse($dateTimeTo)]);
                                }
                            });
                        });
                    }
                    $order_product_query->orderBy('option_id');
                },

                'orderedInfo' => function ($order_product_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {
                    if (!empty($order_status) || ($dateTimeFrom && $dateTimeTo)) {
                        $order_product_query->whereHas('order', function ($order_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {
                            $order_query->where(function ($order_where_query) use ($order_status, $dateTimeFrom, $dateTimeTo) {

                                if (!empty($order_status)) {
                                    $order_where_query->where('order_status_id', $order_status);
                                }

                                if ($dateTimeFrom && $dateTimeTo) {
                                    $order_where_query->whereBetween('created_at', [Carbon::parse($dateTimeFrom), Carbon::parse($dateTimeTo)]);
                                }
                            });
                        });
                    }
                    $order_product_query->orderBy('option_id');
                },
                'ordered.order', 'category', 'prices', 'categories',
            ]);

        $models = $query->paginate(1000);

        return view('ecom::admin.orders.stats', [
            'models'   => $models,
            'statuses' => $statuses,
            'dateFrom' => $pFrom,
            'dateTo'   => $pTo,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = null;
        if ($request->has('u')) {
            $user = User::find(intval($request->input('u')));
        }

        $users = User::all();
        $coupons = Coupon::all();
        $order_statuses = Order::STATUSES;
        $shipping_methods = Cart::getShippingMethods();
        $payment_methods = Cart::getPaymentMethods();
        $products = Product::getQueryWithPrices()->get();

        return view('ecom::admin.orders.create', compact(
            'user',
            'users',
            'coupons',
            'order_statuses',
            'shipping_methods',
            'payment_methods',
            'products',
        ));
    }


    public function store(Request $request)
    {
        $tmp_user = User::where('email', $request['shipping_email'])->first();
        if ($tmp_user) {
            $request['user_id'] = $tmp_user->id;
        } else {
            DB::beginTransaction();

            try {
                $user = User::updateOrCreate(
                    [
                        'email' => $request['shipping_email'],
                        'status' => User::STATUS_NOT_ACTIVE,
                        'role' => null
                    ],
                    [
                        'name' => $request['shipping_name'],
                        'email' => $request['shipping_email'],
                        'password' => bcrypt(Str::random(12)),
                        'phone' => $request['shipping_phone'],
                        'country' => '',
                        'city' => $request['shipping_city'],
                        'address' => $request['shipping_address'],
                        'apartment' => $request['shipping_apartment'], //NP city ID
                        'postcode' => '',
                        'status' => User::STATUS_NOT_ACTIVE,
                    ]
                );

                DB::commit();

                $request['user_id'] = $user->id;
            } catch (\Exception $e) {

                DB::rollBack();

                return redirect()->back()->with('error', __('Error!') . __('Cannot create User'));
            }
        }

        $model = new Order();

        $model->fill($request->all());

        return $model->save()
            ? redirect()->route('orders.edit', $model)->with('success', __('Success!')) //Order created Successfully
            : redirect()->back()->with('error', __('Error!') . __('Order not created, error'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param Pages $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Order $order)
    {
        $statuses = Order::STATUSES;
        $localizations = config('translatable.locales');
        $shipping_methods = Cart::getShippingMethods();
        $payment_methods = Cart::getPaymentMethods();
        $products = Product::getQueryWithPrices()->get();
        $coupons = Coupon::all();
        $discounts = Discount::all();

        return view('ecom::admin.orders.edit', compact(
            'order',
            'shipping_methods',
            'payment_methods',
            'statuses',
            'products',
            'coupons',
            'discounts',
            'localizations'
        ));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copy($id)
    {
    }

    /**
     * @param PagesRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OrderRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $model = Order::find((int) $id);
            $old_tracking = $model->tracking;
            $old_shipping_method = $model->shipping_method;
            $old_coupon_id = $model->coupon_id;
            $old_order_status_id = $model->order_status_id;

            $model->fill($request->all());

            // update totals on shipping change
            if ($old_shipping_method !== $model->shipping_method) {
                $shipping_methods = Cart::getShippingMethods();

                if (isset($shipping_methods[$model->shipping_method])) {
                    $model->fill([
                        'shipping' => $shipping_methods[$model->shipping_method]['price'],
                    ]);
                } else {
                    DB::rollBack();

                    return redirect()->back()->with('error', __('Error!') . __('Problem with shipping_method'));
                }
            }

            $model->save();

            // TODO: tracking
            // if ($model->tracking !== $old_tracking && $model->paypal_id) { // new tracking code
            //     //Paypal API
            //     $provider = \PayPal::setProvider();
            //     $provider->setApiCredentials(config('paypal')); // Pull values from Config
            //     $token = $provider->getAccessToken();
            //     $provider->setAccessToken($token);

            //     $tracker = [
            //         'transaction_id' => $model->paypal_id,
            //         'tracking_number' => $model->tracking,
            //         'status' => 'SHIPPED',
            //         'carrier' => (isset(\App\Cart::SHIPPING_METHODS[$model->shipping_method]['code']) ? \App\Cart::SHIPPING_METHODS[$model->shipping_method]['code'] : $model->shipping_method)
            //     ];

            //     // The trackers array, the API can handle multiple transactions at a time.
            //     $trackers = ['trackers' => array($tracker)];

            //     $result = $provider->addBatchTracking($trackers);

            //     if (!empty($result['errors'])) {
            //         Log::error(serialize($result['errors']));
            //     } else {
            //         if ($model->shipping_email) {

            //             try {
            //                 Mail::to($model->shipping_email, $model->shipping_name)->send(new TrackedOrder($this->formatOrder($model)));

            //                 if (request()->ajax()) {
            //                     return [
            //                         'success' => true,
            //                         'result' => 'Success',
            //                     ];
            //                 }

            //                 if (!$model->notified) {
            //                     $model->notified = 2;
            //                     $model->save();
            //                 }


            //                 return redirect()->back()->with('flash_message_success', 'Notification sent');
            //             } catch (\Throwable $e) {
            //                 Log::error("Order mail - ", [$e]);
            //             }
            //         }
            //     }
            // }

            DB::commit();

            return redirect()->route('orders.edit', $id)->with('success', __('Success!'));
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('error', __('Error!') . $e->getMessage());
        }
    }

    public function updateProduct(Request $request, OrderProduct $order_product)
    {
        $order_product->update([
            'count' => $request->input('count'),
        ]);

        $order_product->order->recalculateTotals();

        return redirect()->back();
    }

    public function addProduct(Request $request)
    {
        try {

            if (!$request->has('product_id', 'order_id') || (int)$request->input('count') < 1) {
                return redirect()->back()->with('error', __('Insufficient quantity'));
            }

            $product = Product::where('id', $request->input('product_id'))->with(['translations', 'prices'])->first();

            //get prices

            if ($product) {

                $order_products = OrderProduct::query()->where([
                    'order_id' => $request->input('order_id'),
                    'product_id' => $request->input('product_id'),
                    'option_id' => $request->input('option_id') ?? NULL,
                ])->first();

                if ($order_products) {

                    $order_products->update([
                        'count' => (int)$request->input('count'),
                    ]);

                    $order_products->order->recalculateTotals();
                } else {

                    $model = new OrderProduct();

                    $model->fill($request->all());

                    //prices, same in app/Models/Cart:calculatePrice
                    $result = [
                        'price' => 0,
                        'special' => 0,
                        'cost' => 0,
                    ];

                    if ($product) {
                        $result = [
                            'price' => $product->old_price > 0 ? $product->old_price : $product->price,
                            'special' => $product->old_price > 0 ? $product->price : 0,
                            'cost' => $product->cost,
                        ];
                    }

                    if ($product && $request->input('option_id')) {
                        $option_id = $request->input('option_id');

                        $product_option_query = Product::find($request->input('product_id'))->whereHas('prices', function ($builder) use ($option_id) {
                            $builder->where('id', $option_id);
                        })
                            ->with([
                                'prices' => function ($builder) use ($option_id) {
                                    $builder->where('id', $option_id);
                                    $builder->with('productAttributes');
                                }
                            ]);

                        $product_option = $product_option_query->first()->prices->first();

                        if ($product_option) {
                            $model->option_data = json_encode($product_option, JSON_UNESCAPED_UNICODE);

                            $result = [
                                'price' => $product_option->old_price > 0 ? $product_option->old_price : $product_option->price,
                                'special' => $product_option->old_price > 0 ? $product_option->price : 0,
                                'cost' => $product_option->cost,
                            ];
                        }
                    }

                    $rate = Cart::getCurrencies()[$product->currency ?: config('app.currency')]['rate'] ?? 1;

                    //prices in default currency
                    $result['price'] *= $rate;
                    $result['special'] *= $rate;
                    $result['cost'] *= $rate;

                    $model->price = $result['price'];
                    $model->special = $result['special'];
                    $model->cost = $result['cost'];
                    $model->name = $product->translate()->name;

                    $model->save();

                    $model->order->recalculateTotals();
                }
            }

            return redirect()->back()->with('success', __('Added successfully!'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', __('Error!'));
        }
    }

    public function deleteProduct(Request $request, OrderProduct $order_product)
    {
        $new_subtotal = $order_product->order->subtotal - $order_product->total_price;
        // TODO: create one function for order`s totals update
        $order_product->order->update([
            'subtotal' => $new_subtotal,
        ]);
        $order = $order_product->order;

        $order_product->delete();

        $order->recalculateTotals();

        return redirect()->back();
    }

    public function trash(Order $order)
    {
        $order->update(['order_status_id' => 7]); //trash

        return redirect()->route('orders.index')->with('success', __('Deleted successfully!'));
    }

    public function destroy(Order $order)
    {
        $order->products()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $model = Order::query()->where('id', $id)->first();
                if ($model->order_status_id == 7) { //trash
                    $model->products()->delete();
                    $model->delete();
                } else {
                    $model->update(['order_status_id' => 7]); //trash
                }
            }

            return redirect()->route('orders.index', ['cart' => 'trash'])->with('success', __('Deleted successfully!'));
        }

        return redirect()->route('orders.index')->with('error', __('Error!'));
    }

    public function updateSelected(OrderStatusRequest $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $model = Order::query()->where('id', $id)->first();
                if ($model) {
                    $model->update(['order_status_id' => $request->status_id]);
                }
            }

            return redirect()->route('orders.index')->with('success', __('Updated successfully!'));
        }

        return redirect()->route('orders.index')->with('error', __('Error!'));
    }

    public function invoiceCreate($id)
    {
        $logo = app(Setting::class)->get('logo_for_pdf', config('translatable.locale'));

        $contactsTabs = json_decode(app(Setting::class)->get('contacts'), true);

        if ($contactsTabs && is_array($contactsTabs) && count($contactsTabs)) {
            foreach ($contactsTabs as $contactTab) {
                if (isset($contactTab['is_main']) && $contactTab['is_main'] == "1") {
                    $contacts = $contactTab;
                }
            }
        }

        $order = Order::query()
            ->where('id', $id)
            ->first();


        $pdf = Pdf::loadView('ecom::admin.orders.invoice-template', [
            'logo'     => $logo,
            'contacts' => $contacts ?? [],
            'order'    => $order,
        ])
            ->setPaper('A4')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'sans-serif']);

        $pdf->getDomPDF()->set_option('default_charset', 'UTF-8');

        return $pdf->download('invoice_' . $order->id . '_' . Carbon::now()->format('d-m-Y') . '.pdf');
    }
}
