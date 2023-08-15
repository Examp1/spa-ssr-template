<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Modules\Forms\Models\Form;
use App\Service\TelegramBot;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramNoticeController extends Controller
{
    use ResponseTrait;

    private $bot;

    public function __construct(TelegramBot $telegramBot)
    {
        $this->bot = $telegramBot;
    }

    public function send(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['form_id'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['form_id']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $form = Form::query()->where('id', $decodedJson['form_id'])->first();

        if (!$form)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_FORM_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );

        $groupsData = config('telegram.groups');
        $group = null;

        foreach ($groupsData as $item) {
            if ($item['id'] == $form->group_id) {
                $group =  $item;
            }
        }

        if (is_null($group)) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_FORM_GROUP_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        }

        $message = "<b>" . $form->title . "</b>" . PHP_EOL;

        $fields = $decodedJson;
        unset($fields['form_id']);

        $formData = $form->getData();
        $nameItem = [];

        foreach ($formData as $item) {
            if (isset($item['name'])) {
                $nameItem[$item['name']] = $item;
            }
        }

        foreach ($fields as $key => $field) {
            if (isset($nameItem[$key]) && isset($nameItem[$key]['show_in_message']) && $nameItem[$key]['show_in_message'] == 1) {
                $val = $field;

                if ($nameItem[$key]['type'] == "checkbox") {
                    if ($field == 1) {
                        $val = "Так";
                    } else {
                        $val = "Ні";
                    }
                }

                $mess = ($nameItem[$key]['shown_name'] != '' ? ($nameItem[$key]['shown_name'] . " - ") : '')  . $val . PHP_EOL;
                $message .= $mess;
            }
        }

        if (isset($fields['product_text'])) {
            $message .= $fields['product_text'];
        }

        $flag = $this->bot->sendMessage($message, $group['id']);

        if (!isset($flag['ok']) || !$flag['ok']) {
            return $this->errorResponse(
                ErrorManager::buildError(['message' => 'Message failed']),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        $res = [
            'success_title' => $form->success_title,
            'success_text'  => $form->success_text,
        ];

        return $this->successResponse($res);
    }
    
    //TODO: check for vulnerabilities
    public function fileUpload(Request $request)
    {
        $file = $request->file('file');
        $uploadFileInfo = pathinfo($file->getClientOriginalName());
        $file_name = str_slug($uploadFileInfo['filename']) . '_' . uniqid(time()) . '.' . $uploadFileInfo['extension'];
        $path = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'telegram');
        $file->move($path, $file_name);

        $full_url = env('APP_URL') . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'telegram' . DIRECTORY_SEPARATOR . $file_name;

        return $this->successResponse(['url' => $full_url]);
    }
}
