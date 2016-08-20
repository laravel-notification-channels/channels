<?php

namespace NotificationChannels\Gammu;

use NotificationChannels\Gammu\Exceptions\CouldNotSendNotification;
use NotificationChannels\Gammu\Models\Outbox;
use NotificationChannels\Gammu\Models\Phone;
use Illuminate\Notifications\Notification;

class GammuChannel
{
    /**
     * @var Outbox
     */
    protected $outbox;
    
    /**
     * @var Phone
     */
    protected $phone;
    
    /**
     * Channel constructor.
     *
     * @param Outbox $outbox
     */
    public function __construct(Outbox $outbox, Phone $phone)
    {
        $this->outbox = $outbox;
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
            if (!$sender = config('services.gammu.sender')) {
                if (!$sender = $this->phone->first()->ID) {
                    throw CouldNotSendNotification::senderNotProvided();
                }
            }
            
            $payload->sender($sender);
        }
        
        if ($payload->destinationNotGiven()) {
            if (!$destination = $notifiable->routeNotificationFor('gammu')) {
                throw CouldNotSendNotification::destinationNotProvided();
            }
            $payload->to($destination);
        }
        
        $params = $payload->toArray();
        
        $this->outbox->create($params);
    }
}
