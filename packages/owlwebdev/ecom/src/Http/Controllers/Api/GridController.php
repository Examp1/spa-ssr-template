<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Models\SizeGrid;
use App\Models\Landing;
use App\Models\Menu;
use App\Modules\Setting\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Adapter;

class GridController extends Controller
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

        $query = SizeGrid::query()
            ->leftJoin('size_grid_translations', 'size_grid_translations.size_grid_id', '=', 'size_grids.id')
            ->where('size_grid_translations.lang', $lang)
            ->select([
                'size_grids.*',
                'size_grid_translations.name AS size_gridName'
            ]);

        //show without active status
        if (!isset($decodedJson['prevw']) || $decodedJson['prevw'] != crc32($decodedJson['slug'])) {
            $query->where('size_grid_translations.status_lang', 1)->active();
        }

        $model = $query
            ->where('size_grids.slug', $request->get('slug'))
            ->first();

        if (!$model) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        }

        $data = $this->adapter->prepareModelResults($model, $lang);

        /* type */
        $data['type'] = 'size_grid';

        return $this->successResponse($data);
    }
}
