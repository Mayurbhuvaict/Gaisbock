# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2023-05-24
### Added
- Shopware 6.5.0.0 compatibility

## [2.3.0] - 2022-05-13
### Fixed
- JavaScript error from a breaking change from Shopware with Shopware version >= 6.4.10.0

## [2.2.0] - 2022-03-18
### Added
- JavaScript improvements

## [2.1.0] - 2021-09-27
### Added
- Option to configure Facebook's push state functionality

## [2.0.0] - 2021-04-16
### Added
- Shopware 6.4.0.0 compatibility

### Changed
- Renamed icon
- Updated english label
- Config value access in twig templates
- Migrated from sales-channel-api.checkout.cart.detail API route to store-api.checkout.cart.read API route
- Updated npm dependencies for storefront
- Migrated from babel-eslint to @babel/eslint-parser
- Updated AddPaymentInfoByCheckout trigger according to new payment selection on confirmation page

### Removed
- Version numbers from API routes
- stylelint package including config and scripts for storefront

## [1.0.0] - 2020-12-08
- Initial release
