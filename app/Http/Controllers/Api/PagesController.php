<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Landing;
use App\Models\Langs;
use App\Models\Menu;
use App\Models\Pages;
use App\Modules\Setting\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Adapter;

class PagesController extends Controller
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

        app()->setLocale($lang);

        $query = Pages::query()
            ->leftJoin('page_translations', 'page_translations.pages_id', '=', 'pages.id')
            ->where('page_translations.lang', $lang)
            ->where('page_translations.status_lang', 1)
            ->select([
                'pages.*',
                'page_translations.title AS pageName'
            ]);

        if (isset($decodedJson['prevw'])) {
            if ($decodedJson['prevw'] == crc32($decodedJson['slug'])) { // check
                // good
            } else {
                $query->active();
            }
        } else {
            $query->active();
        }

        $model = $query
            ->where('pages.slug', $request->get('slug'))
            ->first();

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );

        $data = $this->adapter->prepareModelResults($model, $lang);

        /* type */
        $data['type'] = 'page';

        /* Custom pages */
        $settings_fields = [
            'contact_page' => 'Сторінка контактів',
        ];
        $pages_settings = cache()->remember(
            'pages_settings_' . $lang,
            (60 * 5),
            function () use ($settings_fields) {
                $pages = [];

                foreach ($settings_fields as $field => $text) {
                    $pages[$field] = app(Setting::class)->get($field . '_page_id', config('translatable.locale'));
                }

                return $pages;
            }
        );

        switch ($model->id) {
            case $pages_settings['contact_page']:
                $data['contacts'] = json_decode(app(Setting::class)->get('contacts', $lang), true);
                $data['maps_api_key'] = app(Setting::class)->get('maps_api_key', config('translatable.locale'));
                break;

            default:
                break;
        }

        // page contacts
        if ($model->id == 2) {
            $data['contacts'] = json_decode(app(Setting::class)->get('contacts', $lang), true);
            $data['maps_api_key'] = app(Setting::class)->get('maps_api_key', Langs::getDefaultLangCode());
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

        $data = [];

        $take        = 1;
        $currentPage = $decodedJson['page'] ?? 1;

        $modelQuery = Pages::query()
            ->leftJoin('page_translations', 'page_translations.pages_id', '=', 'pages.id')
            ->where('page_translations.lang', $lang)
            ->where('page_translations.status_lang', 1)
            ->select([
                'pages.*',
                'page_translations.title AS pageName'
            ])
            ->orderBy('pages.created_at', 'desc')
            ->active();

        $total = $modelQuery->count();

        $modelQuery
            ->take($take)
            ->skip($currentPage == 1 ? 0 : (($take * $currentPage) - $take));

        $model = $modelQuery->get();

        $items = $this->adapter->prepareModelsResults($model, $lang);

        $paginate = [
            'total'        => $total,
            'per_page'     => $take,
            'current_page' => $currentPage,
        ];

        $items['paginate'] = $paginate;

        $data['items'] = $items;

        return $this->successResponse($data);
    }

    public function getMain(Request $request)
    {

        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
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

        $main_page_type = app(Setting::class)->get('main_page_type', config('translatable.locale'));

        switch ($main_page_type) {
            case Menu::TYPE_PAGE:
                $id = app(Setting::class)->get('main_page_page_id', config('translatable.locale'));
                $model = Pages::query()
                    ->leftJoin('page_translations', 'page_translations.pages_id', '=', 'pages.id')
                    ->where('page_translations.lang', $lang)
                    ->where('page_translations.status_lang', 1)
                    ->where('pages.id', $id)
                    ->select([
                        'pages.*',
                        'page_translations.title AS pageName'
                    ])
                    ->active()
                    ->first();

                if (!$model)
                    return $this->errorResponse(
                        ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                        Response::HTTP_NOT_FOUND
                    );

                $data = $this->adapter->prepareModelResults($model, $lang);

                $data['section_type'] = Menu::TYPE_PAGE;
                break;
            case Menu::TYPE_LANDING:
                $id = app(Setting::class)->get('main_page_landing_id', config('translatable.locale'));
                $model = Landing::query()
                    ->leftJoin('landing_translations', 'landing_translations.landing_id', '=', 'landings.id')
                    ->where('landing_translations.lang', $lang)
                    ->where('landing_translations.status_lang', 1)
                    ->where('landings.id', $id)
                    ->select([
                        'landings.*',
                        'landing_translations.title AS pageName'
                    ])
                    ->active()
                    ->first();

                if (!$model)
                    return $this->errorResponse(
                        ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                        Response::HTTP_NOT_FOUND
                    );

                $data = $this->adapter->prepareModelResults($model, $lang);

                //                if($data['model']['menu_id']){
                //                    $data['model']['menu'] = $this->adapter->prepareMenuResults([$data['model']['menu_id']],$lang);
                //                }

                $data['section_type'] = Menu::TYPE_LANDING;
                break;
            default:
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                    Response::HTTP_NOT_FOUND
                );
                break;
        }

        return $this->successResponse($data);
    }
}
