<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use Owlwebdev\Ecom\Models\Cart;
use App\Models\User;
use Owlwebdev\Ecom\Models\Order;
use Owlwebdev\Ecom\Models\Coupon;
use Owlwebdev\Ecom\Models\OrderProduct;
use App\Modules\Setting\Setting;
use App\Mail\Order\AdminOrder;
use App\Mail\Order\CustomerOrder;
use App\Service\TelegramBot;
use App\Http\Requests\CartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\CartCouponRequest;
use App\Http\Requests\CartDeleteRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Requests\CartCheckUserRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
// use LiqPay; //TODO: fix Liqpay

class CartController extends Controller
{
    use ResponseTrait;

    protected $cart;

    protected $order;

    protected $order_product;

    protected $coupon;

    protected $user;

    private $bot;

    // private $liqpay;

    public function __construct(Cart $cart, Order $order, OrderProduct $order_product, Coupon $coupon, User $user, TelegramBot $bot/*, LiqPay $liqpay*/)
    {
        $this->cart = $cart;

        $this->order = $order;

        $this->order_product = $order_product;

        $this->coupon = $coupon;

        $this->user = $user;

        $this->bot = $bot;

        // $this->liqpay = $liqpay;
    }

    public function getData(array $request)
    {
        if (empty($request)) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($request['lang'])) {
            $lang = $request['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        return $request;
    }

    /* Getting refreshed cart */
    private function getCart(array $request, $user = [], $shipping_methods = [], $payment_methods = [])
    {
        $cart_id = isset($request['cart_id']) ? $request['cart_id'] : $request['temp_cart_id'];

        $coupon = isset($request['coupon']) ? $request['coupon'] : '';

        $comment = isset($request['comment']) ? $request['comment'] : '';

        $total_price = $this->cart->getTotalCartCost($cart_id);

        $coupon_model = $this->coupon->findForOrder((string) $coupon);
        $discount = false;
        $discount_name = '';

        $valid_coupon = !is_null($coupon_model);

        $currency = config('app.currency');

        $user = auth('api')->user();

        if ($user && $user->discount) {
            $discount = $user->discount->percentage;
            $discount_name = $request['discount'] = $user->discount->translate(app()->getLocale())->name;
        }

        if ($coupon_model) {
            $request['coupon'] = (string) $coupon_model->translate(app()->getLocale())->name;
        }


        $total_price_with_coupon = 0; //!is_null($coupon_model) ? $this->cart->getTotalCartCostWithCoupon($request->input('cart_id'), $coupon_model->value, $coupon_model->type == 'percent' ? true : false) : $total_price;
        $total_price_with_discount = 0;

        $products = [];

        $free_products = [];

        $items_query = $this->cart->query();

        $items_query
            ->with([
                'product' => function ($query) {

                    // $query->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
                    // ->where('product_translations.lang', app()->getLocale());

                    $query->select([
                        'products.*',
                        // 'product_translations.name',
                    ]);
                },
                'product.prices.productAttributes',
                'product.images',
                'product.translations',
            ]);

        $items = $items_query->where('session_id', $cart_id)->get();

        foreach ($items as $item) {

            $price = 0;
            $special = 0;

            // if product or price is deleted(not set), remove it from cart
            try {
                $price = $item->total_price;
                $special = $item->total_special;
            } catch (\Exception $e) {
                $item->delete();
                continue;
            }

            if ($item->product == null) {
                $item->delete();
            }

            // check availability
            $alert = '';

            if ($item->product->quantity < $item->count) {
                $alert = trans_choice('There are not enough products to purchase, maximum :count', $item->product->quantity);
            }

            $image = $item->product['image'];

            $option_data = [];

            if ($item->option_id) {
                $alert = '';

                $option_data = $item->product['prices']->where('id', $item->option_id)->first();

                if ($option_data->count < $item->count) {
                    $alert = trans_choice('There are not enough products to purchase, maximum :count', $option_data->count);
                }

                $rate = Cart::getCurrencies()[$item->product['currency'] ?? $currency]['rate'] ?? 1;

                $option_data->price = $this->cart->formatPrice($option_data->price * $rate);
                $option_data->old_price = $this->cart->formatPrice($option_data->old_price * $rate);

                //option image from images array
                $image = $option_data->image;
                $option_image_data = $item->product->images->where('price_id', $item->option_id)->first();

                if (!empty($option_image_data)) {
                    $option_data->image = $option_image_data->image;
                    $image = $option_image_data->image;
                } else {
                }
            }

            if ($coupon_model) {
                $current_price = $item->current_price; //Actual price

                //get % value of fixed value coupon for current product
                if ($coupon_model->type == 'fixed') {
                    // : ($current_price >= $coupon_model->value ? $current_price - $coupon_model->value : 0))
                    $all_products_price_sum = $total_price - $coupon_model->value;

                    if ($all_products_price_sum < 1) {
                        $coupon_value = 100;
                    } else {

                        $product_percent_from_order = ($current_price * $item->count) * 100 / $total_price;

                        $product_price_sum_with_discount = $all_products_price_sum * $product_percent_from_order / 100;

                        $one_product_price = $product_price_sum_with_discount / $item->count;

                        $special_for_one = $one_product_price;
                    }
                } else {
                    $special_for_one = $current_price - ($current_price * $coupon_model->value / 100);
                }


                $special = number_format(round(($special_for_one * $item->count), 0), 0, '.', ''); // 0

                $total_price_with_coupon += $special;
            }

            //apply discount per product after coupon
            if ($discount) {
                $current_price = $special > 0 ? $special : $price;
                $new_special = $current_price - ($current_price * $discount / 100); // TODO: make method for different types of discount, not only -%
                $special = $new_special;

                $total_price_with_discount += $special;
            }

            $product = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'code' => $item->product->code,
                'name' => $item->product->translate(app()->getLocale())->name,
                'url' => $item->product->frontLink(),
                'count' => $item->count,
                'currency' => $currency,
                'price' => $price,
                'special' => $special ?: null,
                'image' => $image,
                'saving' => $special && $price != 0 ? round(($price - $special) * 100 / $price) . '%' : '',
                'option' => $option_data,
                'alert' => $alert,
            ];
            $products[] = $product;
        }

        // TODO: AUTODETECT user location

        $info = [
            'country' => 'Ukraine', //name, must be in list
            'region' => null
        ];

        $shipping_fields = Cart::SHIPPING_FIELDS;

        if ($user) {
            $user = $this->returnUser($user);
        }

        //format
        return $this->successResponse(compact(
            'products',
            'free_products',
            'coupon',
            'discount_name',
            'comment',
            'total_price',
            'total_price_with_coupon',
            'total_price_with_discount',
            'valid_coupon',
            'user',
            'shipping_methods',
            'payment_methods',
            'info',
            'cart_id',
            'shipping_fields',
            'currency'
        ));
    }
    /* End Getting refreshed cart */

