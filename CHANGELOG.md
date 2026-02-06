# Changelog

All notable changes to `onlinecheckwriter` will be documented in this file.

## [Unreleased]

## [1.0.0] - 2026-01-28

### Added
- Initial release
- Document mailing support via OnlineCheckWriter API
- Check mailing support (create and mail checks in one step)
- Laravel notification channel integration
- Fluent message builder API for `OnlineCheckWriterDocumentMail`
- Fluent message builder API for `OnlineCheckWriterMailCheck`
- Automatic recipient extraction from notifiable models
- Support for `routeNotificationForOnlineCheckWriter()` method
- Default sender configuration via config file
- Custom exception handling with `OnlineCheckWriterException`
- Address verification endpoint support
- Status checking and cancellation endpoints
- Laravel 10, 11, and 12 support
