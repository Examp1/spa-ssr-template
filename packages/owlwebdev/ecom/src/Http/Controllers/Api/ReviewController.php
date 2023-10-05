<?php

namespace Owlwebdev\Ecom\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Models\Product;
use Owlwebdev\Ecom\Models\Review;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ResponseTrait;

    public function getData(array $request)
    {
        if (empty($request)) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($request['lang'])) {
            $lang = $request['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        return $request;
    }

    public function get(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        $validator = Validator::make($decodedJson, [
            'product_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->getMessages(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['limit']) && (int)$decodedJson['limit'] < 100) {
            $limit = (int)$decodedJson['limit'];
        } else {
            $limit = 20; // TODO: get from admin settings
        }

        $validated = $validator->validated();

        $query = Product::query()
            ->where('id', $validated['product_id']);


        //show without active status
        if (!isset($decodedJson['prevw'])) {
            $query->active();
        }

        $product = $query->first();

        //show without active status
        if (!$product || (isset($decodedJson['prevw']) && $decodedJson['prevw'] != crc32($product->slug))) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_FOUND
            );
        }
        $reviews = $product->reviews()
            ->select(['id', 'author', 'text', 'rating', 'created_at'])
            ->active()
            ->paginate($limit);

        return $this->successResponse($reviews->toArray());
        // } catch (\Throwable $e) {
        //     return $this->errorResponse(
        //         ErrorManager::buildError(VALIDATION_EXCEPTION, []),
        //         Response::HTTP_UNPROCESSABLE_ENTITY
        //     );
        // }
    }

    public function add(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        $user = auth('api')->user();

        $validator = Validator::make($decodedJson, [
            'product_id' => 'required|numeric',
            'user_id'    => 'numeric|nullable',
            'author'     => 'required|min:2|max:25',
            'city'       => 'string|max:50|nullable',
            'email'      => 'sometimes|email|nullable',
            'text'       => 'required|min:1|max:1500',
            'rating'     => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->getMessages(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        if ($user) {
            $validated['user_id'] = $user->id;
            $validated['email'] = $user->email;
        }

        try {
            $product = Product::query()
                ->where('id', $validated['product_id'])
                ->where('status', Product::STATUS_ACTIVE)->first();

            if (!$product) {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                    Response::HTTP_FOUND
                );
            }

            $review = $product->reviews()->create([
                'author'     => trim($validated['author'] ?? ''),
                'email'      => $validated['email'] ?? null,
                'user_id'    => $validated['user_id'] ?? null,
                'city'       => trim($validated['city'] ?? ''),
                'rating'     => trim($validated['rating'] ?? ''),
                'text'       => nl2br(trim(htmlspecialchars($validated['text']))),
                'status'     => 0,
            ]);

            // \Mail::to(setting('email'))->send(new ReviewMail($review));

            return $this->successResponse([]);
        } catch (\Throwable $e) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_EXCEPTION, []),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    public function answer(Request $request)
    {
        $decodedJson = $this->getData($request->json()->all());

        $user = auth('api')->user();

        $validator = Validator::make($decodedJson, [
            'product_id' => 'required|numeric',
            'review_id'  => 'required|numeric',
            'user_id'    => 'numeric|nullable',
            'author'     => 'required|min:2|max:25',
            'city'       => 'string|max:50|nullable',
            'email'      => 'sometimes|email|nullable',
            'text'       => 'required|min:1|max:1500',
            'rating'     => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->getMessages(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        if ($user) {
            $validated['user_id'] = $user->id;
            $validated['email'] = $user->email;
        }

        $review_id = intval($validated['review_id']);

        $review = Review::query()
            ->where('id', $review_id)
            ->where('status', Product::STATUS_ACTIVE)->first();

        if (!$review) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_FOUND
            );
        }

        $status = Review::query()->create([
            'author'     => trim($validated['author']),
            'city'       => !empty($validated['city']) ? trim($validated['city']) : null,
            'rating'     => null,
            'text'       => nl2br(trim(htmlspecialchars($validated['text']))),
            'status'     => 0,
            'email'      => $validated['email'],
            'parent_id'  => $review->id,
            'product_id' => $review->product_id,
        ]);

        if ($status) {
            return [
                'status'  => true,
                'message' => __('reviews.thanks_for_review'),
            ];
        } else {
            return [
                'status'  => false,
                'message' => __('reviews.problem'),
            ];
        }
    }
}
