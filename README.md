# license

[![Integrate](https://github.com/ergebnis/license/workflows/Integrate/badge.svg)](https://github.com/ergebnis/license/actions)
[![Merge](https://github.com/ergebnis/license/workflows/Merge/badge.svg)](https://github.com/ergebnis/license/actions)
[![Prune](https://github.com/ergebnis/license/workflows/Prune/badge.svg)](https://github.com/ergebnis/license/actions)
[![Release](https://github.com/ergebnis/license/workflows/Release/badge.svg)](https://github.com/ergebnis/license/actions)
[![Renew](https://github.com/ergebnis/license/workflows/Renew/badge.svg)](https://github.com/ergebnis/license/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/license/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/license)
[![Type Coverage](https://shepherd.dev/github/ergebnis/license/coverage.svg)](https://shepherd.dev/github/ergebnis/license)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/license/v/stable)](https://packagist.org/packages/ergebnis/license)
[![Total Downloads](https://poser.pugx.org/ergebnis/license/downloads)](https://packagist.org/packages/ergebnis/license)

Provides an abstraction of an open-source license.

## Installation

Run

```sh
$ composer require --dev ergebnis/license
```

## Usage

Sometimes open source maintainers complain about the burden of managing an open-source project. Sometimes they argue that contributors opening pull requests to update license years unnecessarily increase their workload.

Of course, all of this can be automated, can't it?

### Configuration for `friendsofphp/php-cs-fixer`

With [`friendsofphp/php-cs-fixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer) you can use the configuration file `.php_cs` to

* save the license to a file, e.g. `LICENSE` or `LICENSE.md`
* specify a file-level header using the `header_comment` fixer that will be replaced in PHP files

Here's an example of a `.php_cs` file:

```php
<?php

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

:bulb: Also take a look at [`.php_cs`](.php_cs) of this project.

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
        run: "vendor/bin/php-cs-fixer fix --config=.php_cs --diff --diff-format=udiff --dry-run --verbose"

      - name: "Open pull request updating license year"
        uses: "gr2m/create-or-update-pull-request-action@v1.2.9"
        with:
          author: "Andreas Möller <am@localheinz.com>"
          branch: "feature/license-year"
          body: |
            This PR

            * [x] updates the license year
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

             * [x] updates the license year
           commit-message: "Enhancement: Update license year"
           path: "."
           title: "Enhancement: Update license year"
         env:
-          GITHUB_TOKEN: "${{ secrets.GITHUB_TOKEN }}"
+          GITHUB_TOKEN: "${{ secrets.ERGEBNIS_BOT_TOKEN }}"
```
## Types

The following license types are currently available:

* [`Ergebnis\License\Type\MIT`](src/Type/MIT.php)

:bulb: Need a different license type? Feel free to open a pull request!

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Curious what I am building?

:mailbox_with_mail: [Subscribe to my list](https://localheinz.com/projects/), and I will occasionally send you an email to let you know what I am working on.
