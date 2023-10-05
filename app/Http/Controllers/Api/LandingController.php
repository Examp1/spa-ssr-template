<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Landing;
use App\Models\Menu;
use App\Models\Pages;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Adapter;

class LandingController extends Controller
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

        $query = Landing::query()
            ->leftJoin('landing_translations', 'landing_translations.landing_id', '=', 'landings.id')
            ->where('landing_translations.lang', $lang)
            ->select([
                'landings.*',
                'landing_translations.title AS pageName'
            ]);

        //show without active status
        if (!isset($decodedJson['prevw']) || $decodedJson['prevw'] != crc32($decodedJson['slug'])) {
            $query->where('landing_translations.status_lang', 1)->active();
        }

        $model = $query
            ->where('landings.slug', $decodedJson['slug'])
            ->first();

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND);

        $data = $this->adapter->prepareModelResults($model, $lang, Menu::TYPE_LANDING);

//        if($data['model']['menu_id']){
//            $data['model']['menu'] = $this->adapter->prepareMenuResults([$data['model']['menu_id']],$lang);
//        }

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

        $data = [];

        $take        = 10;
        $currentPage = $decodedJson['page'] ?? 1;

        $modelQuery = Landing::query()
            ->leftJoin('landing_translations', 'landing_translations.landing_id', '=', 'landings.id')
            ->where('landing_translations.lang', $lang)
            ->where('landing_translations.status_lang', 1)
            ->select([
                'landings.*',
                'landing_translations.title AS pageName'
            ])
            ->orderBy('landings.created_at', 'desc')
            ->active();

        $total = $modelQuery->count();

        $modelQuery
            ->take($take)
            ->skip($currentPage == 1 ? 0 : (($take * $currentPage) - $take));

        $model = $modelQuery->get();

        $items = $this->adapter->prepareModelsResults($model, $lang, Menu::TYPE_LANDING);

        $paginate = [
            'total'        => $total,
            'per_page'     => $take,
            'current_page' => $currentPage,
        ];

        $items['paginate'] = $paginate;

        $data['items'] = $items;

        return $this->successResponse($data);
    }
}