    private function makeOrder(array $request, User $user)
    {
        $rate = Cart::getCurrencies()[config('app.currency')]['rate'] ?? 1;

        //shipping price
        $shipping = 0;
        if (isset($request['shipping_method']) && $request['shipping_method']) {
            $shipping = $this->getShippingMethods($request['shipping_method']);
        }

        $replaceable_fields = ['name', 'lastname', 'email', 'phone'];

        foreach ($replaceable_fields as $field) {
            if (!isset($request['shipping_' . $field]) || empty($request['shipping_' . $field])) {
                $request['shipping_' . $field] = isset($request[$field]) ? $request[$field] : '';
            }
        }

        $order_data = [
            'user_id'            => $user->id,
            'shipping'           => $shipping,
            'currency'           => config('app.currency'),

            'shipping_method'    => $request['shipping_method'] ?? '',
            'payment_name'       => isset(Cart::PAYMENT_METHODS[$request['payment_method'] ?? '']['name']) ? Cart::PAYMENT_METHODS[$request['payment_method'] ?? '']['name'] : '---',
            'payment_method'     => $request['payment_method'] ?? '',
            'order_status_id'    => isset($request['order_status_id']) ? $request['order_status_id'] : 1,

            'shipping_name'      => $request['shipping_name'] ?? '',
            'shipping_lastname'  => $request['shipping_lastname'] ?? '',
            'shipping_email'     => $request['shipping_email'] ?? '',
            'shipping_phone'     => $request['shipping_phone'] ?? '',

            'shipping_company'   => $request['company'] ?? '',
            'shipping_country'   => $request['shipping_country'] ?? '',
            'shipping_city'      => $request['shipping_city'] ?? '',
            'shipping_city_id'   => $request['shipping_city_id'] ?? '',
            'shipping_address'   => $request['shipping_address'] ?? '',
            'shipping_street'    => $request['shipping_street'] ?? '',
            'shipping_house'     => $request['shipping_house'] ?? '',
            'shipping_apartment' => $request['shipping_apartment'] ?? '',
            'shipping_postcode'  => $request['shipping_postcode'] ?? '',
            'shipping_province'  => $request['shipping_province'] ?? '',
            'shipping_branch'    => $request['shipping_branch'] ?? '',
            'shipping_branch_id' => $request['shipping_branch_id'] ?? '',
        ];

        $coupon = $request['coupon'] ?? '';
        $coupon_model = $this->coupon->findForOrder((string) $coupon);


        if ($coupon_model) {
            $order_data['coupon_id'] = $coupon_model->id;
            //discount texts
            $order_data['discount'] = $coupon_model->translate(app()->getLocale())->name;

            $subtotal = $this->cart->getTotalCartCostWithCoupon($request['cart_id'], $coupon_model->value, $coupon_model->type == 'percent' ? true : false);
        } else {
            $subtotal = $this->cart->getTotalCartCost($request['cart_id']);
        }

        if ($user && $user->discount) {
            $order_data['discount_id'] = $user->discount->id;

            if (isset($order_data['discount']) && !empty($order_data['discount'])) {
                $order_data['discount'] .= ', ' . $user->discount->translate(app()->getLocale())->name;
            } else {
                $order_data['discount'] = $user->discount->translate(app()->getLocale())->name;
            }
        }

        if ($request['comment'] ?? '') {
            $order_data['comment'] = $request['comment'];
        }

        if ($request['utm'] ?? '') {
            $order_data['utm_data'] = $request['utm'];
        }

        // TODO: make total calculation method
        $order_data['subtotal'] = $subtotal;
        $order_data['total'] = $subtotal + $shipping;

        $order = $this->order->updateOrCreate(['id' => $request['order_id'] ?? null], $order_data);

        $items_query = $this->cart->query();

        $items_query
            ->with([
                'product' => function ($query) {

                    $query->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
                        ->where('product_translations.lang', app()->getLocale());

                    $query->select([
                        'products.*',
                        'product_translations.name',
                    ]);
                },
                'product.prices.productAttributes',
            ]);

        $items = $items_query->where('session_id', $request['cart_id'])->get();

        $items->each(function ($item, $key) use ($order, $coupon_model, $rate, $user) {
            $coupon_price = 0;
            $price = 0;
            $special = 0;
            $cost = 0;

            // get prices, calculated in Cart model.
            $price = $item->price;
            $special = $item->special_price;
            $cost = $item->cost;

            $image = $item->product['image'];

            $option_data = [];

            if ($item->option_id) {
                $option_data = $item->product['prices']->where('id', $item->option_id)->first();

                $option_data->price = $this->cart->formatPrice($option_data->price * $rate);
                $option_data->old_price = $this->cart->formatPrice($option_data->old_price * $rate);
                $option_data->cost = $this->cart->formatPrice($option_data->cost * $rate);

                //option image from images array
                $image = $option_data->image;
                $option_image_data = $item->product->images->where('price_id', $item->option_id)->first();

                if (!empty($option_image_data)) {
                    $option_data->image = $option_image_data->image;
                    $image = $option_image_data->image;
                }
            }

            $this->order_product->updateOrCreate(
                [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'option_id' => $item->option_id,
                ],
                [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'option_id' => $item->option_id,
                    'option_data' => json_encode($option_data, JSON_UNESCAPED_UNICODE),
                    'name' => $item->product['name'],
                    'icon' => $image,
                    'price' => $price,
                    'special' => $special,
                    'coupon_price' => $coupon_price,
                    'cost' => $cost,
                    'count' => $item->count,
                ]
            );
        });

        return $order;
    }

