<?php

namespace NotificationChannels\Gammu;

use NotificationChannels\Gammu\Exceptions\CouldNotSendNotification;
use NotificationChannels\Gammu\Models\Outbox;
use NotificationChannels\Gammu\Models\OutboxMultipart;
use NotificationChannels\Gammu\Models\Phone;
use Illuminate\Notifications\Notification;

class GammuChannel
{
    /**
     * @var Outbox
     */
    protected $outbox;

    /**
     * @var OutboxMultipart
     */
    protected $multipart;

    /**
     * @var Phone
     */
    protected $phone;

    /**
     * Channel constructor.
     *
     * @param Outbox $outbox
     */
    public function __construct(Outbox $outbox, OutboxMultipart $multipart, Phone $phone)
    {
        $this->outbox = $outbox;
        $this->multipart = $multipart;
        $this->phone = $phone;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $payload = $notification->toGammu($notifiable);

        if ($payload->senderNotGiven()) {
            if (! $sender = config('services.gammu.sender')) {
                if (! $sender = $this->phone->first()->ID) {
                    throw CouldNotSendNotification::senderNotProvided();
                }
            }

            $payload->sender($sender);
        }

        if ($payload->destinationNotGiven()) {
            if (! $destination = $notifiable->routeNotificationFor('gammu')) {
                throw CouldNotSendNotification::destinationNotProvided();
            }
            $payload->to($destination);
        }

        $params = $payload->toArray();

        $outbox = $this->outbox->create($params);

        $multiparts = $payload->getMultipartChunks();
        if (! empty($multiparts) && ! empty($outbox->ID)) {
            foreach ($multiparts as $chunk) {
                $chunk['ID'] = $outbox->ID;
                $this->multipart->create($chunk);
            }
        }
    }
}
