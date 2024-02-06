# Playground Login Blade

[![Playground CI Workflow](https://github.com/gammamatrix/playground-login-blade/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-login-blade/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-login-blade/testing/develop/coverage.svg)](tests)
[![PHPStan Level 9 src and tests](https://img.shields.io/badge/PHPStan-level%209-brightgreen)](.github/workflows/ci.yml#L120)

The Playground Login Blade package for [Laravel](https://laravel.com/docs/10.x) applications.

This package provides Authentication Controllers and Blade UI handling:
- Email Address Verification
- Password Management
- Authentication Supporting Roles, Privileges or Sanctum
- User Registration

More information is available [on the Playground Login Blade wiki.](https://github.com/gammamatrix/playground-login-blade/wiki)

## Installation

You can install the package via composer:

```bash
composer require gammamatrix/playground-login-blade
```

## Configuration

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Playground\Login\Blade\ServiceProvider" --tag="playground-config"
```

See the contents of the published config file: [config/playground-login-blade.php](config/playground-login-blade.php)

You can publish the views file with:
```bash
php artisan vendor:publish --provider="Playground\Login\Blade\ServiceProvider" --tag="playground-view"
```

### Environment Variables

#### Loading

| env()                                | config()                             |
|--------------------------------------|--------------------------------------|
| `PLAYGROUND_LOGIN_BLADE_LOAD_VIEWS`  | `playground-login-blade.load.views`  |
| `PLAYGROUND_LOGIN_BLADE_LOAD_ROUTES` | `playground-login-blade.load.routes` |


## Testing

```sh
composer test
```

## About

Playground Blade provides information in the `artisan about` command.

<!-- <img src="resources/docs/artisan-about-playground-blade.png" alt="screenshot of artisan about command with Playground Blade."> -->

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Jeremy Postlethwaite](https://github.com/gammamatrix)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
