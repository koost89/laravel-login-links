# Changelog

All notable changes to `laravel-login-links` will be documented in this file.
## 1.0.1 - 2021-07-11
- Removed unneeded `hasVisitLimit` method in `CanLoginWithLink` trait.

## 1.0.0 - 2021-07-11
- Replaced `expire_after_visit` with `allowed_visits_before_expiration` config value.
Users can now specify how many times a URL can be visited before it expires.
- Added `visits` and `visits_allowed` to migration
- Updated tests
- Updated README.md

## 0.30 - 2021-07-11
- Added `generateLoginLink()` method for classes that have `CanLoginWithLink` trait.
- Added tests for `generateLoginLink()`

## 0.20 - 2021-07-11
- Added `LoginLinkGenerated` and `LoginLinkUsed` events.
- Added tests for events (and for custom config)
- Updated readme.

## 0.10 - 2021-07-11
- Initial beta release
