# Changelog

## [unreleased]

### Added

- Add a Flarum console command to download and register themes from
  highlight.js releases.

### Fixed

- Support Flarum installations that have a separated assets storage by using
  the filesystem interface as [described in the docs][extend/assets].

[extend/assets]: https://docs.flarum.org/extend/assets/

## [v1.2.1] - 2023-05-27

### Fixed

- Fix default highlight theme values.

## [v1.2.0] - 2023-05-27

### Added

- Add settings to change code-block's background and text colors. This allows
  to keep higlight.js themes unchanged.
- Allow to choose the highlight theme for dark and light Flarum styles.
- Add `base16/monokai` theme.

## [v1.1.0] - 2023-05-17

### Added

- Add support links in the metadata of the package.
- Test more PHP versions.
- Setup PHPStan for static code analysis.
- Set 7.3 as the minimum required PHP version.

### Fixed

- Fix a crash when start or end tag are omited.

## [v1.0.0] - 2023-04-28

First stable release.

[unreleased]: https://github.com/club-1/flarum-ext-server-side-highlight/compare/v1.2.1...HEAD
[v1.2.1]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.2.1
[v1.2.0]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.2.0
[v1.1.0]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.1.0
[v1.0.0]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.0.0
