<?php

namespace App\Mail\Transport;


use Symfony\Component\Mailer\SentMessage;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;
use Swift_TransportException;
use Symfony\Component\Mime\MessageConverter;
use ESputnik\ESputnik;

class EsputnikTransport extends Transport
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->from = '"' . config('mail.from.name') . '" <' . config('mail.from.address') . '>';
        $this->client = new ESputnik(config('esputnik.default.user'), config('esputnik.default.password'));
    }

    /**
     * {@inheritDoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $to = $this->getTo($message);

        try {
            $this->client->sendEmail($this->from, $message->getSubject(), $message->getBody(), '', $to);
        } catch (Swift_TransportException $e) {
            throw new Swift_TransportException('Request to Esputnik API failed.', $e->getCode(), $e);
        }


        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    protected function getTo(Swift_Mime_SimpleMessage $message)
    {
        return collect($this->allContacts($message))->map(function ($display, $address) {
            return $display ? $display . " <{$address}>" : $address;
        })->values()->implode(',');
    }

    protected function allContacts(Swift_Mime_SimpleMessage $message)
    {
        return array_merge(
            (array) $message->getTo(),
            (array) $message->getCc(),
            (array) $message->getBcc()
        );
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'esputnik';
    }
}
