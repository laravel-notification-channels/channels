<?php

namespace NotificationChannels\IssueBadge;

use Illuminate\Notifications\Notification;
use NotificationChannels\IssueBadge\Exceptions\CouldNotSendNotification;

class IssueBadgeChannel
{
    public function __construct(protected IssueBadge $client)
    {
    }

    /**
     * Issue the badge described by the notification's toIssueBadge() method.
     *
     * The recipient is resolved in this order: the message's name()/email(),
     * then routeNotificationForIssuebadge() (an email string or
     * ['name' => ..., 'email' => ...]), then the notifiable's name/email attributes.
     *
     * @param  mixed  $notifiable
     * @return array The IssueBadge API response (IssueId, publicUrl, ...)
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification): array
    {
        if (! method_exists($notification, 'toIssueBadge')) {
            throw CouldNotSendNotification::missingToIssueBadgeMethod();
        }

        $message = $notification->toIssueBadge($notifiable);

        if (is_string($message)) {
            $message = new IssueBadgeMessage($message);
        }

        if (! $message instanceof IssueBadgeMessage) {
            throw CouldNotSendNotification::invalidMessage();
        }

        if (! $message->getBadgeId()) {
            throw CouldNotSendNotification::missingBadgeId();
        }

        [$routeName, $routeEmail] = $this->resolveRoute($notifiable, $notification);

        $name = $message->getName() ?? $routeName ?? $this->attribute($notifiable, 'name');
        $email = $message->getEmail() ?? $routeEmail ?? $this->attribute($notifiable, 'email');

        if (! $name) {
            throw CouldNotSendNotification::missingRecipientName();
        }

        $message->name($name)->email($email ?: null);

        return $this->client->issue($message->toArray());
    }

    /** @return array{0: ?string, 1: ?string} [name, email] */
    protected function resolveRoute($notifiable, Notification $notification): array
    {
        if (! is_object($notifiable) || ! method_exists($notifiable, 'routeNotificationFor')) {
            return [null, null];
        }

        $route = $notifiable->routeNotificationFor('issuebadge', $notification);

        if (is_string($route)) {
            return [null, $route];
        }

        if (is_array($route)) {
            return [$route['name'] ?? null, $route['email'] ?? null];
        }

        return [null, null];
    }

    protected function attribute($notifiable, string $key): ?string
    {
        if (is_array($notifiable)) {
            return isset($notifiable[$key]) ? (string) $notifiable[$key] : null;
        }

        if (is_object($notifiable) && isset($notifiable->{$key})) {
            return (string) $notifiable->{$key};
        }

        return null;
    }
}
