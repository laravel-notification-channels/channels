<?php

namespace NotificationChannels\Notify;

use Illuminate\Http\Resources\Json\JsonResource;

class NotifyMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'message' => [
                'notificationType' => $this->getNotificationType(),
                'language' => $this->getLanguage(),
                'params' => $this->getParams(),
                'customer' => [
                    'clientId' => $this->getClientId(),
                    'secretKey' => $this->getSecret(),
                ],
                'transport' => [
                    [
                        'type' => $this->getTransport(),
                        'recipients' => [
                            'to' => $this->getTo(),
                            'cc' => $this->getCc(),
                            'bcc' => $this->getBcc(),
                        ],
                    ],
                ],
            ],
        ];
    }
}
