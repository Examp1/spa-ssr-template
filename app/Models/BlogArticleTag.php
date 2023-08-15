<?php

namespace App\Models;

use App\Models\Translations\BlogTagTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogArticleTag extends Model
{
    use HasFactory;

    protected $table = 'blog_article_tag';

    protected $guarded = [];

    public $timestamps = false;
}
