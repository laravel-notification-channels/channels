# Changelog

All notable changes to `onewaysms` will be documented in this file.

## 1.0.0 - Unreleased

- Initial release.
- Send SMS notifications through the OneWaySMS MT endpoint.
- Automatic selection between text and Unicode encoding, with `text()` and `unicode()` overrides.
- Credit balance lookup via `OneWaySmsApi::checkBalance()`.
- A typed exception per documented gateway error code, carrying the raw code.
