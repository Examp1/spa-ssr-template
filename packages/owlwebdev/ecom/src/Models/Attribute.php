<?php

namespace Owlwebdev\Ecom\Models;

use Owlwebdev\Ecom\Interfaces\Attr;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Owlwebdev\Ecom\Models\Translations\AttributeTranslation;

/**
 * Class Attributes
 * @package App\Models
 *
 * @property integer $order
 */
class Attribute extends Model implements TranslatableContract
{
    use HasFactory;

    use Sluggable;
    use Translatable;
    use SluggableScopeHelpers;

    protected $table = 'attributes';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\AttributeTranslation';
    public $translationForeignKey = 'attribute_id';
    public $translatedAttributes = [
        'name'
    ];

    protected $fillable = [
        'icon', 'order', 'slug', 'attribute_group_id', 'type',
    ];

    const ICONS = [
        'ic-type',
        'ic-calender',
        'ic-nutrtion',
        'ic-features',
        'ic-rate',
        'ic-location',
    ];

    const TYPES = [
        'text',
        'image',
        'color',
        'size'
        // TODO: make attributes types
        // 'bool',
        // 'list',
    ];

    public function getDefaultTitleAttribute()
    {
        return $this->translateOrDefault()->name;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'defaultTitle'
            ]
        ];
    }

    /**
     * @param integer|null  $category_id
     * @param Product|null $product
     * @return array|Collection
     */
    public static function getAttributesList($category_id = null, $product = null)
    {
        if (empty($category_id))
            return [];

        /** @var Category $category */
        $category = Category::query()->findOrFail($category_id);

        $query = self::query()
            // ->where(['attribute_group_id' => $category->attribute_group_id])
            ->whereHas('attributeGroups', function ($query) use ($category) {
                $query->where('attribute_groups.id', '=', $category->attribute_group_id);
            })
            ->orderBy('order')
            ->orderByTranslation('name');

        /** @var Collection|self[] $attributes */
        $attributes = $query->get();
        $list = new Collection();

        foreach ($attributes as $attribute) {
            $attr = new Attr($attribute);

            foreach (config('translatable.locales') as $language => $name) {
                $attr->text[$language] = '';
            }

            $list->push($attr);
        }

        $list = $list->keyBy('attr_slug');

        if ($product) {
            $productAttributes = ProductAttributes::query()
                ->where(['product_id' => $product->id])
                ->leftJoin('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
                ->select([
                    'product_attributes.*',
                    'attributes.slug as attr_slug'
                ])
                ->get()
                ->toArray();


            foreach ($productAttributes as $attribute) {
                if ($list->has($attribute['attr_slug'])) {
                    $attr = $list->get($attribute['attr_slug']);
                    $attr->text[$attribute['lang']] = $attribute['text'];
                    $attr->slug = $attribute['slug'];
                }
            }
        }

        return $list;
    }

    /**
     * @param integer|null  $category_id
     * @param Product|null $product
     * @return array|Collection
     */
    public static function getAttributesListGroups($category_id = null, $product = null)
    {
        if (empty($category_id))
            return [];

        /** @var Category $category */
        $category = Category::query()->findOrFail($category_id);

        $query = self::query()
            // ->where(['attribute_group_id' => $category->attribute_group_id])
            ->whereHas('attributeGroups', function ($query) use ($category) {
                $query->where('attribute_groups.id', '=', $category->attribute_group_id);
            })
            ->orderBy('order')
            ->orderByTranslation('name');
        /** @var Collection|self[] $attributes */
        $attributes = $query->get();
        $list = new Collection();
        $result = new Collection();

        foreach ($attributes as $attribute) {
            $attr = new Attr($attribute);

            $result->group = $attribute->attributeGroup()
                ->with('translations')
                ->first()->name;

            foreach (config('translatable.locales') as $language => $name) {
                $attr->text[$language] = '';
            }

            $list->push($attr);
        }

        $list = $list->keyBy('attr_slug');

        if ($product) {
            $productAttributes = ProductAttributes::query()
                ->where(['product_id' => $product->id])
                ->leftJoin('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
                ->select([
                    'product_attributes.*',
                    'attributes.slug as attr_slug'
                ])
                ->get()
                ->toArray();


            foreach ($productAttributes as $attribute) {
                if ($list->has($attribute['attr_slug'])) {
                    $attr = $list->get($attribute['attr_slug']);
                    $attr->text[$attribute['lang']] = $attribute['text'];
                    $attr->slug = $attribute['slug'];
                }
            }
        }
        if (empty($list)) {
            return [];
        }
        $result->list = $list;
        return $result;
    }

    public function attributeGroup()
    {
        return $this->hasOne(AttributeGroup::class, 'id', 'attribute_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributeGroups()
    {
        return $this->belongsToMany(AttributeGroup::class)->using(AttributeAttributeGroup::class);
    }

    public function getAllLanguagesNotEmpty()
    {
        return AttributeTranslation::query()
            ->where('attribute_id', $this->id)
            ->where('name', '<>', '')
            ->orderBy('lang', 'desc')
            ->pluck('lang')
            ->toArray();
    }

    /**
     * @return string
     */
    public function groupsToString()
    {
        $arr = collect($this->attributeGroups()->get()->toArray())->pluck('name')->toArray();
        return implode(', ', $arr);
    }
}
