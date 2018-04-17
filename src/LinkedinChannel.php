<?php

namespace khaninejad\linkedin;

use khaninejad\linkedin\Exceptions\CouldNotSendNotification;
use khaninejad\linkedin\Events\MessageWasSent;
use khaninejad\linkedin\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class LinkedinChannel
{
  public function __construct(\Happyr\LinkedIn\LinkedIn $linkedin)
  {
      $this->linkedin = $linkedin;
  }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\LinkedinChannel\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
      if ($linkedinSettings = $notifiable->routeNotificationFor('linkedin')) {
            $this->switchSettings($linkedinSettings);
        }

        $linkedinmessage = $notification->toLinkedin($notifiable);
        $postBody = $linkedinmessage->getRequestBody();
        $endpoint = $linkedinmessage->getApiEndpoint();
        $this->linkedin->post($endpoint, $postBody);

    }
    private function switchSettings($linkedinSettings)
    {
        $this->linkedin = new \Happyr\LinkedIn\LinkedIn($linkedinSettings);
    }
}
