<?php

namespace App\Models\Translations;

use App\Modules\Setting\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'blog_category_translations';

    public $originalModel = 'App\Models\BlogCategories';
    public $entityAttribute = 'blog_categories_id';

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
        'image_mob',
        'alt_mob',
    ];

    const META_CREATED_AS_EMPTY = 0; // не заполнено
    const META_CREATED_AS_BY_HAND = 1; // заполнено вручную
    const META_CREATED_AS_AUTO_GEN = 2; // сгенерировано автоматически
    const META_TAGS = ['%SiteName%','%PageName%']; // generator tags

    public function getMetaKeywords(string $lang = 'uk'): array
    {
        $siteName = app(Setting::class)->get('app_name',$lang);

        return [
            $siteName,
            $this->name,
        ];
    }
}
