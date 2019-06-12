# Kirby Page Lock
[![Release](https://img.shields.io/github/release/pedroborges/kirby-page-lock.svg)](https://github.com/pedroborges/kirby-page-lock/releases) [![Issues](https://img.shields.io/github/issues/pedroborges/kirby-page-lock.svg)](https://github.com/pedroborges/kirby-page-lock/issues)

Page Lock helps prevent content loss by placing a temporary lock on pages being edited on the Kirby's Panel. It supports multilingual sites out of the box by locking each page language individually.

[![Preview of the Page Lock Plugin for Kirby CMS](https://raw.githubusercontent.com/pedroborges/kirby-page-lock/master/preview.gif)](https://github.com/pedroborges/kirby-page-lock)

A helpful message with editor's name is displayed on locked pages. Additionally, the plugin protects the content by:

- Removing the save button
- Disabling keyboard shortcuts
- Making input fields read-only
- Disabling drag-and-drop sorting
- Removing action buttons
- Removing the files widget

> Unfortunately Page Lock can't 'disable' all third-party custom fields. Be aware some these custom fields may still be edited even when the page is locked.

## Requirements
- Kirby 2.3.2+
- PHP 5.4+

## Installation

### Download
[Download the files](https://github.com/pedroborges/kirby-page-lock/archive/master.zip) and place them inside `site/plugins/page-lock`.

### Kirby CLI
Kirby's [command line interface](https://github.com/getkirby/cli) is the easiest way to install the Page Lock plugin:

    $ kirby plugin:install pedroborges/kirby-page-lock

Updating couldn't be any easier, simply run:

    $ kirby plugin:update pedroborges/kirby-page-lock

### Git Submodule
You can add the Page Lock as a Git submodule.

<details>
    <summary><strong>Show Git Submodule instructions</strong> 👁</summary><p>

    $ cd your/project/root
    $ git submodule add https://github.com/pedroborges/kirby-page-lock.git site/plugins/page-lock
    $ git submodule update --init --recursive
    $ git commit -am "Add Page Lock plugin"

Updating is as easy as running a few commands.

    $ cd your/project/root
    $ git submodule foreach git checkout master
    $ git submodule foreach git pull
    $ git commit -am "Update Page Lock plugin"
    $ git submodule update --init --recursive

</p></details>

## Basic Usage
Due to a limitation in Kirby's Panel, this plugin makes use of [Form Fields](https://getkirby.com/docs/panel/blueprints/form-fields) in order to load a script on each page while it's being edited.

To make the installation process smoother, Page Lock registers a clone of the `title` field. Since most pages define a `title` field on the [blueprint](https://getkirby.com/docs/panel/blueprints), you may not even need to do any configuration on your own.

```yaml
title:
  label: Title
  type: title
```

When using the `title` field you can disable Page Lock on a single blueprint by setting the `lock` field option to `false`:

```yaml
title:
  label: Title
  type: title
  lock: false
```

> If you need to globally disable the `title` field that ships with Page Lock, set the `page-lock.title` option to `false`.

In case you don't need a `title` field on your blueprint, you can use the `lock` [global field](https://getkirby.com/docs/panel/blueprints/global-field-definitions) to enable the plugin on it.

```yaml
lock: lock
```

The above is a shorthand for:

```yaml
lock:
  type: lock
```

## Option
The following options can be set in your `/site/config/config.php`:

### `page-lock.interval`
Time in seconds that opened pages notify the plugin they are still being edited. This affects the time the page is unlock after the editor leaves it. Defaults to `10` seconds.

```php
c::set('page-lock.interval', 10);
```

### `page-lock.title`
Enables/disables Page Lock clone `title` field. Defaults to `true`.

```php
c::set('page-lock.title', true);
```

## FAQ
### How can I test Page Lock on my site?
First make sure there at least two registered users. In your browser, login into Kirby's Panel with `user1` then navigate to a page that either has a `title` or `lock` field defined in the corresponding blueprint. Login with `user2` using a private window or another browser then open the same page. You should see a red box saying: _user1 is editing this page._

## Change Log
All notable changes to this project will be documented at: <https://github.com/pedroborges/kirby-page-lock/blob/master/CHANGELOG.md>

## License
Page Lock plugin is open-sourced software licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php).

Copyright © 2017 Pedro Borges <oi@pedroborg.es>
