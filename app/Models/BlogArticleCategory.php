<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogArticleCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_article_category';

    protected $guarded = [];

    public $timestamps = false;
}
