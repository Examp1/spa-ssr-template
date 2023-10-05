<?php

namespace Owlwebdev\Ecom\Models\Translations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeGridTranslation extends Model
{
    use HasFactory;

    protected $table = 'size_grid_translations';
    public $originalModel = 'Owlwebdev\Ecom\Models\SizeGrid';
    public $entityAttribute = 'size_grid_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'status_lang',
        'image',
        'alt',
    ];
}
