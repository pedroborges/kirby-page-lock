# Change Log
All notable changes to this project will be documented in this file.

## [1.0.0-beta.1] - 2017-03-13
Huge thanks to [confday](https://confdays.com) (by @vauvarin) for sponsoring this release!

### Added
- Displays helpful lock message with editor's name
- `title` clone field
- `lock` global field
- If your site has login-protected editable pages on the front-end, now Page Lock exposes it's API to allow you to lock them as well:
    - `pageLock()` helper function
    - `$page->pageLock()` page method
    - `$page->isLocked()` page method
    - `$page->isNotLocked()` page method
    - `page-lock` snippet

### Changed
- Github repository has been renamed from `pedroborges/kirby-pagelock` to `pedroborges/kirby-page-lock`
- `pagelock` field is now called `lock`
- `fields.pagelock.time` option is now called `page-lock.interval`
- Default `page-lock.interval` is now 10 seconds
- Kirby routes are used instead of custom field routes
- Instead of creating `.lock` files on each page's root, now the plugin keeps track of locked pages on a single JSON file

## [0.1.0] - 2016-04-13
### Initial release
