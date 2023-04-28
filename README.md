# Server Side Highlight

![License](https://img.shields.io/badge/license-AGPL--3.0--or--later-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/club-1/flarum-ext-server-side-highlight.svg)](https://packagist.org/packages/club-1/flarum-ext-server-side-highlight) [![Total Downloads](https://img.shields.io/packagist/dt/club-1/flarum-ext-server-side-highlight.svg)](https://packagist.org/packages/club-1/flarum-ext-server-side-highlight) [![Coverage](https://img.shields.io/codecov/c/gh/club-1/flarum-ext-server-side-highlight?token=UPYYHOTAWD)](https://codecov.io/gh/club-1/flarum-ext-server-side-highlight) [![Build Status](https://img.shields.io/github/actions/workflow/status/club-1/flarum-ext-server-side-highlight/check.yml?branch=main)](https://github.com/club-1/flarum-ext-server-side-highlight/actions/workflows/check.yml)

A [Flarum](http://flarum.org) extension. Server-side code highlighting with cached results.

A hash for each code block of the posts is computed while parsing them, then on render the code highlighting is done in the backend once and stored in the cache for future renderings.

I provides the following advantages:

1. The highlighting is done on the server so it is not up to the clients to do it.
2. The server uses the cache to save highlighted blocks to only do the processing once.
3. It works without any JS so even on the worst web browser the highlight will be there and there is no delay before the highlighting is applied.
4. The theme adapts itself to the "dark mode" setting of Flarum.
5. It works even with BBCode extension disabled.

Client-side highlighting is still used, but only for the post preview.

To do:

- [ ] Add a way to configure the theme.

## Installation

Install with composer:

```sh
composer require club-1/flarum-ext-server-side-highlight:"*"
```

### Recommendation

This extension alone does not apply the formatting changes to previously posted comments. I you want to reparse all the comments posts of the database it is recommended to install and enable the [`club-1/flarum-ext-chore-commands`](https://github.com/club-1/flarum-ext-chore-commands) extension and use its `chore:reparse` command.

## Updating

```sh
composer update club-1/flarum-ext-server-side-highlight:"*"
php flarum cache:clear
php flarum assets:publish
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
