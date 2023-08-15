<?php

namespace App\Service;

class TelegramBot
{
    /**
     * @param $message
     * @param $group_id
     * @return false|mixed
     */
    public function sendMessage($message,$group_id)
    {
        $groups = config('telegram.groups');

        $group = null;

        foreach ($groups as $item){
            if($item['id'] == $group_id){
                $group = $item['bot'];
            }
        }

        if($group){
            $curlProps = [
                CURLOPT_URL            => 'https://api.telegram.org/bot' . $group['token'] . '/sendMessage',
                CURLOPT_POST           => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_POSTFIELDS     => array(
                    'chat_id'    => $group['chat_id'],
                    'text'       => $message,
                    'parse_mode' => 'HTML',
                ),
            ];
            $curl      = curl_init();

            curl_setopt_array($curl, $curlProps);
            $response = curl_exec($curl);

            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                //
            }

            return json_decode($response, true);
        } else {
            return false;
        }
    }

}
