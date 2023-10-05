<?php

namespace Owlwebdev\Ecom\Models;

use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;

class CategoryFilter extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'category_filters';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\CategoryFilterTranslation';
    public $translationForeignKey = 'category_filter_id';
    public $translatedAttributes = [
        'name'
    ];

    protected $fillable = [
        'link',
        'category_id',
        'order',
    ];

    // protected $appends = [
    //     'url',
    // ];

    // public function getUrlAttribute ()
    // {
    //     return url($this->link);
    // }
}
