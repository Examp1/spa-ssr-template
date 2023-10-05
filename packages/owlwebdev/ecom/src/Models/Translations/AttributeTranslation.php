<?php

namespace Owlwebdev\Ecom\Models\Translations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AttributeTranslation
 * @package App\Models\Translations
 *
 * @property string $name
 */
class AttributeTranslation extends Model
{
    use HasFactory;

    protected $table = 'attribute_translations';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    private string $entityAttribute = 'attribute_id';
}
