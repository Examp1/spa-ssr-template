<?php

namespace Owlwebdev\Ecom\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Discount extends Model
{
    use HasFactory;
    use Translatable;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const SHOWN_NAME = 'Знижки';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\DiscountTranslation';

    public $translatedAttributes = [
        'name',
        'description',
    ];
    protected $fillable = ['status', 'percentage'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function list()
    {
        return $this
            ->with('translations')
            // ->where(function ($query) {
            //     $query->where('start_at', null);
            //     $query->orWhereDate('start_at', '<=', Carbon::now());
            // })
            // ->where(function ($query) {
            //     $query->where('end_at', null);
            //     $query->orWhereDate('end_at', '>=', Carbon::now());
            // })
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

    public function scopeActive($query)
    {
        return $query->where('discounts.status', self::STATUS_ACTIVE);
    }
}
