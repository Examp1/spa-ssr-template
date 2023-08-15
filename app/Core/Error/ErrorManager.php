<?php


namespace App\Core\Error;


class ErrorManager
{
    static public function buildError($code, $params = []): array
    {
        $res = [
            'code'    => $code,
            'message' => config('api_errors')[$code]['message']
        ];

        if (count($params)) {
            $res['params'] = $params;
        }

        return $res;
    }
}
