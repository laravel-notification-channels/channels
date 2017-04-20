<?php

namespace NotificationChannels\Smsapi;

class SmsapiSmsMessage extends SmsapiMessage
{
    /**
     * @param string|null $content
     */
    public function __construct($content = null)
    {
        if (is_string($content)) {
            $this->data['content'] = $content;
        }
    }

    /**
     * @param  string $content
     * @return self
     */
    public function content($content)
    {
        $this->data['content'] = $content;
        return $this;
    }

    /**
     * @param  string $template
     * @return self
     */
    public function template($template)
    {
        $this->data['template'] = $template;
        return $this;
    }

    /**
     * @param  string $from
     * @return self
     */
    public function from($from)
    {
        $this->data['from'] = $from;
        return $this;
    }

    /**
     * @param  bool $fast
     * @return self
     */
    public function fast($fast)
    {
        $this->data['fast'] = $fast;
        return $this;
    }

    /**
     * @param  bool $flash
     * @return self
     */
    public function flash($flash)
    {
        $this->data['flash'] = $flash;
        return $this;
    }

    /**
     * @param  string $encoding
     * @return self
     */
    public function encoding($encoding)
    {
        $this->data['encoding'] = $encoding;
        return $this;
    }

    /**
     * @param  bool $normalize
     * @return self
     */
    public function normalize($normalize)
    {
        $this->data['normalize'] = $normalize;
        return $this;
    }

    /**
     * @param  bool $nounicode
     * @return self
     */
    public function nounicode($nounicode)
    {
        $this->data['nounicode'] = $nounicode;
        return $this;
    }

    /**
     * @param  bool $single
     * @return self
     */
    public function single($single)
    {
        $this->data['single'] = $single;
        return $this;
    }
}
