<?php

namespace App\Models\Translations;

use App\Modules\Constructor\Collections\ComponentCollections;
use App\Modules\Constructor\Contracts\HasConstructor;
use App\Modules\Constructor\Traits\Constructorable;
use App\Modules\Setting\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PageTranslation
 * @package App\Models\Translations
 *
 * @property string $title
 * @property string $description
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 */
class PageTranslation extends Model implements HasConstructor
{
    use HasFactory;
    use Constructorable;

    protected $table = 'page_translations';

    public $originalModel = 'App\Models\Pages';
    public $entityAttribute = 'pages_id';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'meta_created_as',
        'meta_auto_gen',
        'image',
        'alt',
        'image_mob',
        'alt_mob',
        'constructor_html',
        'status_lang',
        'main_screen'
    ];

    const META_CREATED_AS_EMPTY    = 0; // не заполнено
    const META_CREATED_AS_BY_HAND  = 1; // заполнено вручную
    const META_CREATED_AS_AUTO_GEN = 2; // сгенерировано автоматически
    const META_TAGS = ['%SiteName%','%PageName%']; // generator tags

    /**
     * @param array $attributes
     * @return Model
     */
    public function fill(array $attributes = [])
    {
        $this->fillConstructorable($attributes);

        return parent::fill($attributes);
    }

    /**
     * @inheritDoc
     */
    public function constructorComponents(): array
    {
        return [
            'simple-text'          => ComponentCollections::simpleText(),
            'simple-title'         => ComponentCollections::simpleTitle(),
            'text-n-columns'       => ComponentCollections::textNColumns(),
            'image-and-text'       => ComponentCollections::imageAndText(),
            'video-and-text'       => ComponentCollections::videoAndText(),
            'images-3'             => ComponentCollections::images3(),
            'stages'               => ComponentCollections::stages(),
            'quotes'               => ComponentCollections::quotes(),
            'quote-slider'         => ComponentCollections::quoteSlider(),
            'full-image'           => ComponentCollections::fullImage(),
            'advantages'           => ComponentCollections::advantages(),
            'accordion'            => ComponentCollections::accordion(),
            'accordion-table'      => ComponentCollections::accordionTable(),
            'numbers'              => ComponentCollections::numbers(),
            'gallery'              => ComponentCollections::gallery(),
            'table'                => ComponentCollections::table(),
            'blocks'               => ComponentCollections::blocks(),
            'simple-text-btn-left' => ComponentCollections::simpleTextBtnLeft(),
            'cta'                  => ComponentCollections::cta(),
            'link-list'            => ComponentCollections::linkList(),
            'text-divider'         => ComponentCollections::textDivider(),
            'blocks-slider'        => ComponentCollections::blocksSlider(),
            'team'                 => ComponentCollections::team(),
            'theses'               => ComponentCollections::theses(),
            'partners'             => ComponentCollections::partners(),
            'blocks-links'         => ComponentCollections::blocksLinks(),
            'widget'               => ComponentCollections::widget(WIDGET_GROUP_PAGE),
            'form'                 => ComponentCollections::form(),
        ];
    }

    public function getMetaKeywords(string $lang = 'uk'): array
    {
        $siteName = app(Setting::class)->get('app_name',$lang);

        return [
            $siteName,
            $this->title,
        ];
    }
}
