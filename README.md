# ChrisWiegman.com

Basic functionality for ChrisWiegman.com

## Setup and Usage of the Development Environment

A fully featured development environment is included using PHP 8.4. Scripts to run commands including setup and more use _make_ as a task runner. See the instructions below for getting started.

Before starting your workstation will need the following:

- [Docker](https://www.docker.com/)
- [Kana](https://github.com/ChrisWiegman/kana)

1. Clone the repository

`git clone https://github.com/ChrisWiegman/chriswiegman-plugin.git`

2. Start Kana

```bash
cd chriswiegman-plugin
make start
```

When finished, Kana will open a development version of your site in the browser and you're ready to go

WordPress Credentials:

**URL:** _http://chriswiegman-plugin.sites.kana.sh/wp-admin_

**Admin User:** _admin_

**Admin Password:** _password_

## Using Xdebug

Xdebug 3 released a [number of changes](https://xdebug.org/docs/upgrade_guide) that affect the way Xdebug works. Namely, it no longer listens on every request and requires a "trigger" to enable the connection. Use one of the following plugins to enable the trigger on your machine:

- [Xdebug Helper for Firefox](https://addons.mozilla.org/en-GB/firefox/addon/xdebug-helper-for-firefox/) ([source](https://github.com/BrianGilbert/xdebug-helper-for-firefox)).
- [Xdebug Helper for Chrome](https://chrome.google.com/extensions/detail/eadndfjplgieldjbigjakmdgkmoaaaoc) ([source](https://github.com/mac-cain13/xdebug-helper-for-chrome)).
- [XDebugToggle for Safari](https://apps.apple.com/app/safari-xdebug-toggle/id1437227804?mt=12) ([source](https://github.com/kampfq/SafariXDebugToggle)).

To enable Xdebug using the built-in Kana configuration use the following:

```bash
kana xdebug on
```

## Testing

The project contains 3 types of testing:

1. Linting with the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)

```bash
make test-lint
```

2. Unit Tests

```bash
make test-php-unit
```

3. E3E Tests with Playwright
