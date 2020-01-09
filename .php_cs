<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

use Ergebnis\License;
use Ergebnis\PhpCsFixer\Config;

$holder = License\Holder::fromString('Andreas Möller');
$range = License\Range::since(
    License\Year::fromString('2020'),
    new \DateTimeZone('UTC')
);
$url = License\Url::fromString('https://github.com/ergebnis/license');

$header = <<<EOF
Copyright (c) {$range->toString()} {$holder->toString()}

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.

@see {$url->toString()}
EOF;

$config = Config\Factory::fromRuleSet(new Config\RuleSet\Php71($header));

$config->getFinder()
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->exclude([
        '.build',
        '.dependabot',
        '.github',
    ])
    ->name('.php_cs');

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php_cs.cache');

return $config;
