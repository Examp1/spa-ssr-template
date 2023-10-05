<?php

namespace App\Service;

use Mailgun\Mailgun;

class SendMailGunTemplate
{
    const TEMPLATE_REGISTRATION_CONFIRM_EMAIL = 'stage_registration_confirm_email';
    const TEMPLATE_RESET_PASSWORD = 'stage_reset_password';
    const TEMPLATE_THANKS_FOR_REGISTRATION = 'stage_thanks_for_registration';

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $template
     * @param $template_vars
     * @return \Mailgun\Model\Message\SendResponse|\Psr\Http\Message\ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendMail($from,$to,$subject,$template,$template_vars = [])
    {
        $mgClient = Mailgun::create(
            config('mail.mailers.mailgun.secret'),
            config('mail.mailers.mailgun.endpoint')
        );
        $domain = config('mail.mailers.mailgun.domain');
        $params = [
            'from'     => $from,
            'to'       => $to,
            'subject'  => $subject,
            'template' => $template,
        ];

        if(count($template_vars)){
            foreach ($template_vars as $key => $item){
                $params['v:'.$key] = $item;
            }
        }

        return $mgClient->messages()->send($domain, $params);
    }
}