    public function checkUser(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        if (!isset($decodedJson['email'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['email']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validateFields = [
            'cart_id' => 'required|string',

            'name'     => 'nullable|string|between:2,100',
            'lastname' => 'nullable|string|between:2,100',
            'phone'    => 'nullable|string|between:2,100',
            'email'    => 'required|string|email|max:100',

            'coupon' => 'sometimes|string|between:1,500',
        ];

        $validator = Validator::make($decodedJson, $validateFields);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, $validator->errors()->toArray()),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validatedData = $validator->validated();

        $user = $this->user->where('email', $validatedData['email'])->first();

        if ($user) {
            // dont update
            if ($user->status == 0) {
                $user->update([
                    'name' => isset($validatedData['name']) && !empty($validatedData['name']) ? $validatedData['name'] : $user->name,
                    'lastname' => isset($validatedData['lastname']) && !empty($validatedData['lastname']) ? $validatedData['lastname'] : $user->name,
                    'phone' => !empty($validatedData['phone']) ? $validatedData['phone'] : $user->phone,
                ]);
            }
        } else {
            $user = $this->user->create([
                'name' => isset($validatedData['name']) && !empty($validatedData['name']) ? $validatedData['name'] : 'User',
                'lastname' => isset($validatedData['lastname']) && !empty($validatedData['lastname']) ? $validatedData['lastname'] : 'Unregistered',
                'phone' => $validatedData['phone'] ?? '',
                'email' => $validatedData['email'] ?? '',
                'status' => User::STATUS_NOT_ACTIVE,
                'password' => Hash::make(Str::random(16)),

                //discount_id
            ]);
        }

        $validatedData['order_status_id'] = 1; // Dropped for now

        $order = $this->makeOrder($validatedData, $user);

        return $this->successResponse([
            'user' => $this->returnUser($user),
            'order_id' => $order->id,
        ]);
    }

    public function add(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        if (!isset($decodedJson['product_id'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['product_id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['cart_id']) || (isset($decodedJson['cart_id']) && is_null($decodedJson['cart_id']))) { //fix old cart_id cookie problem
            $tmp = Str::random();
            $decodedJson['temp_cart_id'] = $tmp;
        }

        if (isset($decodedJson['option_id'])) {
            $cart = $this->cart->where([
                'user_id' => Auth::user() ? Auth::user()->id : null,
                'session_id' => isset($decodedJson['cart_id']) ? $decodedJson['cart_id'] : $decodedJson['temp_cart_id'],
                'product_id' => $decodedJson['product_id'],
                'option_id' => $decodedJson['option_id'],
            ])->first();
        } else {
            $cart = $this->cart->where([
                'user_id' => Auth::user() ? Auth::user()->id : null,
                'session_id' => isset($decodedJson['cart_id']) ? $decodedJson['cart_id'] : $decodedJson['temp_cart_id'],
                'product_id' => $decodedJson['product_id'],
                'option_id' => null,
            ])->first();
        }

        if ($cart) {
            $cart->increment('count');
            $cart->save();
        } else {
            $cart = $this->cart->create([
                'user_id' => Auth::user() ? Auth::user()->id : null,
                'session_id' => isset($decodedJson['cart_id']) ? $decodedJson['cart_id'] : $decodedJson['temp_cart_id'],
                'product_id' => $decodedJson['product_id'],
                'count' => $decodedJson['count'],
                'option_id' => $decodedJson['option_id'] ?? null,
            ]);
        }

        return $this->getCart($decodedJson);
    }

    public function applyCoupon(Request $request)
    { // TODO: limit requests
        $decodedJson = $this->getData($request->json()->all());

        if (!isset($decodedJson['coupon'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['coupon']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $coupon_model = $this->coupon->findForOrder((string) $decodedJson['coupon']);

        if (is_null($coupon_model)) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND, ['coupon']),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->getCart($decodedJson);
    }

    public function update(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        if (!isset($decodedJson['cart_id'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['cart_id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['id'])) { // product cart id
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($decodedJson['count'] < 1) {
            $decodedJson['count'] = 1;
        }

        $cart = $this->cart
            ->where('session_id', $decodedJson['cart_id'])
            ->find($decodedJson['id']);

        if ($cart) {
            $cart->update([
                'count' => $decodedJson['count'],
            ]);
        } else {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        }


        return $this->getCart($decodedJson);
    }

    public function delete(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        if (!isset($decodedJson['cart_id'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['cart_id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['id'])) { // product cart id
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $cart = $this->cart->whereId($decodedJson['id'])->first();

        if ($cart) {
            $cart->delete();
        } else {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->getCart($decodedJson);
    }

    public function count(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        $count = 0;
        $total_price = 0;

        if (isset($decodedJson['cart_id'])) {
            $count = $this->cart->where('session_id', $decodedJson['cart_id'])->sum('count');
            $total_price = $this->cart->getTotalCartCost($decodedJson['cart_id']);
        }

        $user = auth('api')->user();
        $coupon = $decodedJson['coupon'] ?? '';
        $coupon_model = $this->coupon->findForOrder((string) $coupon);

        // TODO: make method for totals calculation
        if ($coupon_model) {
            $total_price = $this->cart->getTotalCartCostWithCoupon($decodedJson['cart_id'], $coupon_model->value, $coupon_model->type == 'percent' ? true : false);
        }

        if ($user && $user->discount) {
            $total_price = $total_price - ($total_price *  $user->discount->percentage / 100); // TODO: make method for different types of discount, not only -%
        }

        return $this->successResponse([
            'count' => $count,
            'total_price' => $total_price,
        ]);
    }

    public function checkout(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        $user = auth('api')->user();

        if ($user) {
            $user = $this->returnUser($user);
        }
        $shipping_methods = $this->getShippingMethods();

        $payment_methods = $this->getPaymentMethods();

        return $this->getCart($decodedJson, $user, $shipping_methods, $payment_methods);
    }

    public function returnUser(User $user)
    {
        return [
            'name'  => $user->name,
            'lastname'  => $user->lastname,
            'phone' => $user->phone,
            'email' => $user->email,
            'birthday' => $user->birthday,
            'addresses' => $user->addresses,
            // TODO: add other user info, address etc.
        ];
    }

    public function checkUserEmail(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());
        $user = auth('api')->user();

        if ($user) {
            return response()->json(['status' => 'good']);
        }

        if (!isset($decodedJson['email'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['email']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($this->user->where('email', $decodedJson['email'])->active()->first()) {
            return response()->json(['status' => 'need_login']);
        } else {
            return response()->json(['status' => 'good']);
        }
    }

    public function complete(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        $validateFields = [
            'cart_id' => 'required|string',
            'order_id' => 'sometimes|numeric',

            'name'     => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'phone'    => 'string|between:2,100',
            'email'    => 'required|string|email|max:100',

            'shipping_method' => 'required|string|max:100',
            'payment_method' => 'required|string|max:100',

            'shipping_name' => 'sometimes|string|between:2,100',
            'shipping_lastname' => 'sometimes|string|between:2,100',
            'shipping_phone' => 'sometimes|string|between:2,100',
            'shipping_email' => 'sometimes|string|email|max:100',
            'shipping_company' => 'sometimes|string|between:2,100',
            'shipping_country' => 'sometimes|string|between:2,100',
            'shipping_province' => 'sometimes|string|between:2,100',
            'shipping_city' => 'nullable|string|between:2,100',
            'shipping_city_id' => 'sometimes|string|between:2,100',
            'shipping_address' => 'sometimes|string|between:1,100',
            'shipping_street' => 'nullable|string|between:1,100',
            'shipping_house' => 'nullable|string|between:1,100',
            'shipping_apartment' => 'nullable|string|between:1,100',
            'shipping_postcode' => 'sometimes|string|between:1,100',
            'shipping_branch' => 'nullable|string|between:2,100',
            'shipping_branch_id' => 'nullable|string|between:2,100',


            'billing_name' => 'sometimes|string|between:2,100',
            'billing_email' => 'sometimes|string|between:2,100',
            'billing_country' => 'sometimes|string|between:2,100',
            'billing_province' => 'sometimes|string|between:2,100',
            'billing_city' => 'sometimes|string|between:2,100',
            'billing_address' => 'sometimes|string|between:1,100',
            'billing_apartment' => 'sometimes|string|between:1,100',
            'billing_phone' => 'sometimes|string|between:1,100',
            'billing_postcode' => 'sometimes|string|between:1,100',

            'coupon' => 'nullable|string|between:1,500',
            'comment' => 'nullable|string|between:2,500',
            'utm_data' => 'nullable|string|between:2,255',
        ];

        $validator = Validator::make($decodedJson, $validateFields);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, $validator->errors()->toArray()),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validatedData = $validator->validated();


        if (!isset($validatedData['cart_id'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['cart_id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Get user for order information */
        $user = auth('api')->user();

        if ($user) {
            // if (empty($user->phone)) {
            //     $user->update([
            //         //'name' => $request['name'],
            //         'phone' => $request['phone'],
            //         //'country' => $request['country'],
            //         //'city' => $request['city'],
            //         //'address' => $request['nova_poshta_branch'],
            //         //'apartment' => $request['apartment'],
            //         //'postcode' => $request['postcode'],
            //     ]);
            // }

            // if ($request->has('save_info') && $request['save_info'] === 'on'  || empty($user->phone)) {
            //     $user->update([
            //         //'name' => $request['name'],
            //         //'phone' => $request['phone'],
            //         'company' => $request['company'],
            //         'country' => $request['country'],
            //         'city' => $request['city'],
            //         'address' => $request['address'],
            //         'apartment' => $request['apartment'],
            //         'postcode' => $request['postcode'],
            //     ]);
            // }
        } elseif ($this->user->where('email', $validatedData['email'])->active()->first()) {
            //let users skip login but loose orders
            $user = $this->user->notActive()->first();

            // return $this->errorResponse(
            //     ErrorManager::buildError(VALIDATION_UNAUTHORIZED),
            //     Response::HTTP_UNAUTHORIZED
            // );
        } else { // shadow registration
            $user = $this->user->updateOrCreate(
                [
                    'email' => $validatedData['email'],
                    'status' => User::STATUS_NOT_ACTIVE,
                    'role' => null
                ],
                [
                    'name' => $validatedData['name'] ?? 'noname',
                    'lastname' => $validatedData['lastname'] ?? 'nolastname',
                    'email' => $validatedData['email'],
                    'password' => Hash::make(Str::random(12)),
                    'phone' => $validatedData['phone'],
                    // 'country' => $validatedData['country'] ?? '',
                    // 'city' => $validatedData['city'] ?? '',
                    // 'address' => $validatedData['address'] ?? '',
                    // 'apartment' => $validatedData['apartment'] ?? '',
                    // 'postcode' => $validatedData['postcode'] ?? '',
                    'status' => User::STATUS_NOT_ACTIVE,
                ]
            );
        }
        /* End Get user for order information */

        /* Create order */
        $validatedData['order_status_id'] = 2; //Wait status

        // TODO: take utm from front
        // if (session('utm')) {
        //     $utm_data = session('utm');
        //     $utm_data = json_decode($utm_data, true);
        //     $utm_data['time'] = time() - strtotime($utm_data['created_at']);
        //     $utm_data['complete_time'] = time();
        //     $validatedData['utm'] = json_encode($utm_data);
        // }

        $order = $this->makeOrder($validatedData, $user);
        /* End Create order */

        /* Return LiqPay form for redirect to payment site */
        if (in_array($order->payment_method, ['liqpay'])) {
            /* PAYPAL FORMATIONG */
            // $items = [];
            // $desc = 'Products: ';
            // if ($order->products()) {
            //     foreach ($order->products()->get() as $product) {
            //         if (strlen($desc . ' ' . $product->name) < 127) { //Maximum description length: 127.
            //             $desc .= $product->name . ' ';
            //         }
            //         $price = $product->price;
            //         $special = $product->special;
            //         if ($product->coupon_id) {
            //             $price = $product->price;
            //             $special = $product->coupon_price;
            //         }
            //         $items[] = [
            //             'name'        => $product->name,
            //             'description' => '#' . $product->product_id . '-' . $product->pattern_id . '-' . $product->size_id,
            //             'sku'         => '#' . $product->product_id . '-' . $product->pattern_id . '-' . $product->size_id,
            //             'unit_amount' => strval(($special ?: $price)),
            //             'quantity'    => $product->count,
            //             'category'    => 'PHYSICAL_GOODS',
            //         ];
            //     }
            // }
            // $paypal_order = [
            //     'intent' => 'CAPTURE',
            //     'payer' => [
            //         'name' => [
            //             'given_name' => $order->shipping_name,
            //             'surname' => $order->shipping_lastname,
            //         ],
            //         'address' => [
            //             'address_line_1' => $order->shipping_address,
            //             'address_line_2' => $order->shipping_apartment,
            //             'admin_area_1' => $order->shipping_province, //Obl or something else
            //             'admin_area_2' => $order->shipping_city,
            //             'postal_code' => $order->shipping_postcode,
            //             'country_code' => $order->shipping_country,
            //         ],
            //         'email_address' => $order->shipping_email,
            //         'phone' => [
            //             'phone_type' => "MOBILE",
            //             'phone_number' => [
            //                 'national_number' => str_replace('+', '', $order->shipping_phone),
            //             ],
            //         ],
            //     ],
            //     'purchase_units' => [[
            //         'custom_id' => 'order_' . $order->id,
            //         'description' => $desc,
            //         'amount' => [
            //             'currency_code' => 'USD',
            //             'value' => "$order->total",
            //             'breakdown' => [
            //                 'item_total' =>
            //                 array(
            //                     'currency_code' => 'USD',
            //                     'value' => "$order->subtotal",
            //                 ),
            //                 'shipping' =>
            //                 array(
            //                     'currency_code' => 'USD',
            //                     'value' => "$order->shipping",
            //                 ),
            //                 'handling' =>
            //                 array(
            //                     'currency_code' => 'USD',
            //                     'value' => "0",
            //                 ),
            //                 'tax_total' =>
            //                 array(
            //                     'currency_code' => 'USD',
            //                     'value' => "0",
            //                 ),
            //                 'shipping_discount' =>
            //                 array(
            //                     'currency_code' => 'USD',
            //                     'value' => "0",
            //                 ),
            //             ],
            //         ],
            //         'shipping' => [
            //             //'method' => "Ukrposhta - Ukraine's National Post", //TODO: put valid method
            //             'type' => "SHIPPING",
            //             'name' => [
            //                 'full_name' => $order->shipping_name . ' ' . $order->shipping_lastname,
            //             ],
            //             'address' => [
            //                 'address_line_1' => $order->shipping_address,
            //                 'address_line_2' => $order->shipping_apartment,
            //                 'admin_area_1' => $order->shipping_province,
            //                 'admin_area_2' => $order->shipping_city,
            //                 'postal_code' => $order->shipping_postcode,
            //                 'country_code' => $order->shipping_country,
            //             ],
            //         ],
            //         //'items' => $items,
            //         // 'shipping' => [
            //         //     //'method' => "Ukrposhta - Ukraine's National Post", //TODO: put valid method
            //         //     'type' => "SHIPPING",
            //         //     'address' => [
            //         //         'address_line_1' => $order->shipping_address,
            //         //         'address_line_2' => $order->shipping_apartment,
            //         //         //'admin_area_1' => $order->shipping_city,
            //         //         'admin_area_2' => $order->shipping_city,
            //         //         'postal_code' => $order->shipping_postcode,
            //         //         'country_code' => $order->shipping_country,
            //         //     ],
            //         // ],
            //     ]],
            //     'application_context' => [
            //         'cancel_url' => route('cart.thankyou'), // TODO: make cancel page
            //         'return_url' => route('cart.thankyou'),
            //     ]
            // ];
            $temp_order = $order->id . '_' . Str::random(12);
            $data = [
                'version'     => '3',
                'action'      => 'pay',
                'amount'      => $order->total,
                'currency'    => 'UAH',
                // 'paytypes'    => 'card', // optional, from settings in Liqpay Store settings
                'description' => __('Payment for order #:order_id', ['order_id' => $temp_order]),
                'order_id'    => $temp_order,
                'result_url'  => url('cart/thankyou'),
                'server_url'  => route('liq.pay.callback'),
                'language'    => app()->getLocale(),
            ];

            // $liqform = $this->liqpay->cnb_form($data);

            return $this->successResponse([
                'type' => 'form',
                // 'form'    => $liqform,
            ]);
        } else { //other payments methods
            $this->sendMails($order);

            return $this->successResponse([
                'order' => $this->formatOrder($order),
            ]);
        }
    }

    public function liqPayCallback(Request $request)
    {
        if ($request->has('data')) {
            $result = json_decode(base64_decode($request->input('data')));

            $order_id = substr($result->order_id, 0, strpos($result->order_id, '_'));

            $order = $this->order->findOrFail($order_id);

            // $sign = $this->liqpay->cnb_signature($request->input('data'));

            // if ($order && $request->input('signature') && $sign && ($request->input('signature') === $sign)) {
            //     if ($result->status === 'success') {

            //         $order->update(['order_status_id' => 4]);

            //         /* TODO: Clear front cart_id, coupon */

            //         /* Cart cleaning */
            //         $this->cart->where('session_id', $request->input('cart_id'))->delete();
            //         /* End Cart cleaning */

            //         $this->sendMails($order);
            //     }

            //     if ($result->status === 'error' || $result->status === 'failure') {
            //         Log::info('Liqpay error');
            //         $order = $this->order->findOrFail($order_id);

            //         $order->update(['order_status_id' => 3]);
            //     }
            // } else {
            //     Log::error('Liqpay callback not valid data');
            // }
        }
    }

    protected function sendMails(Order $order)
    {
        // try {
        //     Mail::to($order->shipping_email, $order->shipping_name)->send(new CustomerOrder($this->formatOrder($order)));

        //     Mail::to(option('email_service', config('mail.from.address')), config('mail.from.name'))->send(new AdminOrder($this->formatOrder($order)));
        // } catch (\Throwable $e) {
        //     Log::error("Order mail - ", [$e]);
        // }

        //tlg
        try {
            $tg_arr = [];
            // $tg_arr['new_order'] = htmlentities(' <a href="' . route('orders.edit', ['order' => $order->id]) . '" >#' . $order->id . '</a>'); //encode html
            $tg_arr['Нове замовлення'] = ' <a href="' . route('orders.edit', ['order' => $order->id]) . '">#' . $order->id . '</a>';
            // $tg_arr['new_order'] = ' #' . $order->id;
            // $tg_arr['empty_addr'] = $order->shipping_city . ', ' . $order->shipping_address;
            if ($order->products()) {
                foreach ($order->products()->get() as $key => $product) {
                    if ($product->option_id && !empty($product->option)) {
                        // $option_name = '';

                        // foreach ($product->option['product_attributes'] as $attr) {
                        //     $option_names[] = $attr['attr_name'] . ': ' . $attr['value'];
                        // }

                        // $option_name = implode(', ', $option_names)

                        $tg_arr['empty_' . $product->product_id . '-' . $product->option['id'] . '-' . $key] = $product->name . '(' . $product->option['name'] . ') * ' . $product->count . ' = ' . $product->total_price;
                    } else {
                        $tg_arr['empty_' . $product->product_id . '-' . $key] = $product->name . ' * ' . $product->count . ' = ' . $product->total_price;
                    }
                    // $tg_arr['empty_spacer' . $key] = ' ';

                }
            }
            $tg_arr['empty_spacer'] = ' ';
            // $tg_arr['Без знижок:'] = $order->full_subtotal_price;
            $tg_arr['Разом:'] = $order->subtotal;
            if ($order->discount) {
                $tg_arr['Знижки:'] = $order->discount;
            }
            $tg_arr['Доставка:'] = (isset(Cart::SHIPPING_METHODS[$order->shipping_method]['name']) ? Cart::SHIPPING_METHODS[$order->shipping_method]['name'] : $order->shipping_method); // $order->shipping_method == 0 ? __('free') : $order->shipping;
            $tg_arr['Всього:'] = $order->total;

            $txt = '';
            foreach ($tg_arr as $key => $value) {
                $txt .= (strpos($key, 'empty') === 0 ? "" : __($key) . " ") . $value . PHP_EOL; //telegram
            };
            $this->bot->sendMessage($txt, 1);
        } catch (\Throwable $e) {
            Log::error("Order tg - ", [$e]);
        }
    }

    public function thankYou(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        if (isset($decodedJson['i']) && isset($decodedJson['d']) && $decodedJson['i'] && $decodedJson['d']) {
            $order = $this->order->find(['id' => ($decodedJson['i'] / 2 - 33), 'created_at' => base64_decode($decodedJson['d'])])->first();
            if ($order) {
                $shipping_methods = $this->getShippingMethods();

                $payment_methods = $this->getPaymentMethods();

                return $this->successResponse([
                    'order' => $this->formatOrder($order),
                    'shipping_method' => $shipping_methods[$order->shipping_method] ?? [],
                    'payment_method' => $payment_methods[$order->payment_method] ?? [],
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'messege' => 'not found',
        ]);
    }

    public function formatOrder(Order $order)
    {
        $products = [];

        if ($order->products) {
            foreach ($order->products as $product) {
                $products[] = [
                    'name' => $product->name,
                    // 'total_price' => $product->total_price,
                    // 'special' => $product->current_price,
                    // 'price' => $product->price,
                    'count' => $product->count,
                    'image' => $product->product ? $product->product->translate()->image : 'null',
                    'option' => $product->option,
                    'currency' => $order->currency,

                    //total prices = price * count
                    'price' => $product->price == $product->current_price ? $product->total_price : $product->price * $product->count,
                    'special' => $product->price == $product->current_price ? null : $product->total_price,
                    'current_price_without_discount' => $product->current_price_without_discount,
                    'total_price_without_discount' => $product->total_price_without_discount,
                ];
            }
        }
        $created_at = $order->created_at;
        if (isset($created_at)) {
            $pd = Carbon::parse($created_at);
            switch (app()->getLocale()) {
                case 'uk':
                    $pd->setLocale('uk_UA');
                    break;
                case 'ru':
                    $pd->setLocale('ru_RU');
                    break;

                default:
                    $pd->setLocale('en_GB');
                    break;
            }

            $created_at = (string)$pd->translatedFormat('d.m.Y H:i');
        }


        $payments = Cart::getPaymentMethods(app()->getLocale());
        $shippings = Cart::getShippingMethods(app()->getLocale());

        return [
            'status' => __($order->order_status),
            'id' => $order->id,
            'order_link' => $order->order_link,
            //
            'contact_email' => $order->shipping_email,
            'contact_phone' => $order->shipping_phone,
            //
            'payment' => 'Total ' . $order->total,
            'payment_method' => (isset($payments[$order->payment_method]) ? $payments[$order->payment_method]['name'] : $order->payment_method), //'Paypal ' . $order->total . config('app.currency'),
            'payment_method_icon' => (isset($payments[$order->payment_method]) ? $payments[$order->payment_method]['icon'] : ''),
            //
            'shipping' => $order->shipping,
            'shipping_method' => (isset($shippings[$order->shipping_method]) ? $shippings[$order->shipping_method]['name'] : $order->shipping_method), //$order->shipping_method,
            'shipping_method_icon' => (isset($shippings[$order->shipping_method]) ? $shippings[$order->shipping_method]['icon'] : ''),
            //
            'shipping_name' => $order->shipping_name,
            'shipping_lastname' => $order->shipping_lastname,
            // 'shipping_name' => $order->shipping_name . ' ' . $order->shipping_lastname,
            'shipping_email' => $order->shipping_email,
            'shipping_phone' => $order->shipping_phone,
            'shipping_address' => $order->shipping_address,
            'shipping_street' => $order->shipping_street,
            'shipping_house' => $order->shipping_house,
            'shipping_apartment' => $order->shipping_apartment,
            'shipping_city' => $order->shipping_city,
            'shipping_city_id' => $order->shipping_city_id,
            'shipping_country' => $order->shipping_country,
            'shipping_province' => $order->shipping_province,
            'shipping_postcode' => $order->shipping_postcode,
            'shipping_branch' => $order->shipping_branch,
            'shipping_branch_id' => $order->shipping_branch_id,
            //
            'billing_name' => $order->billing_name,
            'billing_lastname' => $order->billing_lastname,
            // 'billing_name' => $order->billing_name . ' ' . $order->billing_lastname,
            'billing_email' => $order->billing_email,
            'billing_phone' => $order->billing_phone,
            'billing_address' => $order->billing_address,
            'billing_apartment' => $order->billing_apartment,
            'billing_city' => $order->billing_city,
            'billing_country' => $order->billing_country,
            'billing_province' => $order->billing_province,
            'billing_postcode' => $order->billing_postcode,
            //
            'subtotal' => $order->subtotal,
            'discount' => $order->discount,
            'full_subtotal_price' => $order->full_subtotal_price,
            'subtotal_price_without_discount' => $order->subtotal_price_without_discount,
            'total_price' => $order->total,
            'tracking' => $order->tracking,
            'tracking_link' => $order->tracking_link,
            //
            'products' => $products,
            //
            'created_at' => $created_at,

            'history' => $order->historyList(),
        ];
    }
    /**
     * Returns shipping methods array or method price
     *
     * @param string $code
     * @return void
     */
    public function getShippingMethods($code = '')
    {

        $settings = Cache::remember(
            'checkout_shippings_' . app()->getLocale(),
            3600,
            function () {
                return $this->cart::getShippingMethods(app()->getLocale());
            }
        );

        return $code ? ($settings[$code]['price'] ?? 0) : $settings;
    }

    public function getPaymentMethods()
    {
        $settings = Cache::remember(
            'checkout_payments_' . app()->getLocale(),
            3600,
            function () {
                return $this->cart::getPaymentMethods(app()->getLocale());
            }
        );

        return $settings;
    }
}
