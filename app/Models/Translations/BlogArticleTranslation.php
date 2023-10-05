<?php

namespace App\Models\Translations;

use App\Modules\Constructor\Collections\ComponentCollections;
use App\Modules\Constructor\Contracts\HasConstructor;
use App\Modules\Constructor\Traits\Constructorable;
use App\Modules\Setting\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogArticleTranslation extends Model implements HasConstructor
{
    use HasFactory;
    use Constructorable;

    protected $table = 'blog_article_translations';

    public $originalModel = 'App\Models\BlogArticles';
    public $entityAttribute = 'blog_articles_id';


    protected $fillable = [
        'name',
        'excerpt',
        'text',
        'meta_title',
        'meta_description',
        'meta_created_as',
        'meta_auto_gen',
        'image',
        'alt',
        'preview_image',
        'preview_alt',
        'image_mob',
        'alt_mob',
        'constructor_html',
        'status_lang'
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

    public $timestamps = false;

    /**
     * @inheritDoc
     */
    public function constructorComponents(): array
    {
        return [
            'simple-text'    => ComponentCollections::simpleText(),
            'quotes'         => ComponentCollections::quotes(),
//            'image-and-text' => ComponentCollections::imageAndText(),
            'image-blog'     => ComponentCollections::imageBlog(),
            'list'           => ComponentCollections::list(),
            'button'         => ComponentCollections::button(),
//            'gallery'        => ComponentCollections::gallery(),
//            'widget'         => ComponentCollections::widget(WIDGET_GROUP_ARTICLE),
        ];
    }

    public function getMetaKeywords(string $lang = 'uk'): array
    {
        $siteName = app(Setting::class)->get('app_name',$lang);

        return [
            $siteName,
            $this->name,
        ];
    }
}
