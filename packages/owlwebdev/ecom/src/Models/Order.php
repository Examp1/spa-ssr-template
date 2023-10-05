<?php

namespace Owlwebdev\Ecom\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * Class Order
 *
 * @property integer $id
 * @property string $subtotal
 * @property string $total
 * @property string $shipping_name
 * @property string $shipping_lastname
 * @property string $shipping_email
 * @property string $shipping_method
 * @property string $shipping_company
 * @property string $shipping_country
 * @property string $shipping_city
 * @property string $shipping_address
 * @property string $shipping_apartment
 * @property string $shipping_phone
 * @property string $shipping_postcode
 * @property string $payment_name
 * @property string $payment_method
 * @property string $billing_email
 * @property string $billing_phone
 * @property string $billing_name
 * @property string $billing_country
 * @property string $billing_city
 * @property string $billing_address
 * @property string $billing_postcode
 * @property string $billing_apartment
 * @property integer $coupon_id
 * @property integer $user_id
 * @property integer $order_status_id
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 * @property string $utm_data
 * @property string $currency
 *
 * @property User $user
 * @property Coupon $coupon
 * @property OrderProduct[]|Collection $products
 */
class Order extends Model
{
    const STATUSES = [
        '1' => 'Dropped cart',
        '2' => 'Waiting',
        '3' => 'Canceled',
        '4' => 'Payed',
        '5' => 'In Progress',
        '6' => 'Complete',
        '7' => 'Trash',
        '8' => 'Return',
        '9' => 'Shipped'
    ];

    const PUBLIC_STATUSES = [
        '2', '3', '4', '5', '6', '8', '9'
    ];

    const DECREMENT_STATUSES = [
        '2',
    ];

    const INCREMENT_STATUSES = [
        '3',
    ];

    protected $fillable = [
        'subtotal',
        'shipping',
        'total',
        'shipping_method',
        'payment_name',
        'payment_method',

        'shipping_name',
        'shipping_lastname',
        'shipping_surname',
        'shipping_phone',
        'shipping_email',
        'shipping_company',
        'shipping_country',
        'shipping_province',
        'shipping_city',
        'shipping_city_id',
        'shipping_address',
        'shipping_street',
        'shipping_house',
        "shipping_apartment",
        "shipping_postcode",
        "shipping_branch",
        "shipping_branch_id",

        'billing_name',
        'billing_lastname',
        'billing_surname',
        'billing_email',
        'billing_country',
        'billing_province',
        'billing_city',
        'billing_city_id',
        'billing_address',
        'billing_address_id',
        'billing_apartment',
        'billing_phone',
        'billing_postcode',
        'coupon_id',
        'discount_id',
        'discount',
        'user_id',
        'order_status_id',
        'comment',
        'utm_data',
        'paypal_id',
        'tracking',
        'notified',
        'currency',
    ];

    protected $appends = [
        'total_price',
        'order_status',
        'tracking_link',
    ];

    public function showStatus()
    {

        return self::STATUSES[$this->order_status_id];
    }

    public function getOrderLinkAttribute()
    {
        $url = '/cart/thankyou?i=' . (($this->id + 33) * 2) . '&d=' . base64_encode($this->created_at); //super secret link

        if (app()->getLocale() !== config('translatable.locale')) {
            $url = '/' . app()->getLocale() . $url;
        }

        return url($url);
    }

    public function frontLink()
    {
        return self::getOrderLinkAttribute(); //super secret link
    }

    public function getOrderStatusAttribute()
    {
        return self::STATUSES[$this->order_status_id];
    }

    public function getTrackingLinkAttribute()
    {
        $link = '';

        $shippings = Cart::getShippingMethods(app()->getLocale());

        if ($this->tracking && isset($shippings[$this->shipping_method]) && isset($shippings[$this->shipping_method]['link'])) {
            $link = str_replace('trackingcode', $this->tracking, $shippings[$this->shipping_method]['link']);
        }
        return $link; //super secret link
    }

    public function getTotalPriceAttribute()
    {
        return number_format(round(($this->subtotal_price + $this->shipping), 2), 2, '.', '');
    }

    public function getSubtotalPriceAttribute()
    {
        $result = $this->products()->get()->reduce(function ($carry, $item) {
            $price = $item->total_price;

            if ($price !== false) {
                return $carry + $price;
            }

            return $carry;
        }, 0);

        return number_format(round(($result), 2), 2, '.', '');
    }

    public function getSubtotalPriceWithoutDiscountAttribute()
    {
        $result = $this->products()->get()->reduce(function ($carry, $item) {
            $price = $item->total_price_without_discount;

            if ($price !== false) {
                return $carry + $price;
            }

            return $carry;
        }, 0);

        return number_format(round(($result), 2), 2, '.', '');
    }

