<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\BlogArticles;
use Owlwebdev\Ecom\Models\Category;
use Owlwebdev\Ecom\Models\Product;
use Illuminate\Http\Request;
use Owlwebdev\Ecom\Models\SearchHistory;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use App\Modules\Setting\Setting;

class SearchController extends Controller
{
    use ResponseTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request):JsonResponse
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['string']) || $decodedJson['string'] === '') {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['string']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['lang'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['lang']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        $per_page = cache()->remember('catalog_per_page', (60 * 60 * 12), function () {
            return app(Setting::class)->get('categories_per_page', config('translatable.locale'));
        });

        $currentPage = (int)($decodedJson['page'] ?? 1);

        $string = $decodedJson['string'];
        $prod_code = '';
        $data = [
            'categories' => [],
            'products' => [],
            'news' => [],
        ];

        // try get product code
        if (mb_strpos($string, '-') !== false) {
            $prod_code = explode("-", $string, 2)[0];
        }

        // disable Autoload Translations
        $model = new Product;
        $model->disableAutoloadTranslations();

        $products = $model->query()
            ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where(function ($query) use ($lang) {
                $query->where('product_translations.lang', $lang);
                $query->where('product_translations.status_lang', 1);
            })
            ->where(function ($query) use ($string, $lang, $prod_code) {
                $query->whereTranslationLike('name', '%' . $string . '%', $lang)
                    ->orWhere('code', 'LIKE', '%' . $string . '%')
                    /*->orWhereTranslationLike('description', '%' . $string . '%', $lang)
                    ->orWhereTranslationLike('constructor_html', '%' . $string . '%', $lang)*/;
                if ($prod_code) {
                    $query->orWhere('code', 'LIKE', '%' . $prod_code . '%');
                }
            })
            ->select([
                'product_translations.name',
                'product_translations.image',
                'product_translations.alt',
                'product_translations.info',
                'product_translations.excerpt',
                'products.currency',
                'products.quantity',
                'products.price',
                'products.old_price',
                'products.slug',
                'products.category_id',
                'products.id as id', // fix id rewrite with translation id);
            ])
            ->with([
                'prices' => function ($query) {
                    $query->active();
                }
            ])
            ->orderByRaw('(quantity = 0)')
            ->active();

        $paginated = $products->paginate($per_page, ['*'], 'page', $currentPage)->toArray();

        $data['products']['data'] = $paginated['data'];
        $data['products']['paginate'] = Arr::except($paginated, 'data');

        // foreach ($products as $product) {
        //     $url = '/' . Product::getMenuConfig()['url_prefix'] . $product->slug;

        //     if ($lang !== config('translatable.locale')) {
        //         $url = '/' . $lang . $url;
        //     }

        //     $data['products'][] = [
        //         'slug'  => $product->slug,
        //         'url'   => $product->frontLink(),
        //         'name'  => $product->name,
        //         'image' => $product->image,
        //     ];
        // }

        // Categories

        $categories = Category::query()
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where(function ($query) use ($lang) {
                $query->where('category_translations.lang', $lang);
                $query->where('category_translations.status_lang', 1);
            })
            ->where(function ($query) use ($string, $lang) {
                $query->whereTranslationLike('name', '%' . $string . '%', $lang);
                $query->orWhereTranslationLike('description', '%' . $string . '%', $lang);
                $query->orWhereTranslationLike('constructor_html', '%' . $string . '%', $lang);
            })
            ->select([
                'categories.*',
                'category_translations.name AS transName',
                'category_translations.image AS image'
            ])
            ->active()
            ->get();

        foreach ($categories as $category) {


            $data['categories'][] = [
                'slug' => $category->slug,
                'url'  => $category->frontLink(),
                'name' => $category->transName,
                'img'  => $category->image,
            ];
        }

        // News
        // disable Autoload Translations
        $model = new BlogArticles;
        $model->disableAutoloadTranslations();

        $articles = $model->query()
            ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
            ->where(function ($query) use ($lang) {
                $query->where('blog_article_translations.lang', $lang);
                $query->where('blog_article_translations.status_lang', 1);
            })
            ->where(function ($query) use ($string, $lang) {
                $query->whereTranslationLike('name', '%' . $string . '%', $lang);
                $query->orWhereTranslationLike('excerpt', '%' . $string . '%', $lang);
                $query->orWhereTranslationLike('constructor_html', '%' . $string . '%', $lang);
            })
            ->select([
                'blog_articles.public_date',
                'blog_articles.slug',
                'blog_article_translations.name',
                'blog_article_translations.image',
                'blog_article_translations.alt',
                'blog_article_translations.text',
                'blog_article_translations.excerpt',
            ])
            ->active();

        $data['news'] = $articles->paginate(10);
        return $this->successResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addToHistory(Request $request):JsonResponse
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['input']) && isset($decodedJson['select'])) {
            SearchHistory::query()->create([
                'input'  => $decodedJson['input'],
                'select' => $decodedJson['select'],
                'ip'     => $request->ip()
            ]);
        }

        return $this->successResponse([]);
    }
}
