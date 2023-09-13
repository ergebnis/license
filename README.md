# license

[![Integrate](https://github.com/ergebnis/license/workflows/Integrate/badge.svg)](https://github.com/ergebnis/license/actions)
[![Merge](https://github.com/ergebnis/license/workflows/Merge/badge.svg)](https://github.com/ergebnis/license/actions)
[![Release](https://github.com/ergebnis/license/workflows/Release/badge.svg)](https://github.com/ergebnis/license/actions)
[![Renew](https://github.com/ergebnis/license/workflows/Renew/badge.svg)](https://github.com/ergebnis/license/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/license/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/license)
[![Type Coverage](https://shepherd.dev/github/ergebnis/license/coverage.svg)](https://shepherd.dev/github/ergebnis/license)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/license/v/stable)](https://packagist.org/packages/ergebnis/license)
[![Total Downloads](https://poser.pugx.org/ergebnis/license/downloads)](https://packagist.org/packages/ergebnis/license)
[![Monthly Downloads](http://poser.pugx.org/ergebnis/license/d/monthly)](https://packagist.org/packages/ergebnis/license)

This package provides an abstraction of an open-source license.

## Installation

Run

```sh
composer require --dev ergebnis/license
```

## Usage

Sometimes open source maintainers complain about the burden of managing an open-source project. Sometimes they argue that contributors opening pull requests to update license years unnecessarily increase their workload.

Of course, all of this can be automated, can't it?

### Configuration for `friendsofphp/php-cs-fixer`

With [`friendsofphp/php-cs-fixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer) you can use the configuration file `.php-cs-fixer.php` to

- save the license to a file, e.g. `LICENSE` or `LICENSE.md`
- specify a file-level header using the `header_comment` fixer that will be replaced in PHP files

Here's an example of a `.php-cs-fixer.php` file for an open-source project using the [`MIT`](src/Type/MIT.php) license type:

```php
<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

use Ergebnis\License;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$license = License\Type\MIT::text(
    __DIR__ . '/LICENSE',
    License\Range::since(
        License\Year::fromString('2020'),
        new \DateTimeZone('UTC')
    ),
    License\Holder::fromString('Andreas Möller'),
    License\Url::fromString('https://github.com/ergebnis/license')
);

$license->save();

$finder = Finder::create()->in(__DIR__);

return Config::create()
    ->setFinder($finder)
    ->setRules([
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $license->header(),
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],
    ]);
```

:bulb: Also take a look at [`.php-cs-fixer.php`](.php-cs-fixer.php) of this project.

Here's an example of a `.php-cs-fixer.php` file for a closed-source project using the [`None`](src/Type/None.php) license type:


```php
<?php

declare(strict_types=1);

/**
 * Copyright (c) 2011-2019 Andreas Möller
 *
 * @see https://github.com/localheinz/localheinz.com
 */

use Ergebnis\License;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$license = License\Type\None::text(
    License\Range::since(
        License\Year::fromString('2020'),
        new \DateTimeZone('UTC')
    ),
    License\Holder::fromString('Andreas Möller'),
    License\Url::fromString('https://github.com/localheinz/localheinz.com')
);

$finder = Finder::create()->in(__DIR__);

return Config::create()
    ->setFinder($finder)
    ->setRules([
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $license->header(),
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],
    ]);
```

### GitHub Actions

When using [GitHub Actions](https://github.com/features/actions), you can set up a scheduled workflow that opens a pull request to the license year automatically on January 1st:

```yaml
name: "License"

on:
  schedule:
    - cron: "1 0 1 1 *"

jobs:
  license:
    name: "License"

    runs-on: "ubuntu-latest"

    steps:
      - name: "Checkout"
        uses: "actions/checkout@v2.0.0"

      - name: "Install dependencies with composer"
        run: "composer install --no-interaction --no-progress --no-suggest"

      - name: "Run friendsofphp/php-cs-fixer"
        run: "vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --diff --dry-run --verbose"

      - name: "Open pull request updating license year"
        uses: "gr2m/create-or-update-pull-request-action@v1.2.9"
        with:
          author: "Andreas Möller <am@localheinz.com>"
          branch: "feature/license-year"
          body: |
            This PR

            - [x] updates the license year
          commit-message: "Enhancement: Update license year"
          path: "."
          title: "Enhancement: Update license year"
        env:
          GITHUB_TOKEN: "${{ secrets.GITHUB_TOKEN }}"
```

:bulb: See [`crontab.guru`](https://crontab.guru) if you need help scheduling the workflow.

Note that pull requests opened or commits pushed by GitHub Actions will not trigger a build. As an alternative, you can set up a bot user:

```diff
       - name: "Open pull request updating license year"
         uses: "gr2m/create-or-update-pull-request-action@v1.2.9"
         with:
-          author: "Andreas Möller <am@localheinz.com>"
+          author: "ergebnis-bot <bot@ergebn.is>"
           branch: "feature/license-year"
           body: |
             This PR

             - [x] updates the license year
           commit-message: "Enhancement: Update license year"
           path: "."
           title: "Enhancement: Update license year"
         env:
-          GITHUB_TOKEN: "${{ secrets.GITHUB_TOKEN }}"
+          GITHUB_TOKEN: "${{ secrets.ERGEBNIS_BOT_TOKEN }}"
```
## Types

The following license types are currently available:

- [`Ergebnis\License\Type\MIT`](src/Type/MIT.php)
- [`Ergebnis\License\Type\None`](src/Type/None.php)

:bulb: Need a different license type? Feel free to open a pull request!

## Changelog

The maintainers of this package record notable changes to this project in a [changelog](CHANGELOG.md).

## Contributing

The maintainers of this package suggest following the [contribution guide](.github/CONTRIBUTING.md).

## Code of Conduct

The maintainers of this package ask contributors to follow the [code of conduct](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## General Support Policy

The maintainers of this package provide limited support.

You can support the maintenance of this package by [sponsoring @localheinz](https://github.com/sponsors/localheinz) or [requesting an invoice for services related to this package](mailto:am@localheinz.com?subject=ergebnis/license:%20Requesting%20invoice%20for%20services).

## PHP Version Support Policy

This package supports PHP versions with [active support](https://www.php.net/supported-versions.php).

The maintainers of this package add support for a PHP version following its initial release and drop support for a PHP version when it has reached its end of active support.

## Security Policy

This package has a [security policy](.github/SECURITY.md).

## License

This package uses the [MIT license](LICENSE.md).

## Social

Follow [@localheinz](https://twitter.com/intent/follow?screen_name=localheinz) and [@ergebnis](https://twitter.com/intent/follow?screen_name=ergebnis) on Twitter.
