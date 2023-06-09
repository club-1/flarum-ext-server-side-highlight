# Changelog

## [unreleased]

### Added

- Allow copy/pasting color code by using `color-preview` instead of `color`
  setting types.
- Add code highlight preview for current settings in admin page.
- Debounce code highlight in preview, using [`data-s9e-livepreview-hash`][livepreview-hash].
  This way the highlighting is not computed again if the code did not change,
  which should improve performances.

[livepreview-hash]: https://s9etextformatter.readthedocs.io/JavaScript/Live_preview_attributes/#data-s9e-livepreview-hash

## [v1.3.0] - 2023-06-03

### Added

- Add a Flarum console command to download and register themes from
  highlight.js releases.
- Add integration with [`fof/nightmode >= 1.5.2`][fof/nightmode] by switching
  the highlight theme accordingly.

### Fixed

- Support Flarum installations that have a separated assets storage by using
  the filesystem interface as [described in the docs][extend/assets].

[fof/nightmode]: https://github.com/FriendsOfFlarum/nightmode
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

[unreleased]: https://github.com/club-1/flarum-ext-server-side-highlight/compare/v1.3.0...HEAD
[v1.3.0]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.3.0
[v1.2.1]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.2.1
[v1.2.0]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.2.0
[v1.1.0]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.1.0
[v1.0.0]: https://github.com/club-1/flarum-ext-server-side-highlight/releases/tag/v1.0.0
