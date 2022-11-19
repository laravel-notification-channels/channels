<?php

namespace NotificationChannels\WhatsApp;

use Illuminate\Notifications\Notification;
use Netflie\WhatsAppCloudApi\Response;
use Netflie\WhatsAppCloudApi\Response\ResponseException;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use NotificationChannels\WhatsApp\Exceptions\CouldNotSendNotification;

class WhatsAppChannel
{
    /*
     * HTTP WhatsApp Cloud API wrapper
     */
    private WhatsAppCloudApi $whatsapp;

    public function __construct(WhatsAppCloudApi $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification): ?Response
    {
        // @phpstan-ignore-next-line
        $message = $notification->toWhatsApp($notifiable);

        if (! $message->hasRecipient()) {
            $to = $notifiable->routeNotificationFor('whatsapp', $notification)
                ?? $notifiable->routeNotificationFor(self::class, $notification);

            if (! $to) {
                return null;
            }

            $message->to($to);
        }

        try {
            return $this->whatsapp->sendTemplate(
                $message->recipient(),
                $message->configuredName(),
                $message->configuredLanguage(),
                $message->components()
            );
        } catch (ResponseException $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e->response()->body());
        }
    }
}
