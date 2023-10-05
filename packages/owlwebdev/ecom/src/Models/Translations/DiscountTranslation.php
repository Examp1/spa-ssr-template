<?php

namespace Owlwebdev\Ecom\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class DiscountTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];
}
