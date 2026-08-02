# Changelog

All notable changes to `laravel-notification-channels/sms-ir` will be documented in this file.

## 1.0.0 - 2026-08-03

- Initial release.
- `SmsIrChannel` with `SmsIrMessage` for plain-text and verification (template) sends.
- Verification sends walk a recipient list one at a time, because the sms.ir verify
  endpoint takes a single mobile; plain text goes out in one bulk request.
- A notifiable with no phone number is skipped rather than treated as an error.
- `routeNotificationForSmsIr()` is preferred, with `routeNotificationForSmsir()` accepted
  so a notifiable already set up for `farsi/laravel-smsir` keeps working.
- Upstream failures are wrapped in `CouldNotSendNotification`, carrying the sms.ir status
  code and its Persian message unchanged.
