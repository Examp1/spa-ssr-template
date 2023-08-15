<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Service\Adapter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    use ResponseTrait;

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getByIds(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['ids'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['ids']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        $items = cache()->remember(
            'menu_' . $lang . '_' . implode('_', $decodedJson['ids']),
            (60 * 60 * 24),
            function () use ($decodedJson, $lang) {
                return $this->adapter->prepareMenuResults($decodedJson['ids'], $lang);;
            }
        );

        $data['items'] = $items;

        return $this->successResponse($data);
    }
}
