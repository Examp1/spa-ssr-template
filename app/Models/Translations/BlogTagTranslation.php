<?php

namespace App\Models\Translations;

use App\Modules\Setting\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTagTranslation extends Model
{
    use HasFactory;

    protected $table = 'blog_tag_translations';

    protected $fillable = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_auto_gen',
        'meta_created_as',
        'image',
        'alt'
    ];

    public $timestamps = false;

    public $originalModel = 'App\Models\BlogTags';
    public $entityAttribute = 'blog_tags_id';

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
