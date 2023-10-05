<?php

namespace Owlwebdev\Ecom\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'likes',
        'dislikes',
        'author',
        'city',
        'email',
        'text',
        'rating',
        'status',
        'product_id',
        'parent_id',
        'user_id',
        'created_at',
    ];

    protected $appends = ['public_date'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    const SHOWN_NAME = 'Відгуки';
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

            if (empty($model->status)) {

                $model->status = '0';
            }
        });
    }

    public function getPublicDateAttribute()
    {
        return (new Carbon($this->created_at))->format('d.m.Y');
        // return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->isoFormat('Do MMM YYYY');
    }

    // -- relations
    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function photos()
    // {
    //     return $this->hasMany(ReviewsPhoto::class, 'review_id', 'id');
    // }

    // --

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('reviews.status', self::STATUS_ACTIVE);
    }

    public function shortText()
    {
        if (strlen(strip_tags($this->text)) > 150) {
            return printTruncated(150, $this->text) . '...';
        } else {
            return printTruncated(150, $this->text);
        }
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

    /**
     * @return string
     */
    public function showStatus()
    {
        return view('admin.pieces.status', self::getStatuses()[$this->status]);
    }

    // public function getCreatedAtAttribute()
    // {
    //     return $this->created_at->format('m.d.Y');
    // }
}
