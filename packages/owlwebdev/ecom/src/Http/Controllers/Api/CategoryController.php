<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Adapter;
use App\Modules\Setting\Setting;
use App\Modules\Forms\Models\Form;
use Illuminate\Support\Arr;

class CategoryController extends Controller
{
    use ResponseTrait;

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

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

        if (isset($decodedJson['current_category_slug'])) {
            $current_category_slug = $decodedJson['current_category_slug'];
        } else {
            $current_category_slug = '';
        }

        app()->setLocale($lang);
        config(['translatable.locale' => $lang]); // get translated fields

        $per_page = cache()->remember('catalog_per_page', (60 * 60 * 12), function () {
            return app(Setting::class)->get('categories_per_page', config('translatable.locale'));
        });

        // disable Autoload Translations
        $model = new Category;
        $model->disableAutoloadTranslations();

        $query = $model->query()
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where('category_translations.lang', $lang)
            ->select([
                'categories.*',
                'category_translations.*',
                'categories.id as id',
            ]);

        //show without active status
        if (!isset($decodedJson['prevw']) || $decodedJson['prevw'] != crc32($decodedJson['slug'])) {
            $query->where('category_translations.status_lang', 1)->active();
        }


        $model = $query
            ->where('categories.slug', $decodedJson['slug'])
            ->first();

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );

        $data = $this->adapter->prepareModelResults($model, $lang);

        /* type */
        $data['type'] = $model->parent_id ? 'subcategory' : 'category';
        $data['breadcrumbs'] = $model->getBreadcrumbs($current_category_slug);

        /* sort/order */
        $sorts = ['price', 'hot', 'created_at'];
        $orders = ['asc', 'desc'];

        if (isset($decodedJson['sort']) && in_array($decodedJson['sort'], $sorts)) {
            $data['sort'] = $decodedJson['sort'];
        } else {
            $data['sort'] = 'name';
        }

        if (isset($decodedJson['order']) && in_array($decodedJson['order'], $orders)) {
            $data['order'] = $decodedJson['order'];
        } else {
            $data['order'] = 'asc';
        }

        $currentPage = $decodedJson['page'] ?? 1;

        $paginated = $model->parseFilters($decodedJson['filters'] ?? null)->getProducts($data['sort'], $data['order'])->paginate($per_page, ['*'], 'page', $currentPage)->toArray();
        $data['products']['data'] = $paginated['data'];
        $data['products']['paginate'] = Arr::except($paginated, 'data');

        $data['filter'] = $model->filter->getFilters();
        $data['selected'] = $model->filter->getSelectedFilters(true);

        $data['additional_filters'] = $model->filters;
        $data['sorts'] = $sorts;
        $data['orders'] = $orders;

        $fast_form = cache()->remember(
            'fast_form' . $lang,
            (60 * 60 * 24),
            function () use ($lang) {
                $formId = app(Setting::class)->get('fast_form', $lang);

                if ($formId) {
                    $form = Form::query()->where('id', $formId)->first();
                    $result = [
                        'form_id' => $formId,
                        'data' => $form->getData(),
                        'btn_name' => $form->btn_name,
                    ];
                    //replace type, ex. input > form-input
                    if (is_array($result['data']) && count($result['data'])) {
                        foreach ($result['data'] as $qaw => $qwe) {
                            $result['data'][$qaw]['type'] = 'form-' . $qwe['type'];
                        }
                    }
                    return $result;
                }

                return [];
            }
        );

        if ($fast_form) {
            $data['fast_form'] = $fast_form;
        }
        $preorder_form = cache()->remember(
            'preorder_form' . $lang,
            (60 * 60 * 24),
            function () use ($lang) {
                $formId = app(Setting::class)->get('preorder_form', $lang);

                if ($formId) {
                    $form = Form::query()->where('id', $formId)->first();
                    $result = [
                        'form_id' => $formId,
                        'data' => $form->getData(),
                        'btn_name' => $form->btn_name,
                    ];
                    //replace type, ex. input > form-input
                    if (is_array($result['data']) && count($result['data'])) {
                        foreach ($result['data'] as $qaw => $qwe) {
                            $result['data'][$qaw]['type'] = 'form-' . $qwe['type'];
                        }
                    }
                    return $result;
                }

                return [];
            }
        );

        if ($preorder_form) {
            $data['preorder_form'] = $preorder_form;
        }

        return $this->successResponse($data);
    }

    public function getAll(Request $request)
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

        $categories = Category::query()
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where('category_translations.lang', $lang)
            ->select([
                'categories.*',
                'category_translations.name AS transName'
            ])
            ->get();

        $data = [];

        foreach ($categories as $category) {
            $url = '/' . Category::getMenuConfig()['url_prefix'] . $category->slug;

            if ($lang !== config('translatable.locale')) {
                $url = '/' . $lang . $url;
            }

            $data[] = [
                'slug' => $category->slug,
                'url'  => $url,
                'name' => $category->transName
            ];
        }

        return $this->successResponse(['list' => $data]);
    }
}
