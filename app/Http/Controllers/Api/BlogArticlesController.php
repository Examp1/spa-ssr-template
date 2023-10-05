<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\BlogArticleCategory;
use App\Models\BlogArticles;
use App\Models\BlogArticleTag;
use App\Models\BlogCategories;
use App\Models\BlogTags;
use App\Models\Menu;
use App\Models\Pages;
use App\Modules\Setting\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Adapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class BlogArticlesController extends Controller
{
    use ResponseTrait;

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Получить только модель (основные данные)
     *
     * data in {slug,lang}
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBySlug(Request $request)
    {

        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['slug'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['slug']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        $m = BlogArticles::query()->where('blog_articles.slug', $decodedJson['slug'])->first();

        if ($m) {
            $m->views = ($m->views + 1);
            $m->save();
        }

        $model = cache()->remember('get_blog_articles_all_' . '_' . implode('_', $decodedJson), (60 * 5), function () use ($decodedJson, $lang) {
            $m_query = BlogArticles::query()
                ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
                ->where('blog_article_translations.lang', $lang)
                ->select([
                    'blog_articles.*',
                    'blog_article_translations.name AS articleName',
                    'blog_article_translations.image',
                    'blog_article_translations.preview_image',
                ]);

            //show without active status
            if (!isset($decodedJson['prevw']) || $decodedJson['prevw'] != crc32($decodedJson['slug'] . config('app.name'))) {
                $m_query->where('blog_article_translations.status_lang', 1)->active();
            }

            return $m_query
                ->where('blog_articles.slug', $decodedJson['slug'])
                ->first();
        });

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                //Response::HTTP_NOT_FOUND // 404
                Response::HTTP_FOUND //302 to home
            );

        $model->views = $m->views;

        $data = $this->adapter->prepareModelResults($model, $lang);

        $tagsIds = BlogArticleTag::query()->where('blog_article_id', $model->id)->pluck('blog_tag_id')->toArray();

        $tagsList = [];

        if ($tagsIds) {
            $tags = BlogTags::query()
                ->leftJoin('blog_tag_translations', 'blog_tag_translations.blog_tags_id', '=', 'blog_tags.id')
                ->where('blog_tag_translations.lang', $lang)
                ->whereIn('blog_tags.id', $tagsIds)
                ->select([
                    'blog_tags.*',
                    'blog_tag_translations.name AS transName',
                ])
                ->get();

            foreach ($tags as $tag) {
                $url = '/' . BlogTags::getMenuConfig()['url_prefix'] . $tag->slug;

                if ($lang !== config('translatable.locale')) {
                    $url = '/' . $lang . $url;
                }

                $tagsList[] = [
                    'slug' => $tag->slug,
                    'url'  => $url,
                    'name' => $tag->transName
                ];
            }
        }

        $data['tags'] = $tagsList;

        $seeAlsoStatic = cache()->remember('see_also_for_blog', (60 * 60 * 12), function () use ($lang) {
            $seeAlsoTitle = app(Setting::class)->get('blog_see_also_title', $lang);
            $seeAlsoBtnName = app(Setting::class)->get('blog_see_also_btn_name', $lang);
            $seeAlsoBtnLink = app(Setting::class)->get('blog_see_also_btn_link', $lang);
            $seeAlsoBtnStyle = app(Setting::class)->get('blog_see_also_btn_style', $lang);
            $seeAlsoBtnIcon = app(Setting::class)->get('blog_see_also_btn_icon', $lang);

            return [
                'title'  => $seeAlsoTitle,
                'button' => [
                    'name'  => $seeAlsoBtnName,
                    'link'  => $seeAlsoBtnLink,
                    'style' => $seeAlsoBtnStyle,
                    'icon'  => $seeAlsoBtnIcon,
                ]
            ];
        });

        $similarModels = BlogArticles::query()
            ->active()
            ->where('id', '<>', $model->id)
            ->orderBy('public_date', 'desc')
            ->limit(3)
            ->get();

        $similarData = [];

        $getDefaultLangCode = config('translatable.locale');

        foreach ($similarModels as $item) {
            $translate = $item->getTranslation($lang);

            $url = '/news/' . $item->slug;

            if ($lang !== $getDefaultLangCode) {
                $url = '/' . $lang . $url;
            }

            if (isset($item->public_date)) {
                $pd = Carbon::parse($item->public_date);
                if ($lang == 'uk') {
                    $pd->setLocale('uk_UA');
                } elseif ($lang == 'ru') {
                    $pd->setLocale('ru_RU');
                }
                $item->public_date = $pd->translatedFormat('d F Y');
            }

            $similarData[] = [
                'title'  => $translate['name'],
                'text'  => $translate['text'],
                'link'   => $url,
                'image' => $item->image,
                'public_date' => $item->public_date,
            ];
        }

        $data['see_also'] = array_merge($seeAlsoStatic, ['list' => $similarData]);

        /*************************************** Breadcrumbs *********************************************/
        $breadcrumbs = [
            [
                'name' => __('News', [], $lang),
                'url'  => ($lang !== config('translatable.locale') ? ('/' . $lang) : '') . '/news'
            ]
        ];

        $breadcrumbs[]  = [
            'name' => $model->articleName,
            'url'  => $model->frontLink()
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return $this->successResponse($data);
    }

    public function getAll(Request $request)
    {
        $take = cache()->remember('get_blog_per_page', (60 * 60 * 12), function () {
            return (int)app(Setting::class)->get('blog_per_page', config('translatable.locale'));
        });

        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        $data = cache()->remember('get_blog_articles_all_' . $take . '_' . implode('_', $decodedJson), (60 * 5), function () use ($decodedJson, $lang, $take) {
            $data        = [];
            $currentPage = $decodedJson['page'] ?? 1;

            // disable Autoload Translations
            $model = new BlogArticles;
            $model->disableAutoloadTranslations();

            $articlesQuery = BlogArticles::query()
                ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
                ->where('blog_article_translations.lang', app()->getLocale())
                ->where('blog_article_translations.status_lang', 1)
                ->select([
                    'blog_articles.public_date',
                    'blog_articles.slug',
                    'blog_article_translations.name',
                    'blog_article_translations.image',
                    'blog_article_translations.alt',
                    'blog_article_translations.text',
                    'blog_article_translations.excerpt',
                    'blog_article_translations.preview_image',
                    'blog_article_translations.preview_alt',
                ])
                ->orderBy('blog_articles.public_date', 'desc')
                ->active();

            // $articlesQuery = BlogArticles::query()
            //     ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
            //     // ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_articles.main_category_id')
            //     // ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_articles.main_category_id')
            //     ->where('blog_article_translations.lang', $lang)
            //     ->where('blog_article_translations.status_lang', 1)
            //     // ->where('blog_category_translations.lang', $lang)
            //     ->select([
            //         'blog_articles.*',
            //         // 'blog_categories.path',
            //         // 'blog_category_translations.name as category_title',
            //     ])
            //     ->orderBy('blog_articles.public_date', 'desc')
            //     ->active();

            // Custom pagination
            // $total = $articlesQuery->count();

            // $articlesQuery
            //     ->take($take)
            //     ->skip($currentPage == 1 ? 0 : (($take * $currentPage) - $take));

            // $articles = $articlesQuery->get();

            // $items = $this->adapter->prepareModelsResults($articles, $lang);

            // $paginate = [
            //     'total'        => $total,
            //     'per_page'     => $take,
            //     'current_page' => $currentPage,
            // ];

            // $items['paginate'] = $paginate;

            // $data = [
            //     'items' => $items
            // ];

            // $page = Pages::query()
            //     ->leftJoin('page_translations', 'page_translations.pages_id', '=', 'pages.id')
            //     ->where('page_translations.lang', config('translatable.locale'))
            //     ->where('pages.id', 13)
            //     ->select([
            //         'pages.*',
            //         'page_translations.title AS transTitle',
            //         'page_translations.description AS transDescription',
            //     ])
            //     ->first();

            // $data['page'] = [
            //     'title' => $page->transTitle,
            //     'description' => $page->transDescription,
            // ];

            $paginated = $articlesQuery->paginate($take, ['*'], 'page', $currentPage);

            // format public date in paginator
            $paginated->setCollection(
                $paginated->getCollection()->transform(function ($item) use ($lang) {
                    if (isset($item->public_date)) {
                        $pd = Carbon::parse($item->public_date);
                        if ($lang == 'uk') {
                            $pd->setLocale('uk_UA');
                        } elseif ($lang == 'ru') {
                            $pd->setLocale('ru_RU');
                        }
                        $item->public_date = $pd->translatedFormat('d F Y');
                    }

                    return $item;
                })
            );
            $paginated = $paginated->toArray();
            $data['articles']['data'] = $paginated['data'];
            $data['articles']['paginate'] = Arr::except($paginated, 'data');

            return $data['articles'];
        });

        /*************************************** Breadcrumbs *********************************************/
        $breadcrumbs = [
            [
                'name' => __('News', [], $lang),
                'url'  => ($lang !== config('translatable.locale') ? ('/' . $lang) : '') . '/news'
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return $this->successResponse($data);
    }

    /**
     * Получить только items и category
     *
     * data in [category_slug,lang,page]
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function category(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        if (!isset($decodedJson['slug'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['slug']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $take = cache()->remember('get_blog_per_page', (60 * 60 * 12), function () {
            return (int)app(Setting::class)->get('blog_per_page', config('translatable.locale'));
        });

        $data = cache()->remember('get_blog_articles_by_category_' . $take . implode('_', $decodedJson), (60 * 5), function () use ($decodedJson, $lang, $take) {
            $currentPage = $decodedJson['page'] ?? 1;

            $category = BlogCategories::query()
                ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_categories.id')
                ->where('blog_category_translations.lang', $lang)
                ->select([
                    'blog_categories.*',
                    'blog_category_translations.name AS categoryName',
                    'blog_category_translations.description AS categoryDescription',
                    'blog_category_translations.excerpt AS categoryExcerpt',
                    'blog_category_translations.image AS categoryImage',
                    'blog_category_translations.alt AS categoryImageAlt',
                    'blog_category_translations.meta_title AS categoryMetaTitle',
                    'blog_category_translations.meta_description AS categoryMetaDescription',
                ])
                ->where('blog_categories.slug', $decodedJson['slug'])
                ->active()
                ->first();

            if (!$category) {
                return ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND);
            }

            $articleIds = BlogArticleCategory::query()->where('blog_category_id', $category->id)->pluck('blog_article_id')->toArray();

            $articlesQuery = BlogArticles::query()
                ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
                ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_articles.main_category_id')
                ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_articles.main_category_id')
                ->where('blog_article_translations.lang', app()->getLocale())
                ->where('blog_article_translations.status_lang', 1)
                ->where('blog_category_translations.lang', app()->getLocale())
                ->select([
                    'blog_articles.*',
                    'blog_article_translations.name as transName',
                    'blog_article_translations.excerpt as transExcerpt',
                    'blog_article_translations.text as transText',
                    'blog_article_translations.name as transName',
                    'blog_article_translations.image AS transImage',
                    'blog_article_translations.alt AS transImageAlt',
                    'blog_article_translations.preview_image AS transPreviewImage',
                    'blog_article_translations.preview_alt AS transPreviewImageAlt',
                    'blog_article_translations.meta_title AS transMetaTitle',
                    'blog_article_translations.meta_description AS transMetaDescription',
                    'blog_categories.path',
                    'blog_category_translations.name as category_title',
                ])
                ->orderBy('blog_articles.public_date', 'desc')
                ->where(function ($q) use ($category, $articleIds) {
                    $q->whereIn('blog_articles.id', $articleIds)
                        ->orWhere('blog_articles.main_category_id', $category->id);
                })
                ->active();

            $total = $articlesQuery->count();

            $articlesQuery
                ->take($take)
                ->skip($currentPage == 1 ? 0 : (($take * $currentPage) - $take));

            $articles = $articlesQuery->get();

            $items = $this->adapter->prepareModelsResults($articles, $lang, Menu::TYPE_BLOG_CATEGORY);

            $paginate = [
                'total'        => $total,
                'per_page'     => $take,
                'current_page' => $currentPage,
            ];

            $items['paginate'] = $paginate;

            $data = [
                'category' => [
                    'id'               => $category->id,
                    'parent_id'        => $category->parent_id,
                    'name'             => $category->categoryName,
                    'description'      => $category->categoryDescription,
                    'excerpt'          => $category->categoryExcerpt,
                    'slug'             => $decodedJson['slug'],
                    'path'             => $category->path,
                    'meta_title'       => $category->categoryMetaTitle,
                    'meta_description' => $category->categoryMetaDescription,
                    'image'            => $category->categoryImage,
                    'alt'              => $category->categoryImageAlt,
                ],
                'items'    => $items
            ];

            //TODO: seo from settings
            // $page = Pages::query()
            //     ->leftJoin('page_translations', 'page_translations.pages_id', '=', 'pages.id')
            //     ->where('page_translations.lang', config('translatable.locale'))
            //     ->where('pages.id', 13)
            //     ->select([
            //         'pages.*',
            //         'page_translations.title AS transTitle',
            //         'page_translations.description AS transDescription',
            //     ])
            //     ->first();

            // $data['page'] = [
            //     'title' => $page->transTitle,
            //     'description' => $page->transDescription,
            // ];
            return $data;
        });

        return $this->successResponse($data);
    }

    public function getTagsAll(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        $tags = BlogTags::query()
            ->leftJoin('blog_tag_translations', 'blog_tag_translations.blog_tags_id', '=', 'blog_tags.id')
            ->where('blog_tag_translations.lang', $lang)
            ->select([
                'blog_tags.*',
                'blog_tag_translations.name AS transName'
            ])
            ->get();

        $data = [];

        foreach ($tags as $tag) {
            $url = '/' . BlogTags::getMenuConfig()['url_prefix'] . $tag->slug;

            if ($lang !== config('translatable.locale')) {
                $url = '/' . $lang . $url;
            }

            $data[] = [
                'slug' => $tag->slug,
                'url'  => $url,
                'name' => $tag->transName
            ];
        }

        return $this->successResponse(['list' => $data]);
    }

    public function getByTagSlug(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        if (!isset($decodedJson['slug'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['slug']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $take = cache()->remember('get_blogtags_per_page', (60 * 60 * 12), function () {
            return (int)app(Setting::class)->get('blogtags_per_page', config('translatable.locale'));
        });

        $data = cache()->remember('get_blog_articles_by_tag_' . $take . implode('_', $decodedJson), (60 * 5), function () use ($decodedJson, $lang, $take) {
            $currentPage = $decodedJson['page'] ?? 1;

            $tag = BlogTags::query()
                ->leftJoin('blog_tag_translations', 'blog_tag_translations.blog_tags_id', '=', 'blog_tags.id')
                ->where('blog_tag_translations.lang', $lang)
                ->select([
                    'blog_tags.*',
                    'blog_tag_translations.name AS transName',
                    'blog_tag_translations.description AS transDescription',
                    'blog_tag_translations.image AS transImage',
                    'blog_tag_translations.alt AS transImageAlt',
                    'blog_tag_translations.meta_title AS transMetaTitle',
                    'blog_tag_translations.meta_description AS transMetaDescription',
                ])
                ->where('blog_tags.slug', $decodedJson['slug'])
                ->active()
                ->first();

            if (!$tag) {
                return ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND);
            }

            $articleIds = BlogArticleTag::query()->where('blog_tag_id', $tag->id)->pluck('blog_article_id')->toArray();

            $articlesQuery = BlogArticles::query()
                ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
                ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_articles.main_category_id')
                ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_articles.main_category_id')
                ->where('blog_article_translations.lang', $lang)
                ->where('blog_article_translations.status_lang', 1)
                ->where('blog_category_translations.lang', $lang)
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
                ->active();

            $total = $articlesQuery->count();

            $articlesQuery
                ->take($take)
                ->skip($currentPage == 1 ? 0 : (($take * $currentPage) - $take));

            $articles = $articlesQuery->get();

            $items = $this->adapter->prepareModelsResults($articles, $lang, Menu::TYPE_BLOG_CATEGORY);

            $paginate = [
                'total'        => $total,
                'per_page'     => $take,
                'current_page' => $currentPage,
            ];

            $items['paginate'] = $paginate;

            $data = [
                'tag'        => [
                    'id'               => $tag->id,
                    'name'             => $tag->transName,
                    'description'      => $tag->categoryDescription,
                    'slug'             => $decodedJson['slug'],
                    'meta_title'       => $tag->categoryMetaTitle,
                    'meta_description' => $tag->categoryMetaDescription,
                    'image'            => $tag->categoryImage,
                    'alt'              => $tag->categoryImageAlt,
                ],
                'items'           => $items
            ];


            return $data;
        });

        return $this->successResponse($data);
    }
}
