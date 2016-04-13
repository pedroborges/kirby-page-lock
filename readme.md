# Page Lock Plugin for Kirby CMS
[![Release](https://img.shields.io/github/release/pedroborges/kirby-pagelock.svg)](https://github.com/pedroborges/kirby-pagelock/releases) [![Issues](https://img.shields.io/github/issues/pedroborges/kirby-pagelock.svg)](https://github.com/pedroborges/kirby-pagelock/issues) [![Buy a Non-Commercial License](https://img.shields.io/badge/buy-noncommercial_license-green.svg)](https://www.paypal.com/cgi-bin/webscr?&amp;cmd=_xclick&amp;business=oi@pedroborg.es&amp;currency_code=USD&amp;amount=14&amp;item_name=Page Lock Plugin for Kirby CMS Non-Commercial License) [![Buy a Commercial License](https://img.shields.io/badge/buy-commercial_license-green.svg)](https://www.paypal.com/cgi-bin/webscr?&amp;cmd=_xclick&amp;business=oi@pedroborg.es&amp;currency_code=USD&amp;amount=39&amp;item_name=Page Lock Plugin for Kirby CMS Commercial License)

This robust plugin for [Kirby CMS](https://getkirby.com) locks a page in the panel as soon as you open it to prevent other users from editing it. Content loss no more!

It's ready for multilingual sites too! Each page file is locked individually. No extra configuration needed.

[![Preview of the Page Lock Plugin for Kirby CMS](https://raw.githubusercontent.com/pedroborges/kirby-pagelock/master/preview.gif)](https://github.com/pedroborges/kirby-pagelock)

## Installation

### Download
[Download the files](https://github.com/pedroborges/kirby-pagelock/archive/master.zip) and put them in a folder named `pagelock`, inside `site/fields`. Create the `fields` folder if it doesn't exist.

### Kirby CLI
Kirby's [command line interface](https://github.com/getkirby/cli) makes installing Page Lock Plugin a breeze:

    $ kirby plugin:install pedroborges/kirby-pagelock

Updating couldn't be any easier, simply run:

    $ kirby plugin:update pedroborges/kirby-pagelock

### Git Submodule
If Git is part of your workflow, you can add Page Lock Plugin as a Git Submodule.

    $ cd your/project
    $ git submodule add https://github.com/pedroborges/kirby-pagelock.git site/fields/pagelock

Updating is as easy as running a few commands.

    $ cd your/project
    $ git submodule foreach git checkout master
    $ git submodule foreach git pull
    $ git commit -a -m "Update submodules"
    $ git submodule update --init --recursive

## How To
Add the following field to **each** [blueprint](https://getkirby.com/docs/panel/blueprints) you want to enable the lock feature:

    fields:
      pagelock:
        type: pagelock

That's it! Multilingual support works out of the box.

> When using Git, make sure to add the following rule to your `.gitignore` file: `.lock*`

## Option

The following option can be set in your `/site/config/config.php`:

### fields.pagelock.time

    // Default: 8 seconds
    c::set('fields.pagelock.time', 8);

As soon as you open a page, Page Lock Plugin verifies if it's locked or not. In case it isn't, the page is locked and the plugin keeps pinging the server to inform you are not done editing yet. If it's been locked, then we want to know when it unlocks. This setting changes the frequency those pings happen.

## Issues and Feedback
If you have a Github account, please report issues directly on Github:

<https://github.com/pedroborges/kirby-pagelock/issues>

Otherwise you can send me an email: oi@pedroborg.es

## Requirements
- Kirby 2.2.3+
- PHP 5.4+

## Buy a License
Please keep in mind that I develop this plugin with passion in my spare time. Few free to try it on your local machine or on a private test server. Once your project goes live, get a license and support further development of this and future plugins for Kirby CMS.

- For Non-Commercial Use: $14/website <a href="https://www.paypal.com/cgi-bin/webscr?&amp;cmd=_xclick&amp;business=oi@pedroborg.es&amp;currency_code=USD&amp;amount=14&amp;item_name=Page Lock Plugin for Kirby CMS Non-Commercial License" target="_blank"><img src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Buy Now"></a>
- For Commercial Use: $39/website <a href="https://www.paypal.com/cgi-bin/webscr?&amp;cmd=_xclick&amp;business=oi@pedroborg.es&amp;currency_code=USD&amp;amount=39&amp;item_name=Page Lock Plugin for Kirby CMS Commercial License" target="_blank"><img src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Buy Now"></a>

You'll get this awesome feature and **all** future releases!

A Page Lock Plugin license is valid for a single domain. You can find the license agreement here: <https://github.com/pedroborges/kirby-pagelock/blob/master/license.md>

## Copyright
@ 2016 Pedro Borges <oi@pedroborg.es>
