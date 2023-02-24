# Add translations to your Eloquent models

[![PHPUnit](https://img.shields.io/github/actions/workflow/status/nevadskiy/laravel-translatable/phpunit.yml?branch=master)](https://packagist.org/packages/nevadskiy/laravel-translatable)
[![Code Coverage](https://img.shields.io/codecov/c/github/nevadskiy/laravel-translatable?token=9X6AQQYCPA)](https://packagist.org/packages/nevadskiy/laravel-translatable)
[![Latest Stable Version](https://img.shields.io/packagist/v/nevadskiy/laravel-translatable)](https://packagist.org/packages/nevadskiy/laravel-translatable)
[![License](https://img.shields.io/github/license/nevadskiy/laravel-translatable)](https://packagist.org/packages/nevadskiy/laravel-translatable)

[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner-direct-single.svg)](https://stand-with-ukraine.pp.ua)

## 🍬 Features

- Translatable attributes behave like regular model attributes.
- Full support for accessors, mutators and casts (even JSON).
- Fallback translations.
- 4 different strategies for storing translations.

## 📺 Quick demo

```php
$book = new Book()
$book->translator()->set('title', 'Fifty miles', 'en')
$book->translator()->set('title', "П'ятдесят верстов", 'uk')
$book->save();

app()->setLocale('en');
echo $book->title; // Fifty miles

app()->setLocale('uk');
echo $book->title; // П'ятдесят верстов
```

## ✅ Requirements

- PHP `7.2` or newer
- Laravel `7.0` or newer  
- Can work with [Octane](https://github.com/laravel/octane)

## 🔌 Installation

Install the package via composer:

```bash
composer require nevadskiy/laravel-translatable
```

## 📑 Documentation

Documentation for the package can be found in the [Wiki section](https://github.com/nevadskiy/laravel-translatable/wiki). 

## 📄 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## ☕ Contributing

Thank you for considering contributing. Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for more information.

## 📜 License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
