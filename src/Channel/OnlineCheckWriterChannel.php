<?php

namespace Zilmoney\OnlineCheckWriter\Channel;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Zilmoney\OnlineCheckWriter\Exceptions\OnlineCheckWriterException;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMailCheck;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMessage;
use Zilmoney\OnlineCheckWriter\OnlineCheckWriterClient;

class OnlineCheckWriterChannel
{
    public function __construct(
        protected OnlineCheckWriterClient $client
    ) {}

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return array|null
     * @throws OnlineCheckWriterException
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        $message = $notification->toOnlineCheckWriter($notifiable);

        if (!$message instanceof OnlineCheckWriterMessage) {
            return null;
        }

        // Try to get recipient from the notifiable if the message supports it
        $recipient = $this->getRecipientFromNotifiable($notifiable);
        if ($recipient) {
            $message->to($recipient);
        }

        // Apply default sender if this is a DocumentMail
        if ($message instanceof OnlineCheckWriterDocumentMail) {
            $defaultSender = Config::get('onlinecheckwriter.default_sender', []);
            if (!empty($defaultSender)) {
                $message->from($defaultSender);
            }
        }

        return $this->client->send($message);
    }

    /**
     * Get recipient address from the notifiable entity.
     */
    protected function getRecipientFromNotifiable(mixed $notifiable): ?array
    {
        // Check if the notifiable has a method for OnlineCheckWriter address
        if (method_exists($notifiable, 'routeNotificationForOnlineCheckWriter')) {
            return $notifiable->routeNotificationForOnlineCheckWriter();
        }

        // Check for a postal address method
        if (method_exists($notifiable, 'getPostalAddress')) {
            return $notifiable->getPostalAddress();
        }

        // Try to build from common attributes (address_line_1 format)
        if (isset($notifiable->address_line_1)) {
            return [
                'name' => $notifiable->name ?? '',
                'company' => $notifiable->company ?? null,
                'address_line_1' => $notifiable->address_line_1,
                'address_line_2' => $notifiable->address_line_2 ?? null,
                'city' => $notifiable->city ?? '',
                'state' => $notifiable->state ?? '',
                'zip' => $notifiable->zip ?? $notifiable->postal_code ?? '',
                'country' => $notifiable->country ?? 'US',
                'phone' => $notifiable->phone ?? null,
                'email' => $notifiable->email ?? null,
            ];
        }

        // Try to build from common attributes (address1 format)
        if (isset($notifiable->address1)) {
            return [
                'name' => $notifiable->name ?? '',
                'company' => $notifiable->company ?? null,
                'address1' => $notifiable->address1,
                'address2' => $notifiable->address2 ?? null,
                'city' => $notifiable->city ?? '',
                'state' => $notifiable->state ?? '',
                'zip' => $notifiable->zip ?? $notifiable->postal_code ?? '',
                'country' => $notifiable->country ?? 'US',
                'phone' => $notifiable->phone ?? null,
                'email' => $notifiable->email ?? null,
            ];
        }

        return null;
    }
}
