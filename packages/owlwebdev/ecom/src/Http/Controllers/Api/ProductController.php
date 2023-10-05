<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use Owlwebdev\Ecom\Models\Product;
use App\Service\Adapter;
use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use Owlwebdev\Ecom\Models\ProductAttributes;
use App\Modules\Forms\Models\Form;
use App\Modules\Setting\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
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

        // disable Autoload Translations
        $model = new Product;
        $model->disableAutoloadTranslations();

        $query = $model->query()
            ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where('product_translations.lang', $lang)
            ->select([
                'products.*',
                'product_translations.*',
                'products.id as id',
            ]);

        //show without active status
        if (!isset($decodedJson['prevw']) || $decodedJson['prevw'] != crc32($decodedJson['slug'])) {
            $query->where('product_translations.status_lang', 1)->active();
        }

        $model = $query
            ->where('products.slug', $decodedJson['slug'])
            ->first();

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );

        $data = $this->adapter->prepareModelResults($model, $lang);

        $data['prices'] = $model->prices()->active()->get();
        $data['options'] = [];

        $attrs = $model->productAttributes()->whereHas('prices')->with(['prices' => function ($query) {
            $query->active();
        }])->get();

        foreach ($attrs as $attr) {
            if (!isset($data['options'][$attr->attr_slug])) {
                $data['options'][$attr->attr_slug] = [
                    'attribute_id' => $attr->attribute_id,
                    'name'         => $attr->attr_name,
                    'slug'         => $attr->attr_slug,
                    'type'         => $attr->attr_type,
                    'values'       => [],
                ];
            }
            $prices = $attr->prices->pluck('id');

            $data['options'][$attr->attr_slug]['values'][] = [
                'product_attribute_id' => $attr->id,
                'value'                => $attr->value,
                'alt'                  => $attr->alt,
                'slug'                 => $attr->slug,
                'prices_array'         => $prices,
            ];

            // create structured array of attributes for prices
            $attr_data_for_price = [
                'type'                 => $attr->attr_type,
                'attribute_id'         => $attr->attribute_id,
                'product_attribute_id' => $attr->id,
            ];
            // add to prices
            foreach ($data['prices']->whereIn('id', $prices) as &$price) {
                if (!$price->product_attributes_array) {
                    $price->product_attributes_array = collect();
                }

                $price->product_attributes_array->push($attr_data_for_price);
            }
        }

        $data['attributes'] = $model->productAttributes;

        $data['related'] = [];
        $relatedIds = $model->related->pluck('id');

        // disable Autoload Translations
        $rmodel = new Product;
        $rmodel->disableAutoloadTranslations();

        $products = $rmodel->getQueryWithPrices()
            ->whereIn('products.id', array_merge($relatedIds->toArray()))
            ->active()
            ->get();

        foreach ($products as $rel) {
            $data['related'][] = array_values($rel->toArray());
        }

        $data['similar'] = [];
        $similarIds = $model->similar->pluck('id');

        // disable Autoload Translations
        $smodel = new Product;
        $smodel->disableAutoloadTranslations();

        $products = $smodel->getQueryWithPrices()
            ->whereIn('products.id', array_merge($similarIds->toArray()))
            ->active()
            ->get();

        foreach ($products as $rel) {
            $data['similar'][] = array_values($rel->toArray());
        }

        $data['images'] = $model->images;
        $data['breadcrumbs'] = $model->getBreadcrumbs($current_category_slug);

        $fast_form = cache()->remember(
            'fast_form' . $lang,
            (60 * 60 * 24),
            function () use ($lang) {
                $formId = app(Setting::class)->get('fast_form', $lang);

                if ($formId) {
                    $form = Form::query()->where('id', $formId)->first();
                    $result = [
                        'form_id'  => $formId,
                        'data'     => $form->getData(),
                        'btn_name' => $form->btn_name,
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

        if($fast_form) {
            $data['fast_form'] = $fast_form;
        }
        if ($model->preorder) {
            $preorder_form = cache()->remember(
                'preorder_form' . $lang,
                (60 * 60 * 24),
                function () use ($lang) {
                    $formId = app(Setting::class)->get('preorder_form', $lang);

                    if ($formId) {
                        $form = Form::query()->where('id', $formId)->first();
                        $result = [
                            'form_id'  => $formId,
                            'data'     => $form->getData(),
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
        }

        // $guideId = (int)$model->guide;

        // if ($guideId) {
        //     $page = Pages::query()->where('id', $guideId)->active()->first();
        //     if ($page) {
        //         $data['guide'] = url($page->frontLink());
        //     }
        // }

        $size_grid = $model->category->sizeGrid()->active()->first();

        if ($size_grid) {
            $data['size_grid'] = $size_grid->frontLink();
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

        // disable Autoload Translations
        $model = new Product;
        $model->disableAutoloadTranslations();

        $products = $model->query()
            ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where('product_translations.lang', $lang)
            ->select([
                'products.*',
                'product_translations.*',
                'products.id as id',
            ])
            ->get();

        $data = [];

        foreach ($products as $product) {
            $url = '/' . Product::getMenuConfig()['url_prefix'] . $product->slug;

            if ($lang !== config('translatable.locale')) {
                $url = '/' . $lang . $url;
            }

            $data[] = [
                'slug' => $product->slug,
                'url'  => $url,
                'name' => $product->name
            ];
        }

        return $this->successResponse(['list' => $data]);
    }
}
