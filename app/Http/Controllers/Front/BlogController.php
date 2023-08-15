<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlogArticles;
use App\Models\BlogArticleTag;
use App\Models\BlogCategories;
use App\Models\BlogTags;
use App\Modules\Setting\Setting;

class BlogController extends Controller
{
    public function index()
    {
        $take = cache()->remember('get_articles_per_page', (60 * 60 * 12), function () {
            return (int)app(Setting::class)->get('blog_per_page', config('translatable.locale'));
        });

        $articles = BlogArticles::query()
            ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
            ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_articles.main_category_id')
            ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_articles.main_category_id')
            ->where('blog_article_translations.lang', app()->getLocale())
            ->whereNotNull('blog_article_translations.name')
            ->where('blog_category_translations.lang', app()->getLocale())
            ->select([
                'blog_articles.*',
                'blog_article_translations.name as transName',
                'blog_article_translations.excerpt as transExcerpt',
                'blog_article_translations.text as transText',
                'blog_article_translations.name as transName',
                'blog_article_translations.image AS transImage',
                'blog_article_translations.alt AS transImageAlt',
                'blog_article_translations.meta_title AS transMetaTitle',
                'blog_article_translations.meta_description AS transMetaDescription',
                'blog_categories.path',
                'blog_category_translations.name as category_title',
            ])
            ->orderBy('blog_articles.public_date', 'desc')
            ->active()
            ->paginate($take);

        $meta = [
            'title' => app(Setting::class)->get('blog_title', config('translatable.locale')),
            'meta_title' => app(Setting::class)->get('blog_meta_title', config('translatable.locale')),
            'meta_description' => app(Setting::class)->get('blog_meta_description_', config('translatable.locale')),
        ];

        $this->setMetaTags($meta);
        $this->setOgTags($meta);

        return view('front.home.frontend', [
            'articles' => $articles,
        ]);
    }

    public function tag($slug)
    {
        $take = cache()->remember('get_blogtags_per_page', (60 * 60 * 12), function () {
            return (int)app(Setting::class)->get('blogtags_per_page', config('translatable.locale'));
        });

        $tag = BlogTags::query()
            ->leftJoin('blog_tag_translations', 'blog_tag_translations.blog_tags_id', '=', 'blog_tags.id')
            ->where('blog_tag_translations.lang', app()->getLocale())
            ->select([
                'blog_tags.*',
                'blog_tag_translations.name AS transName',
                'blog_tag_translations.description AS transDescription',
                'blog_tag_translations.image AS transImage',
                'blog_tag_translations.alt AS transImageAlt',
                'blog_tag_translations.meta_title AS transMetaTitle',
                'blog_tag_translations.meta_description AS transMetaDescription',
            ])
            ->where('blog_tags.slug', $slug)
            ->active()
            ->first();

        if (!$tag) {
            abort(404);
        }

        $articleIds = BlogArticleTag::query()->where('blog_tag_id', $tag->id)->pluck('blog_article_id')->toArray();

        $articles = BlogArticles::query()
            ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
            ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_articles.main_category_id')
            ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_articles.main_category_id')
            ->where('blog_article_translations.lang', app()->getLocale())
            ->whereNotNull('blog_article_translations.name')
            ->where('blog_category_translations.lang', app()->getLocale())
            ->select([
                'blog_articles.*',
                'blog_article_translations.name as transName',
                'blog_article_translations.excerpt as transExcerpt',
                'blog_article_translations.text as transText',
                'blog_article_translations.name as transName',
                'blog_article_translations.image AS transImage',
                'blog_article_translations.alt AS transImageAlt',
                'blog_article_translations.meta_title AS transMetaTitle',
                'blog_article_translations.meta_description AS transMetaDescription',
                'blog_categories.path',
                'blog_category_translations.name as category_title',
            ])
            ->orderBy('blog_articles.public_date', 'desc')
            ->where(function ($q) use ($articleIds) {
                $q->whereIn('blog_articles.id', $articleIds);
            })
            ->active()
            ->paginate($take);

        $meta = [
            'title' => $tag->transName,
            'meta_title' => $tag->transMetaTitle,
            'meta_description' => $tag->transMetaDescription,
        ];

        $this->setMetaTags($meta);
        $this->setOgTags($meta);

        return view('front.home.frontend', [
            'articles' => $articles,
        ]);
    }

    public function show($stringfilter)
    {
        $pos = strripos($stringfilter, '/');

        if ($pos !== false) {
            $path = substr($stringfilter, 0, $pos);
            $slug = substr($stringfilter, $pos + 1);
        } else {
            $path = null;
            $slug = $stringfilter;
        }

        // предполагаем что у нас всегда есть slug, и нужна страница single если нет то страница категории
        $article = BlogArticles::query()
            ->where('blog_articles.slug', $slug)
            ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
            ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_articles.main_category_id')
            ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_articles.main_category_id')
            ->where('blog_article_translations.lang', app()->getLocale())
            ->whereNotNull('blog_article_translations.name')
            ->where('blog_category_translations.lang', app()->getLocale())
            ->select([
                'blog_articles.*',
                'blog_article_translations.name as transName',
                'blog_article_translations.excerpt as transExcerpt',
                'blog_article_translations.text as transText',
                'blog_article_translations.name as transName',
                'blog_article_translations.image AS transImage',
                'blog_article_translations.alt AS transImageAlt',
                'blog_article_translations.meta_title AS transMetaTitle',
                'blog_article_translations.meta_description AS transMetaDescription',
                'blog_categories.path',
                'blog_category_translations.name as category_title',
            ])
            ->active()
            ->first();

        if ($article) {
            $meta = [
                'title' => $article->transName,
                'meta_title' => $article->transMetaTitle,
                'meta_description' => $article->transMetaDescription,
                'og_image' => $article->transImage ? get_image_uri($article->transImage) : '',
            ];

            $this->setMetaTags($meta);
            $this->setOgTags($meta);

            return view('front.home.frontend', [
                'page'       => $article,
            ]);
        } else {
            $path = $stringfilter;

            $take = cache()->remember('get_blog_per_page', (60 * 60 * 12), function () {
                return (int)app(Setting::class)->get('blog_per_page', config('translatable.locale'));
            });

            $category = BlogCategories::query()
                ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_categories.id')
                ->where('blog_category_translations.lang', app()->getLocale())
                ->select([
                    'blog_categories.*',
                    'blog_category_translations.name AS transName',
                    'blog_category_translations.description AS transDescription',
                    'blog_category_translations.excerpt AS transExcerpt',
                    'blog_category_translations.image AS transImage',
                    'blog_category_translations.alt AS transImageAlt',
                    'blog_category_translations.meta_title AS transMetaTitle',
                    'blog_category_translations.meta_description AS transMetaDescription',
                ])
                ->where('blog_categories.path', $path)
                ->active()
                ->first();

            if ($category) {

                $meta = [
                    'title' => $category->transName,
                    'meta_title' => $category->transMetaTitle,
                    'meta_description' => $category->transMetaDescription,
                    'og_image' => $category->transImage ? get_image_uri($category->transImage) : '',
                ];

                $this->setMetaTags($meta);
                $this->setOgTags($meta);

                return view('front.home.frontend', [
                    'page'       => $category,
                    // 'articles'   => $articles,
                ]);
            }
        }

        abort(404);
    }
}
