<?php

namespace Owlwebdev\Ecom\Models\Translations;

use App\Modules\Constructor\Collections\ComponentCollections;
use App\Modules\Constructor\Contracts\HasConstructor;
use App\Modules\Constructor\Traits\Constructorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Setting\Setting;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductTranslation
 * @package App\Models\Translations
 *
 * @property string $name
 * @property string $description
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 */
class ProductTranslation extends Model implements HasConstructor
{
    use HasFactory;
    use Constructorable;


    protected $table = 'product_translations';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'excerpt',
        'meta_title',
        'meta_description',
        'image',
        'alt',
        'constructor_html',
        'status_lang',
        'main_screen',
        'info',
    ];

    public $originalModel = 'Owlwebdev\Ecom\Models\Product';
    public $entityAttribute = 'product_id';

    const META_CREATED_AS_EMPTY    = 0;
    const META_CREATED_AS_BY_HAND  = 1;
    const META_CREATED_AS_AUTO_GEN = 2;
    const META_TAGS = ['%SiteName%','%PageName%']; // generator tags

    public function fill(array $attributes = [])
    {
        $this->fillConstructorable($attributes);

        return parent::fill($attributes);
    }

    public function constructorComponents(): array
    {
        return [
//            'simple-text'    => ComponentCollections::simpleText(),
//            'text-n-columns'       => ComponentCollections::textNColumns(),
//            'text-two-columns'       => ComponentCollections::textTwoColumns(),            'image-and-text' => ComponentCollections::imageAndText(),
//            'image-and-text' => ComponentCollections::imageAndText(),
//            'video-and-text' => ComponentCollections::videoAndText(),
//            'images-3'       => ComponentCollections::images3(),
//            'stages'         => ComponentCollections::stages(),
//            'quotes'         => ComponentCollections::quotes(),
//            'full-image'     => ComponentCollections::fullImage(),
//            'advantages'     => ComponentCollections::advantages(),
//            'accordion'      => ComponentCollections::accordion(),
//            'numbers'        => ComponentCollections::numbers(),
//            'gallery'        => ComponentCollections::gallery(),
//            'list'           => ComponentCollections::list(),
//            'table'          => ComponentCollections::table(),
//            'blocks'         => ComponentCollections::blocks(),
            'widget'         => ComponentCollections::widget(WIDGET_GROUP_PAGE),
            'form'             => ComponentCollections::form(),
        ];
    }

    public function getMetaKeywords(string $lang = 'uk'): array
    {
        $siteName = app(Setting::class)->get('app_name', $lang);

        return [
            $siteName,
            $this->name,
        ];
    }
}
