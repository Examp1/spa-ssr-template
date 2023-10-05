<?php

namespace Owlwebdev\Ecom\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class CategoryFilterTranslation extends Model
{
    protected $table = 'category_filter_translations';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    private string $entityAttribute = 'category_filter_id';
}
