<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Langs;
use App\Models\Subscribes;
use App\Modules\Setting\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{
    use ResponseTrait;

    public function subscribe(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['email'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['email']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        if (!filter_var($decodedJson['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->errorResponse(['message' => __('Email is not correct', [], $lang)]);
        }

        if (Subscribes::query()->where('email', $decodedJson['email'])->exists()) {
            return $this->errorResponse(['message' => __('You are already subscribed to the newsletter', [], $lang)]);
        }

        try {
            Subscribes::create(['email' => $decodedJson['email']]);
        } catch (\Throwable $e) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->successResponse([]);
    }
}
