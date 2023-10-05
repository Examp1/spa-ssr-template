<?php

namespace App\Service;

use ESputnik\ESputnik;

class SendEsputnik
{
    const TEMPLATE_REGISTRATION_CONFIRM_EMAIL = 'registration_confirm_email';
    const TEMPLATE_RESET_PASSWORD = 'reset_password';

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $htmlText
     * @param $plaintText
     * @return ESputnik\Types\SendMessageResultDto|array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendMail($from, $to, $subject, $htmlText, $plaintText)
    {
        $from = '"' . config('mail.from.name') . '" <' . config('mail.from.address') . '>';

        $client = new ESputnik(config('esputnik.default.user'), config('esputnik.default.password'));

        return $client->sendEmail($from, $subject, $htmlText, $plaintText, $to);
    }
}
