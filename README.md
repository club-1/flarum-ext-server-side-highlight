# Server Side Highlight

![License](https://img.shields.io/badge/license-AGPL--3.0--or--later-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/club-1/flarum-ext-server-side-highlight.svg)](https://packagist.org/packages/club-1/flarum-ext-server-side-highlight) [![Total Downloads](https://img.shields.io/packagist/dt/club-1/flarum-ext-server-side-highlight.svg)](https://packagist.org/packages/club-1/flarum-ext-server-side-highlight)

A [Flarum](http://flarum.org) extension. Server-side code highlighting with cached results.

A hash for each code block of the posts is computed while parsing them, then on render the code highlighting is done in the backend once and stored in the cache for future renderings.

## Installation

Install with composer:

```sh
composer require club-1/flarum-ext-server-side-highlight:"*"
```

## Updating

```sh
composer update club-1/flarum-ext-server-side-highlight:"*"
php flarum cache:clear
```

## Acknowledgement

This extension is based on the following libraries:

- [highlight.php](https://github.com/scrivo/highlight.php)

## Links

- [Packagist](https://packagist.org/packages/club-1/flarum-ext-server-side-highlight)
- [GitHub](https://github.com/club-1/flarum-ext-server-side-highlight)
<!--
- [Discuss](https://discuss.flarum.org/d/PUT_DISCUSS_SLUG_HERE)
-->