    public function getFullSubtotalPriceAttribute()
    {
        $result = $this->products()->get()->reduce(function ($carry, $item) {
            $price = $item->total_full_price;

            if ($price !== false) {
                return $carry + $price;
            }

            return $carry;
        }, 0);

        return number_format(round(($result), 2), 2, '.', '');
    }

    public function recalculateTotals()
    {
        $order_price_without_discounts = $this->subtotal_price_without_discount;
        $coupon_type = $this->coupon ? $this->coupon->type : null;
        $coupon_value = $this->coupon ? $this->coupon->value : 0;

        foreach ($this->products()->get() as $order_product) {
            $full_price = $order_product->special > 0 ? $order_product->special : $order_product->price; // same in app/Models/OrderProduct.php getCurrentPriceAttribute()

            $order_product->coupon_price = 0;

            // coupon
            if ($this->coupon) {

                //get % value of fixed value coupon for current product
                if ($coupon_type == 'fixed') {
                    $all_products_price_sum = $order_price_without_discounts - $this->coupon->value;

                    if ($all_products_price_sum < 1) {
                        $coupon_value = 100;
                    } else {

                        $product_percent_from_order = ($full_price * $order_product->count) * 100 / $order_price_without_discounts;

                        $product_price_sum_with_discount = $all_products_price_sum * $product_percent_from_order / 100;

                        $one_product_price = $product_price_sum_with_discount / $order_product->count;

                        $order_product->coupon_price = $one_product_price;
                        // dd($product_percent_from_order, $order_price_without_discounts, $full_price, $product_price_sum_with_discount, $one_product_price);
                    }
                } else {
                    $order_product->coupon_price = $full_price - ($full_price * $coupon_value / 100);
                }

                // new price
                $full_price = $order_product->coupon_price;

                //discount texts
                $this->discount = $this->coupon->translate(app()->getLocale())->name;
            } else {
                $this->discount = null;
            }

            // discount
            if ($this->discounts) {
                $percentage = intval($this->discounts->percentage) ?? 0;
                $order_product->coupon_price = $full_price - ($full_price * $percentage / 100); // TODO: make method for different types of discount, not only -%

                //discount texts
                $this->discount = $this->discount ? $this->discount . ', ' . $this->discounts->translate(app()->getLocale())->name : $this->discounts->translate(app()->getLocale())->name;
            } elseif (!$this->coupon) {
                $this->discount = null;
            }

            $order_product->save();
        }

        $this->subtotal = $this->subtotal_price;
        $this->total = $this->total_price;
// dd($this->coupon,$this->subtotal);
        // precise prices in totals
        // if ($this->coupon) {
        //     if ($this->coupon->type == 'fixed') {
        //         $this->subtotal = $this->subtotal_price_without_discount - $this->coupon->value;
        //     }
        // }

        // if ($this->discounts) {
        //     $percentage = intval($this->discounts->percentage) ?? 0;
        //     $this->subtotal = $this->subtotal - ($this->subtotal * $percentage / 100); // TODO: make method for different types of discount, not only -%
        // }

        $this->saveQuietly();
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault(new User());
    }

    public function coupon()
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }

    public function discounts()
    {
        return $this->hasOne(Discount::class, 'id', 'discount_id');
    }

    public function history()
    {
        return $this->hasMany(OrderHistory::class, 'order_id', 'id');
    }

    public function historyList()
    {
        $shipping_methods = Cart::getShippingMethods();
        $payment_methods = Cart::getPaymentMethods();

        return $this->history()->orderBy('created_at', 'DESC')->get()->map(function ($item) use ($shipping_methods, $payment_methods) {
            $text = '';

            switch ($item->type) {
                case OrderHistory::PAYMENT:
                    $text = __('Payment changed') . ': ' . (isset($payment_methods[$item->text]) ? $payment_methods[$item->text]['name'] : __($item->text));
                    break;

                case OrderHistory::SHIPPING:
                    $text =  __('Shipping changed') . ': ' . (isset($shipping_methods[$item->text]) ? $shipping_methods[$item->text]['name'] : __($item->text));
                    break;

                default:
                    $text = __($item->type) . ': ' . __($item->text);
                    break;
            }

            return [
                'time' => Carbon::parse($item->created_at)->format('d.m.Y H:i'),
                'text' => $text,
            ];
        });
    }

    /**
     * Update products stock
     *
     * @param string $type decrement or increment
     * @return void
     */
    public function updateProducts(string $type = 'decrement')
    {
        foreach ($this->products as $order_product) {
            if (!$order_product->product) {
                return false;
            }

            if ($order_product->option_id !== null) {
                $price = $order_product->product->prices()->find($order_product->option_id);
                if ($price) {
                    $price->$type('count', $order_product->count);
                }
            } else {
                $order_product->product->$type('quantity', $order_product->count);
            }

            $order_product->product->$type('hot', Product::HOT_FROM_ORDER);

            $order_product->product->updatePriceImage();
        }
    }

}
