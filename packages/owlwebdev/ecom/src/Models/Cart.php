<?php

namespace Owlwebdev\Ecom\Models;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public const SHIPPING_METHODS = [
        'novaposhta' => [
            'name' => "Нова пошта",
            'code' => 'novaposhta',
            'description' => "До відділення",
            'price' => 0,
            'price_text' => 'За тарифами служби', //__('free')
            'link' => '', // tracking
        ],
        'postomat' => [
            'name' => "Нова пошта поштомат",
            'code' => 'postomat',
            'description' => "До поштомата",
            'price' => 0,
            'price_text' => 'За тарифами служби', //__('free')
            'link' => '', // tracking
        ],
        'delivery' => [
            'name' => "Кур'єр",
            'code' => 'delivery',
            'description' => "Доставка кур'єром",
            'price' => 0,
            'price_text' => 'За тарифами служби', //__('free')
            'link' => '', // tracking
        ],
        'ukrposhta' => [
            'name' => "Укрпошта",
            'code' => 'ukrposhta',
            'description' => "Адресна доставка",
            'price' => 0,
            'price_text' => 'За тарифами служби', //__('free')
            'link' => '', // tracking
        ],
        'pickup' => [
            'name' => 'Самовивіз',
            'code' => 'pickup',
            'description' => 'адреси складів/офісів тут',
            'price' => 0,
            'price_text' => 'Free', //__('free')
            'link' => '', // tracking
        ],
        'callme' => [
            'name' => 'Передзвоніть',
            'code' => 'callme',
            'description' => 'Ми Вам зателефонуємо для уточнення усіх деталей стосовно доставки',
            'price' => 0,
            'price_text' => 'Free', //__('free')
            'link' => '', // tracking
        ],

    ];

    public const PAYMENT_METHODS = [
        'cash' => [
            'code' => 'cash',
            'name' => 'Готівкою кур’єру або при самовивозі',
            'description' => "",
            'icon' => ''
        ],
        'liqpay' => [
            'code' => 'liqpay',
            'name' => 'Картою через систему Liqpay',
            'description' => "",
            'icon' => ''
        ],
        'card' => [
            'code' => 'card',
            'name' => 'На карту або розрахунковий рахунок',
            'description' => "",
            'icon' => ''
        ],
        'paypal' => [
            'code' => 'paypal',
            'name' => 'Paypal',
            'description' => "Після оформлення замовлення з вами зв'яжеться наш менеджер згідно графіку роботи нашого інтернет-магазину для уточнення всіх деталей",
            'icon' => '',
        ],
        'payafter' => [
            'code' => 'payafter',
            'name' => 'Оплата під час отримання товару',
            'description' => "Доставка кур'єром",
            'icon' => ''
        ],

    ];

    public const CURRENCIES = [
        'UAH' => [
            'name' => 'Гривні',
            'code' => 'UAH',
            'rate' => 1,
            'prefix' => '', //₴
            'suffix' => ' грн',
            'status' => 1,
        ],
        'USD' => [
            'name' => 'Долари',
            'code' => 'USD',
            'rate' => 36.5686,
            'prefix' => '$', //$
            'suffix' => '',
            'status' => 1,
        ],
        'EUR' => [
            'name' => 'Фунти',
            'code' => 'EUR',
            'rate' => 40.1724,
            'prefix' => '£', //£
            'suffix' => '',
            'status' => 1,
        ],

    ];

    public const SHIPPING_FIELDS = [
        "shipping_name",
        "shipping_lastname",
        "shipping_company",
        "shipping_email",
        "shipping_country",
        "shipping_province",
        "shipping_city",
        "shipping_address",
        "shipping_street",
        "shipping_house",
        "shipping_apartment",
        "shipping_phone",
        "shipping_postcode",
    ];

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'option_id',
        'count',
    ];

    public function calculatePrice()
    {
        $result = [
            'price' => 0,
            'special' => 0,
            'cost' => 0,
        ];

        $product = $this->product;

        if ($product) {
            $result = [
                'price' => $product->old_price > 0 ? $product->old_price : $product->price,
                'special' => $product->old_price > 0 ? $product->price : 0,
                'cost' => $product->cost > 0 ? $product->cost : 0,
            ];
        }

        if ($product && $this->option_id) {
            $option_id = $this->option_id;

            $product_option_query = $product->whereHas('prices', function ($builder) use ($option_id) {
                $builder->where('id', $option_id);
            })
                ->with([
                    'prices' => function ($builder) use ($option_id) {
                        $builder->where('id', $option_id);
                    }
                ]);

            $product_option = $product_option_query->first()->prices->first();

            if ($product_option) {
                $result = [
                    'price' => $product_option->old_price > 0 ? $product_option->old_price : $product_option->price,
                    'special' => $product_option->old_price > 0 ? $product_option->price : 0,
                    'cost' => $product_option->cost > 0 ? $product_option->cost : 0,
                ];
            }
        }

        $rate = self::getCurrencies()[$product->currency ?: config('app.currency')]['rate'] ?? 1;
        //prices in default currency
        $result['price'] *= $rate;
        $result['special'] *= $rate;
        $result['cost'] *= $rate;

        return $result;
    }

    public function getPriceAttribute()
    {
        return $this->calculatePrice()['price'];
    }

    public function getTotalPriceAttribute()
    {
        return $this->formatPrice($this->getPriceAttribute() * $this->count);
    }

    public function getSpecialPriceAttribute()
    {
        return $this->calculatePrice()['special'];
    }

    public function getTotalSpecialAttribute()
    {
        return $this->formatPrice($this->getSpecialPriceAttribute() * $this->count);
    }

    public function getCurrentPriceAttribute()
    {
        $prices = $this->calculatePrice();
        return $prices['special'] > 0 ? $prices['special'] : $prices['price'];
    }

    public function getTotalCurrentPriceAttribute()
    {
        $price = $this->getCurrentPriceAttribute();
        return $this->formatPrice($price * $this->count);
    }

    public function getCostAttribute()
    {
        $prices = $this->calculatePrice();
        return $prices['cost'];
    }

    public function getCostSaveAttribute()
    {
        $total = $this->getTotalPriceAttribute();
        $special = $this->getTotalSpecialAttribute();
        if ($special < 1) {
            return 0;
        } elseif ($total < 1) {
            return 0;
        } else {
            return (100 - round($special * 100 / $total));
        }
    }

    public function getTotalCartCost(string $session_id)
    {

        $items = $this->where('session_id', $session_id)->get();

        $total = $items->reduce(function ($carry, $item) {
            // if product or price is deleted(not set), remove it from cart

            try {
                $price = $item->getTotalCurrentPriceAttribute();
            } catch (\Exception $e) {
                $item->delete();
                $price = false;
            }

            if ($price !== false) {
                return $carry + $price;
            }

            return $carry;
        });

        if ($total) {
            return $this->formatPrice($total);
        }

        return 0;
    }

    public function getTotalCartCostWithCoupon(string $session_id, $coupon_value, $percent = true)
    {
        $items = $this->where('session_id', $session_id)->get();

        $total = $items->reduce(function ($carry, $item) use ($percent, $coupon_value) {
            $price = $item->total_current_price; //Actual price

            return $carry + $price;
        });

        if ($total) {
            //coupon
            $total = $percent ? $total - ($total * $coupon_value / 100) : ($total >= $coupon_value ? $total - $coupon_value : 0);

            //discount
            $user = auth('api')->user();

            if ($user && $user->discount) {
                $percentage = intval($user->discount-> percentage) ?? 0;
                $total = $total - ($total * $percentage / 100); // TODO: make method for different types of discount, not only -%
            }

            return $this->formatPrice($total);
        }

        return 0;
    }

    public function formatPrice(float $price)
    {
        $cents = 2;

        if (floor($price) == $price) { // whole number
            $cents = 0;
        }

        return number_format(round($price, 2), $cents, '.', '');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public static function getSettings(string $lang = null, string $type = null)
    {
        if (!$lang) {
            $lang = config('translatable.locale');
        }

        $settings = [];
        $settings_db = Settings::query()
            ->where([
                ['code', 'checkout'],
                ['lang', $lang]
            ])
            ->first();

        if ($settings_db) {
            $settings_data = $settings_db->toArray();
            $settings = isset($settings_data['value']) ? json_decode($settings_data['value'], true) : [];
        }

        if ($type && isset($settings[$type])) {
            return $settings[$type];
        } else {
            return [];
        }

        return $settings;
    }

    public static function formatMethodsData(array $data, array $default)
    {
        foreach ($data as $code => $method) {
            foreach ($method as $field_key => $field) {
                switch ($field_key) {
                    case 'price_text':
                        $price = $method['price'] ?? $default[$code]['price'];

                        if (!$field && (!empty($price) || $price === '0')) {
                            $default[$code][$field_key] = ($price === '0' ? __('free') : $price);
                        } elseif ($field) {
                            $default[$code][$field_key] = $field;
                        }
                        break;

                    case 'price':
                        if ($field || $field === '0') {
                            $default[$code][$field_key] = (int) $field;
                        }
                        break;

                    case 'rate':
                        if ($field) {
                            $default[$code][$field_key] = toFloat($field);
                        }
                        break;

                    default:
                        $default[$code][$field_key] = $field;
                        break;
                }
            }
        }

        return $default;
    }

    public static function getPaymentMethods(string $lang = null)
    {
        if (!$lang) {
            $lang = config('translatable.locale');
        }

        $data = self::getSettings($lang, 'payment');

        $default = self::PAYMENT_METHODS;

        if (empty($data)) {
            return $default;
        }

        return self::formatMethodsData($data, $default);
    }

    public static function getShippingMethods(string $lang = null)
    {
        if (!$lang) {
            $lang = config('translatable.locale');
        }

        $data = self::getSettings($lang, 'shipping');

        $default = self::SHIPPING_METHODS;

        if (empty($data)) {
            return $default;
        }

        return self::formatMethodsData($data, $default);
    }

    public static function getCurrencies()
    {
        $lang = config('translatable.locale');

        $data = self::getSettings($lang, 'currencies');

        $default = self::CURRENCIES;

        if (empty($data)) {
            return $default;
        }

        return self::formatMethodsData($data, $default);
    }
}
