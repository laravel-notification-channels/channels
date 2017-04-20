<?php

namespace NotificationChannels\Smsapi;

class SmsapiSmsMessage extends SmsapiMessage
{
    /**
     * @param string|null $content
     */
    public function __construct(string $content = null)
    {
        if (is_string($content)) {
            $this->data['content'] = $content;
        }
    }

    /**
     * @param  string $content
     * @return self
     */
    public function content(string $content): self
    {
        $this->data['content'] = $content;
        return $this;
    }

    /**
     * @param  string $template
     * @return self
     */
    public function template(string $template): self
    {
        $this->data['template'] = $template;
        return $this;
    }

    /**
     * @param  string $from
     * @return self
     */
    public function from(string $from): self
    {
        $this->data['from'] = $from;
        return $this;
    }

    /**
     * @param  bool $fast
     * @return self
     */
    public function fast(bool $fast): self
    {
        $this->data['fast'] = $fast;
        return $this;
    }

    /**
     * @param  bool $flash
     * @return self
     */
    public function flash(bool $flash): self
    {
        $this->data['flash'] = $flash;
        return $this;
    }

    /**
     * @param  string $encoding
     * @return self
     */
    public function encoding(string $encoding): self
    {
        $this->data['encoding'] = $encoding;
        return $this;
    }

    /**
     * @param  bool $normalize
     * @return self
     */
    public function normalize(bool $normalize): self
    {
        $this->data['normalize'] = $normalize;
        return $this;
    }

    /**
     * @param  bool $nounicode
     * @return self
     */
    public function nounicode(bool $nounicode): self
    {
        $this->data['nounicode'] = $nounicode;
        return $this;
    }

    /**
     * @param  bool $single
     * @return self
     */
    public function single(bool $single): self
    {
        $this->data['single'] = $single;
        return $this;
    }
}
