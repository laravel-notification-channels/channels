<?php

namespace NotificationChannels\IssueBadge\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class CouldNotSendNotification extends \Exception
{
    public static function missingToIssueBadgeMethod(): self
    {
        return new static('Notification is missing a toIssueBadge($notifiable) method.');
    }

    public static function invalidMessage(): self
    {
        return new static('toIssueBadge() must return an IssueBadgeMessage or a badge ID string.');
    }

    public static function missingBadgeId(): self
    {
        return new static('No badge ID was set. Call ->badge($id) on the message or return the ID from toIssueBadge().');
    }

    public static function missingRecipientName(): self
    {
        return new static(
            'No recipient name could be resolved. Call ->name() on the message, return ["name" => ..., "email" => ...] '.
            'from routeNotificationForIssuebadge(), or give the notifiable a "name" attribute.'
        );
    }

    public static function serviceRespondedWithAnError(?ResponseInterface $response, ?Throwable $previous = null): self
    {
        if ($response === null) {
            return new static('IssueBadge responded with an error.', 0, $previous);
        }

        $status = $response->getStatusCode();
        $body = json_decode((string) $response->getBody(), true);
        $message = is_array($body) ? ($body['message'] ?? null) : null;

        if (is_array($body) && ! empty($body['errors']) && is_array($body['errors'])) {
            $details = [];
            foreach ($body['errors'] as $field => $errors) {
                $details[] = $field.': '.implode(' ', (array) $errors);
            }
            $message = ($message ?: 'Validation failed').' ('.implode('; ', $details).')';
        }

        return new static(
            sprintf('IssueBadge responded with HTTP %d: %s', $status, $message ?: 'unknown error'),
            $status,
            $previous
        );
    }

    public static function serviceCommunicationError(Throwable $exception): self
    {
        return new static('Could not reach IssueBadge: '.$exception->getMessage(), 0, $exception);
    }
}
