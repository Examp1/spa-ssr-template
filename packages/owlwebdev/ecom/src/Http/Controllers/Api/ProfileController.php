<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use App\Modules\Forms\Models\Form;
use App\Modules\Setting\Setting;
use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Owlwebdev\Ecom\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    use ResponseTrait;

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {

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

            $data['user'] = [
                'name' => $user->name,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'email' => $user->email,
                'birthday' => $user->birthday,
                'addresses' => $user->addresses,
            ];

            return $this->successResponse($data);
        }

        return $this->errorResponse(
            ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function showOrders(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {

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

            $data['orders'] = [];

            $orders = Order::where('user_id', $user->id)->whereIn('order_status_id', Order::PUBLIC_STATUSES)->orderBy('created_at', 'desc')->get();

            if (!empty($orders)) {
                foreach ($orders as $order) {
                    $data['orders'][] = app(CartController::class)->formatOrder($order);
                }
            }

            return $this->successResponse($data);
        }

        return $this->errorResponse(
            ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function showWishlist(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {

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

            $data['wishlist'] = [
                'share'    => $user->wishlist_share ?: '',
                'products' => $user->wishlists()->with([
                    'product' => function ($query) {
                        $query->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
                            ->where('product_translations.lang', app()->getLocale());
                        $query->with([
                            'prices' => function ($query) {
                                $query->active();
                            }
                        ]);
                        $query->select([
                            'products.*',
                            'product_translations.name',
                        ]);
                    },
                ])->get()->toArray(),
            ];

            return $this->successResponse($data);
        }

        return $this->errorResponse(
            ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function showReturn(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {

            if (isset($decodedJson['lang'])) {
                $lang = $decodedJson['lang'];
            } else {
                $lang = config('translatable.locale');
            }

            app()->setLocale($lang);

            $data['return_form'] = [];

            $return_form = cache()->remember(
                'return_form' . $lang,
                (60 * 60 * 24),
                function () use ($lang) {
                    $formId = app(Setting::class)->get('return_form', $lang);

                    if ($formId) {
                        $form = Form::query()->where('id', $formId)->first();
                        $result = [
                            'form_id' => $formId,
                            'list' => $form->getData(),
                            'btn_name' => $form->btn_name,
                        ];
                        //replace type, ex. input > form-input
                        if (is_array($result['list']) && count($result['list'])) {
                            foreach ($result['list'] as $qaw => $qwe) {
                                $result['list'][$qaw]['type'] = 'form-' . $qwe['type'];
                            }
                        }
                        return $result;
                    }

                    return [];
                }
            );

            if ($return_form) {
                $data['return_form'] = $return_form;
            }

            return $this->successResponse($data);
        }

        return $this->errorResponse(
            ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
