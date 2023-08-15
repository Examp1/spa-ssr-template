<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Service\Adapter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    use ResponseTrait;

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAll(Request $request)
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

        $model = Settings::query()
            ->select([
                'code', 'value', 'lang'
            ])
            ->get()
            ->groupBy('lang')->transform(function ($item, $k) {
                return $item->groupBy('code');
            })
            ->toArray();

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );

        $items = $this->adapter->prepareSettingsResults($model, $lang);

        return $this->successResponse($items);
    }
}
