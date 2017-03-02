<?php

namespace NotificationChannels\Wechat;

use EasyWeChat\Foundation\Application;
use Illuminate\Container\Container;

class WeChatMessage
{
    /**
     * EasyWeChat Notice instance.
     *
     * @var \EasyWeChat\Notice\Notice
     */
    protected $notice;

    /**
     * WechatMessage constructor.
     */
    public function __construct()
    {
        $app = Container::getInstance()->make(Application::class);

        $this->notice = $app->notice;
    }

    /**
     * Magic Call access.
     *
     * @param  string $method
     * @param  string|array $args
     *
     * @return $this
     */
    public function __call($method, $args)
    {
        call_user_func_array([$this->notice, $method], $args);

        return $this;
    }
}
