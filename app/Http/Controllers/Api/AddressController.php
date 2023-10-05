<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        $validateFields = [
            'name'     => 'nullable|string|between:2,100',

            'shipping_name' => 'nullable|string|between:2,100',
            'shipping_lastname' => 'nullable|string|between:2,100',
            'shipping_surname' => 'nullable|string|between:2,100',
            'shipping_phone' => 'nullable|string|between:2,100',
            'shipping_email' => 'nullable|string|email|max:100',
            'shipping_company' => 'nullable|string|between:2,100',
            'shipping_country' => 'nullable|string|between:2,100',
            'shipping_province' => 'nullable|string|between:2,100',
            'shipping_city' => 'required|string|between:2,100',
            'shipping_city_id' => 'nullable|string|between:1,100',
            'shipping_address' => 'nullable|string|between:1,100',
            'shipping_street' => 'nullable|string|between:1,100',
            'shipping_house' => 'nullable|string|between:1,100',
            'shipping_apartment' => 'nullable|string|between:1,100',
            'shipping_branch' => 'nullable|string|between:1,100',
            'shipping_branch_id' => 'nullable|string|between:1,100',
            'shipping_postcode' => 'nullable|string|between:2,100',
        ];

        $validator = Validator::make($decodedJson, $validateFields);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, $validator->errors()->toArray()),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validatedData = $validator->validated();

        if (empty($validatedData)) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD),
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!isset($validatedData['name']) || empty($validatedData['name']))  {
            $validatedData['name'] = $validatedData['shipping_name'] . ' ' . $validatedData['shipping_lastname'] . ', ' . $validatedData['shipping_city'];
        }

        try {
            $user->addresses()->create($validatedData);

            $main = $user->mainAddress();

            if (!$main) {
                $new_main = $user->addresses()->first();
                if ($new_main) {
                    $new_main->update(['main' => true]);
                }
            }
        } catch (\Throwable $e) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->successResponse($user->addresses->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->successResponse($user->addresses->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
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

        $validateFields = [
            'id' => 'required|integer',
            'main' => 'nullable|bool',
            'name'     => 'nullable|string|between:2,100',

            'shipping_name' => 'nullable|string|between:2,100',
            'shipping_lastname' => 'nullable|string|between:2,100',
            'shipping_surname' => 'nullable|string|between:2,100',
            'shipping_phone' => 'nullable|string|between:2,100',
            'shipping_email' => 'nullable|string|email|max:100',
            'shipping_company' => 'nullable|string|between:2,100',
            'shipping_country' => 'nullable|string|between:2,100',
            'shipping_province' => 'nullable|string|between:2,100',
            'shipping_city' => 'required|string|between:2,100',
            'shipping_city_id' => 'nullable|string|between:1,100',
            'shipping_address' => 'nullable|string|between:1,100',
            'shipping_street' => 'nullable|string|between:1,100',
            'shipping_house' => 'nullable|string|between:1,100',
            'shipping_apartment' => 'nullable|string|between:1,100',
            'shipping_branch' => 'nullable|string|between:1,100',
            'shipping_branch_id' => 'nullable|string|between:1,100',
            'shipping_postcode' => 'nullable|string|between:2,100',
        ];

        $validator = Validator::make($decodedJson, $validateFields);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, $validator->errors()->toArray()),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validatedData = $validator->validated();

        if (empty($validatedData)) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD),
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $addr = $user->addresses()->find($validatedData['id']);

            if ($addr) {
                if (isset($validatedData['main']) && $validatedData['main'] == true) {
                    $user->addresses()->update(['main' => false]);
                }

                $addr->update($validatedData);

                $main = $user->mainAddress();

                if (!$main) {
                    $new_main = $user->addresses()->first();
                    if ($new_main) {
                        $new_main->update(['main' => true]);
                    }
                }

            } else {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND, []),
                    Response::HTTP_NOT_FOUND // 404
                );
            }
        } catch (\Throwable $e) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->successResponse($user->addresses->toArray());
    }

    public function setMain(Request $request)
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

        $validateFields = [
            'id' => 'required|integer',
        ];

        $validator = Validator::make($decodedJson, $validateFields);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, $validator->errors()->toArray()),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validatedData = $validator->validated();

        if (empty($validatedData)) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD),
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $addr = $user->addresses()->find($validatedData['id']);

            if ($addr) {
                $user->addresses()->update(['main' => false]);

                $addr->update(['main' => true]);

            } else {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND, []),
                    Response::HTTP_NOT_FOUND // 404
                );
            }
        } catch (\Throwable $e) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->successResponse($user->addresses->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
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

        if (!isset($decodedJson['id'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['wishlist']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $addr = $user->addresses()->find($decodedJson['id']);

            if ($addr) {
                $addr->delete();

                $main = $user->mainAddress();
                if (!$main) {
                    $new_main = $user->addresses()->first();
                    if ($new_main) {
                        $new_main->update(['main' => true]);
                    }
                }
            } else {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND, []),
                    Response::HTTP_NOT_FOUND // 404
                );
            }
        } catch (\Throwable $e) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_EXCEPTION, [$e->getMessage()]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->successResponse($user->addresses->toArray());
    }
}
