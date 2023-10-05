<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Collection;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Owlwebdev\Ecom\Models\Translations\AttributeGroupTranslation;

/**
 * Class AttributeGroup  Группы атрибутов
 *
 * @property integer  $id
 * @property string   $name  Название группы атрибутов
 * @property integer  $order  Порядок сортировки
 *
 * @property Attributes[]|Collection  $productAttributes  Список атрибутов
 */
class AttributeGroup extends Model implements TranslatableContract
{
    use HasFactory;

    use Translatable;

    protected $table = 'attribute_groups';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\AttributeGroupTranslation';
    public $translationForeignKey = 'attribute_group_id';
    public $translatedAttributes = [
        'name'
    ];

    protected $fillable = ['name', 'order'];

    // public function attributes()
    // {
    //     return $this->hasMany(Attribute::class, 'attribute_group_id', 'id');
    // }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->using(AttributeAttributeGroup::class);
    }

    public static function getOptionsHTML($selected = null)
    {
        $groups = app(self::class)->all();

        return view('ecom::admin.attribute_groups.options-html', ['groups' => $groups, 'selected' => $selected]);
    }

    public function getAllLanguagesNotEmpty()
    {
        return AttributeGroupTranslation::query()
            ->where('attribute_group_id', $this->id)
            ->where('name', '<>', '')
            ->orderBy('lang', 'desc')
            ->pluck('lang')
            ->toArray();
    }
}
