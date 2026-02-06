# OnlineCheckWriter Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/onlinecheckwriter.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onlinecheckwriter)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/onlinecheckwriter.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onlinecheckwriter)

This package makes it easy to send physical mail and checks using [OnlineCheckWriter](https://onlinecheckwriter.com) with Laravel Notifications.

## Contents

- [Installation](#installation)
- [Setting Up Your API Key](#setting-up-your-api-key)
    - [Sandbox Environment](#sandbox-environment)
    - [Production Environment](#production-environment)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Sending Document Mail](#sending-document-mail)
    - [Sending Checks](#sending-checks)
    - [Using the Facade Directly](#using-the-facade-directly)
- [Available Message Methods](#available-message-methods)
- [Routing Notifications](#routing-notifications)
- [Error Handling](#error-handling)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

```bash
composer require laravel-notification-channels/onlinecheckwriter
```

## Setting Up Your API Key

### Sandbox Environment

Use the sandbox environment for testing and development.

| Setting | Value |
|---------|-------|
| Base URL | `https://test.onlinecheckwriter.com/api/v3` |
| Sign Up | [test.onlinecheckwriter.com](https://test.onlinecheckwriter.com) |

**Setup Steps:**

1. Sign up for a test account at [test.onlinecheckwriter.com](https://test.onlinecheckwriter.com) and get your API key
2. If verification issues arise, contact customer support via live chat or email
3. Configure your `.env` with the sandbox base URL:
   ```env
   ONLINECHECKWRITER_BASE_URL=https://test.onlinecheckwriter.com/api/v3
   ```
4. Add a test bank account using provided sample numbers
5. Run a comprehensive test to confirm setup
6. For optional ACH/Direct Deposit testing, contact support

### Production Environment

> **Important:** Before going live, contact the OnlineCheckWriter support team to enable the API in the production environment. Failure to do so will result in an "Access Denied" error.

| Setting | Value |
|---------|-------|
| Base URL | `https://app.onlinecheckwriter.com/api/v3` |
| Sign Up | [onlinecheckwriter.com](https://onlinecheckwriter.com) |

**Setup Steps:**

1. Contact support to activate Live API access
2. Sign up for a Live account at [onlinecheckwriter.com](https://onlinecheckwriter.com) and get your API key
3. If verification issues arise, contact customer support via live chat or email
4. Configure your `.env` with the production base URL:
   ```env
   ONLINECHECKWRITER_BASE_URL=https://app.onlinecheckwriter.com/api/v3
   ```
5. Add your bank account
6. Run a comprehensive test to confirm setup

**Support Contact:** [support@onlinecheckwriter.com](mailto:support@onlinecheckwriter.com)

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=onlinecheckwriter-config
```

Add your OnlineCheckWriter API credentials to your `.env` file:

```env
ONLINECHECKWRITER_API_KEY=your-api-key

# For Sandbox/Testing:
ONLINECHECKWRITER_BASE_URL=https://test.onlinecheckwriter.com/api/v3

# For Production (uncomment when ready):
# ONLINECHECKWRITER_BASE_URL=https://app.onlinecheckwriter.com/api/v3

# Default sender/return address
ONLINECHECKWRITER_SENDER_NAME="Your Company Name"
ONLINECHECKWRITER_SENDER_COMPANY="Your Company LLC"
ONLINECHECKWRITER_SENDER_ADDRESS_1="123 Main Street"
ONLINECHECKWRITER_SENDER_CITY="New York"
ONLINECHECKWRITER_SENDER_STATE="NY"
ONLINECHECKWRITER_SENDER_ZIP="10001"
ONLINECHECKWRITER_SENDER_PHONE="1234567890"

# For check mailing (contact OnlineCheckWriter.com to get your Bank Account ID)
ONLINECHECKWRITER_BANK_ACCOUNT_ID=your-bank-account-id
```

## Usage

### Sending Document Mail

You can use the channel in your notification class:

```php
use Illuminate\Notifications\Notification;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;

class InvoiceNotification extends Notification
{
    public function via($notifiable): array
    {
        return ['onlinecheckwriter'];
    }

    public function toOnlineCheckWriter($notifiable): OnlineCheckWriterDocumentMail
    {
        return OnlineCheckWriterDocumentMail::create()
            ->file('/path/to/invoice.pdf')
            ->documentTitle('Invoice #1234')
            ->name($notifiable->name)
            ->company($notifiable->company)
            ->address1($notifiable->address1)
            ->city($notifiable->city)
            ->state($notifiable->state)
            ->zip($notifiable->zip)
            ->shippingType(3); // 1=Standard, 2=Express, 3=Priority
    }
}
```

Then send the notification:

```php
$user->notify(new InvoiceNotification());
```

#### Using a Pre-uploaded Document URL

```php
public function toOnlineCheckWriter($notifiable): OnlineCheckWriterDocumentMail
{
    return OnlineCheckWriterDocumentMail::create()
        ->attachmentUrl('https://your-storage.com/document.pdf')
        ->documentTitle('Invoice #1234')
        ->to([
            'name' => $notifiable->name,
            'company' => $notifiable->company,
            'address1' => $notifiable->address1,
            'city' => $notifiable->city,
            'state' => $notifiable->state,
            'zip' => $notifiable->zip,
        ])
        ->from([
            'name' => 'Your Company',
            'address1' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
        ]);
}
```

### Sending Checks

> **Note:** To get your Bank Account ID, please contact [OnlineCheckWriter.com](https://onlinecheckwriter.com).

```php
use Illuminate\Notifications\Notification;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMailCheck;

class PaymentNotification extends Notification
{
    public function __construct(
        protected float $amount,
        protected string $memo
    ) {}

    public function via($notifiable): array
    {
        return ['onlinecheckwriter'];
    }

    public function toOnlineCheckWriter($notifiable): OnlineCheckWriterMailCheck
    {
        return OnlineCheckWriterMailCheck::create()
            ->bankAccount(config('onlinecheckwriter.default_bank_account_id'))
            ->amount($this->amount)
            ->memo($this->memo)
            ->note('Internal reference: PAY-001')
            ->issueDate(now()->format('Y-m-d'))
            ->name($notifiable->name)
            ->company($notifiable->company)
            ->address1($notifiable->address1)
            ->city($notifiable->city)
            ->state($notifiable->state)
            ->zip($notifiable->zip)
            ->shippingType(1);
    }
}
```

Then send the notification:

```php
$vendor->notify(new PaymentNotification(500.00, 'Invoice #1234 Payment'));
```

### Using the Facade Directly

You can also use the facade for direct API access:

```php
use Zilmoney\OnlineCheckWriter\OnlineCheckWriter;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;

// Upload and mail a document
$mail = OnlineCheckWriterDocumentMail::create()
    ->file('/path/to/invoice.pdf')
    ->documentTitle('Invoice 1244')
    ->name('John Doe')
    ->company('ABC Corporation')
    ->address1('5007 Richmond Rd')
    ->city('Tyler')
    ->state('TX')
    ->zip('75701')
    ->phone('1234567890')
    ->email('john@example.com')
    ->shippingType(3);

$response = OnlineCheckWriter::send($mail);

// Upload document only
$response = OnlineCheckWriter::uploadDocumentForMailing(
    '/path/to/document.pdf',
    'Document Title'
);
$attachmentUrl = $response['data']['file_url'];

// Verify an address
$response = OnlineCheckWriter::verifyAddress([
    'address1' => '123 Main St',
    'city' => 'New York',
    'state' => 'NY',
    'zip' => '10001',
]);

// Check status
$response = OnlineCheckWriter::getStatus('documentmailing', 'item-id');

// Cancel a pending item
$response = OnlineCheckWriter::cancel('documentmailing', 'item-id');
```

## Available Message Methods

### OnlineCheckWriterDocumentMail

| Method | Description |
|--------|-------------|
| `file($path)` | Set the PDF file path to upload |
| `attachmentUrl($url)` | Set pre-uploaded attachment URL |
| `documentTitle($title)` | Set the document title |
| `name($name)` | Set recipient name |
| `company($company)` | Set recipient company |
| `address1($address)` | Set recipient address line 1 |
| `address2($address)` | Set recipient address line 2 |
| `city($city)` | Set recipient city |
| `state($state)` | Set recipient state |
| `zip($zip)` | Set recipient zip code |
| `phone($phone)` | Set recipient phone |
| `email($email)` | Set recipient email |
| `shippingType($id)` | Set shipping type (1=Standard, 2=Express, 3=Priority) |
| `from($address)` | Set sender/return address array |
| `to($address)` | Set recipient address from array |

### OnlineCheckWriterMailCheck

| Method | Description |
|--------|-------------|
| `bankAccount($id)` | Set the bank account ID |
| `accountType($type)` | Set account type (default: 'bankaccount') |
| `amount($amount)` | Set the check amount |
| `memo($memo)` | Set the memo line (appears on check) |
| `note($note)` | Set internal note (not printed on check) |
| `issueDate($date)` | Set issue date (YYYY-MM-DD format) |
| `name($name)` | Set recipient name |
| `company($company)` | Set recipient company |
| `address1($address)` | Set recipient address line 1 |
| `address2($address)` | Set recipient address line 2 |
| `city($city)` | Set recipient city |
| `state($state)` | Set recipient state |
| `zip($zip)` | Set recipient zip code |
| `phone($phone)` | Set recipient phone |
| `email($email)` | Set recipient email |
| `shippingType($id)` | Set shipping type |
| `to($address)` | Set recipient address from array |

## Routing Notifications

You can customize how notifications are routed by defining a `routeNotificationForOnlineCheckWriter` method on your notifiable model:

```php
class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForOnlineCheckWriter(): array
    {
        return [
            'name' => $this->full_name,
            'company' => $this->company_name,
            'address1' => $this->street_address,
            'address2' => $this->apartment_number,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->postal_code,
            'phone' => $this->phone_number,
            'email' => $this->email,
        ];
    }
}
```

Alternatively, the channel will automatically extract address information from these model attributes if they exist:
- `address_line_1` or `address1`
- `address_line_2` or `address2`
- `city`, `state`, `zip` (or `postal_code`)
- `name`, `company`, `phone`, `email`

## Error Handling

```php
use Zilmoney\OnlineCheckWriter\Exceptions\OnlineCheckWriterException;

try {
    $response = OnlineCheckWriter::send($mail);
} catch (OnlineCheckWriterException $e) {
    if ($e->isValidationError()) {
        // Handle validation errors (422)
        $errors = $e->getResponse();
    } elseif ($e->isAuthenticationError()) {
        // Handle authentication errors (401)
    } elseif ($e->isRateLimitError()) {
        // Handle rate limiting (429)
    }

    logger()->error('OnlineCheckWriter error: ' . $e->getMessage(), [
        'response' => $e->getResponse(),
    ]);
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email developer@zilmoney.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](https://github.com/laravel-notification-channels/.github/blob/main/CONTRIBUTING.md) for details.

## Credits

- [Zilmoney](https://github.com/zilmoney)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
