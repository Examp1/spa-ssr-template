<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
    use ResponseTrait;

    public function update(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['product_id'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['product_id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $product_id = $decodedJson['product_id'];
        $user = auth('api')->user();

        if ($user->wishlists()->where('product_id', $product_id)->first()) {
            try {
                $user->wishlists()->where('product_id', $product_id)->delete();
            } catch (\Throwable $e) {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        } else {

            try {
                $user->wishlists()->firstOrCreate(['product_id' => $product_id]);
            } catch (\Throwable $e) {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }

        $wl['wishlist_product_ids'] = $user->wishlists()->pluck('product_id')->toArray();

        return $this->successResponse($wl);
    }

    public function sync(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['wishlist'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['wishlist']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $wl = $decodedJson['wishlist'];

        if (!empty($wl)) {

            foreach ($wl as $item) {
                try {
                    $user->wishlists()->firstOrCreate(['product_id' => $item]);
                } catch (\Throwable $e) {
                    return $this->errorResponse(
                        ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }
            }

            return $this->successResponse($user->wishlists()->pluck('product_id')->toArray());
        }
    }

    public function show(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['wishlist'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['wishlist']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $wl = $decodedJson['wishlist'];

        if (!empty($wl) && is_array($wl)) {
            $data['products'] = Product::query()
                ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
                ->where('product_translations.lang', app()->getLocale())
                ->whereIn('products.id', $wl)
                ->with(['prices' => function ($query) {
                    $query->active();
                }])
                ->select([
                    'products.*',
                    'product_translations.name',
                ])
                ->active()
                ->get()
                ->toArray();

            return $this->successResponse($data);
        }

        return $this->errorResponse(
            ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['wishlist']),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
