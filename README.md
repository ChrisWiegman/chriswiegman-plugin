# ChrisWiegman.com

Basic functionality for ChrisWiegman.com

## Setup and Usage of the Development Environment

A fully featured development environment is included using PHP 8.4. Scripts to run commands including setup and more use _make_ as a task runner. See the instructions below for getting started.

Before starting your workstation will need the following:

- [Docker](https://www.docker.com/)

1. Clone the repository

`git clone https://github.com/ChrisWiegman/chriswiegman-plugin.git`

2. Star The Development site

```bash
cd chriswiegman-plugin
make start
```

When finished, just open the site in your browser and you're ready to go.

WordPress Credentials:

**Admin User:** _admin_

**Admin Password:** _password_

## Using Xdebug

Xdebug is started automatically using the `make start` command. Simply run the config provided in VSCode, set your break points and you're good to go.

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
