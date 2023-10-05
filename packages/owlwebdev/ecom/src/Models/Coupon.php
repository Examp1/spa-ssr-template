<?php

namespace Owlwebdev\Ecom\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use Translatable;

    const DISCOUNT_TYPES = [
        'percent' => 'Percent',
        'fixed' => 'Fixed'
    ];
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const SHOWN_NAME = 'Купони';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\CouponTranslation';

    public $translatedAttributes = [
        'name',
        'description',
    ];

    protected $fillable = [
        'slug',
        'value',
        'quantity',
        'type',
        'start_at',
        'end_at',
        'status',
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    public function findForOrder(string $slug)
    {
        return $this
            ->where('slug', $slug)
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('start_at', null);
                $query->orWhereDate('start_at', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->where('end_at', null);
                $query->orWhereDate('end_at', '>=', Carbon::now());
            })
            ->where(function ($query) {
                $query->where('quantity', null);
                $query->orWhere('quantity', '>', 0);
            })
            ->first();
    }

    public function listForNotify()
    {
        return $this
            ->with('translations')
            ->where(function ($query) {
                $query->where('start_at', null);
                $query->orWhereDate('start_at', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->where('end_at', null);
                $query->orWhereDate('end_at', '>=', Carbon::now());
            })
            ->where('status', 1)
            ->get();
    }

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NOT_ACTIVE => [
                'title'    => 'Не активний',
                'bg_color' => '#ff3838',
                'color'    => '#fdfdfd'
            ],
            self::STATUS_ACTIVE     => [
                'title'    => 'Активний',
                'bg_color' => '#49cc00',
                'color'    => 'white'
            ]
        ];
    }

    public function showStatus()
    {
        return view('admin.pieces.status', self::getStatuses()[$this->status]);
    }

    public function reduceQuantity()
    {
        if ($this->quantity !== null) { // quantity in use
            if ($this->quantity == 0) {
                return false;
            }

            $this->quantity = $this->quantity >= 1 ? $this->quantity - 1 : 0;
            $this->save();

            return true;
        } else {
            return true; // good
        }
    }
}
