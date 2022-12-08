<?php

namespace NotificationChannels\WhatsApp\Component;

abstract class Component
{
    abstract public function toArray(): array;
}
