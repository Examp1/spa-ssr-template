<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Service\Adapter;
use phpDocumentor\Reflection\File;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    use ResponseTrait;

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function index(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }


        $pathToFile = $this->getFile();

        if ($pathToFile) {
            $file = file_get_contents($pathToFile);
            $data['file'] = $file;
        } else {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->successResponse($data);
    }

    private function getFile()
    {
        $pathToFile = public_path() . '/sitemap.xml';

        if (file_exists($pathToFile)) {
            return $pathToFile;
        } else {
            return null;
        }
    }
}
