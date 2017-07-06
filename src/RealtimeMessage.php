<?php

namespace NotificationChannels\RealtimePushNotifications;

class RealtimeMessage
{
    /** @var string */
    public $iosTitle;

    /** @var string */
    public $iosSubtitle;

    /** @var string */
    public $iosBody;

    /** @var string */
    public $sound;

    /** @var int */
    public $badge;

    /** @var int */
    public $iosMutableContent;

    /** @var string */
    public $iosAttachmentUrl;
    
    /** @var string */
    public $androidMessage;

    /** @var array */
    public $iosPayload;

    /** @var array */
    public $androidPayload;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }
    
    public function __construct()
    {
    }

    /**
     * Set the iosTitle.
     *
     * @param  string  $title
     *
     * @return $this
     */
    public function iosTitle($title)
    {
        $this->iosTitle = $title;

        return $this;
    }

    /**
     * Set the iosSubtitle.
     *
     * @param  string  $iosSubtitle
     *
     * @return $this
     */
    public function iosSubtitle($iosSubtitle)
    {
        $this->iosSubtitle = $iosSubtitle;

        return $this;
    }

    /**
     * Set the iosBody.
     *
     * @param  string  $body
     *
     * @return $this
     */
    public function iosBody($body)
    {
        $this->iosBody = $body;

        return $this;
    }

    /**
     * Set the sound.
     *
     * @param  string  $sound
     *
     * @return $this
     */
    public function sound($sound)
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * Set the badge.
     *
     * @param  int  $badge
     *
     * @return $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the iosMutableContent.
     *
     * @param  int  $iosMutableContent
     *
     * @return $this
     */
    public function iosMutableContent($iosMutableContent)
    {
        $this->iosMutableContent = $iosMutableContent;

        return $this;
    }

    /**
     * Set the iosAttachmentUrl.
     *
     * @param  string  $iosAttachmentUrl
     *
     * @return $this
     */
    public function iosAttachmentUrl($iosAttachmentUrl)
    {
        $this->iosAttachmentUrl = $iosAttachmentUrl;

        return $this;
    }

    /**
     * Set the androidMessage.
     *
     * @param  string  $androidMessage
     *
     * @return $this
     */
    public function androidMessage($androidMessage)
    {
        $this->androidMessage = $androidMessage;

        return $this;
    }

    /**
     * Set the iosPayload.
     *
     * @param  array  $iosPayload
     *
     * @return $this
     */
    public function iosPayload($iosPayload)
    {
        $this->iosPayload = $iosPayload;

        return $this;
    }

    /**
     * Set the androidPayload.
     *
     * @param  array  $androidPayload
     *
     * @return $this
     */
    public function androidPayload($androidPayload)
    {
        $this->androidPayload = $androidPayload;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        /*ios data*/
        $alert = [];

        if (! empty($this->iosTitle)) {
            $alert['title'] = $this->iosTitle;
        }

        if (! empty($this->iosSubtitle)) {
            $alert['subtitle'] = $this->iosSubtitle;
        }

        if (! empty($this->iosBody)) {
            $alert['body'] = $this->iosBody;
        }

        $aps = [];

        if (! empty($this->sound)) {
            $aps['sound'] = $this->sound;
        }

        if (! empty($this->badge)) {
            $aps['badge'] = $this->badge;
        }

        if (! empty($this->iosMutableContent)) {
            $aps['mutable-content'] = $this->iosMutableContent;
        }

        if (! empty($this->iosAttachmentUrl)) {
            $aps['data']['attachment-url'] = $this->iosAttachmentUrl;
        }

        if (count($alert) > 0) {
            $aps['alert'] = $alert;
        }
        
        /*android data*/
        $androidData = [];

        if (! empty($this->androidMessage)) {
            $androidData['M'] = $this->androidMessage;
        }

        if (count($this->androidPayload) > 0) {
            $androidData = array_merge($androidData, [$this->androidPayload]);
        }

        /*final*/
        $data = [];

        if (count($aps) > 0) {
            $data['apns']['aps'] = $aps;
        }

        if (count($androidData) > 0) {
            $data['gcm']['data'] = $androidData;
        }

        if (! empty($this->iosPayload)) {
            $data['payload'] = $this->iosPayload;
        }

        return $data;
    }
}
