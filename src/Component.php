<?php

namespace NotificationChannels\WhatsApp;

class Component
{
    /**
     * Currency code as defined in ISO 4217.
     */
    public static function currency(float $amount, string $code = 'EUR'): Component\Currency
    {
        return new Component\Currency($amount, $code);
    }

    public static function dateTime(\DateTimeImmutable $dateTime, string $format = 'Y-m-d H:i:s'): Component\DateTime
    {
        return new Component\DateTime($dateTime, $format);
    }

    public static function document(string $link): Component\Document
    {
        return new Component\Document($link);
    }

    public static function image(string $link): Component\Image
    {
        return new Component\Image($link);
    }

    public static function text(string $text): Component\Text
    {
        return new Component\Text($text);
    }

    public static function video(string $link): Component\Video
    {
        return new Component\Video($link);
    }
}
