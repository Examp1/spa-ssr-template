<?php

namespace Owlwebdev\Ecom\Models\Translations;

use App\Modules\Constructor\Collections\ComponentCollections;
use App\Modules\Constructor\Contracts\HasConstructor;
use App\Modules\Constructor\Traits\Constructorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Setting\Setting;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model implements HasConstructor
{
    use HasFactory;
    use Constructorable;

    protected $table = 'category_translations';
    public $originalModel = 'Owlwebdev\Ecom\Models\Category';
    public $entityAttribute = 'category_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'excerpt',
        'description',
        'meta_title',
        'meta_description',
        'meta_created_as',
        'meta_auto_gen',
        'image',
        'alt',
        'ban_image',
        'ban_alt',
        'options',
        'status_lang',
        'main_screen',
        'constructor_html',
    ];

    const META_CREATED_AS_EMPTY = 0;
    const META_CREATED_AS_BY_HAND = 1;
    const META_CREATED_AS_AUTO_GEN = 2;
    const META_TAGS = ['%SiteName%', '%PageName%']; // generator tags

    /**
     * @param array $attributes
     * @return Model
     */
    public function fill(array $attributes = [])
    {
        $this->fillConstructorable($attributes);

        return parent::fill($attributes);
    }

    public function constructorComponents(): array
    {
        return [
            // 'widget'           => ComponentCollections::widget(WIDGET_GROUP_CATALOG),
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
