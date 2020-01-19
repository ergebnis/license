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

$range = License\Range::since(
    License\Year::fromString('2020'),
    new \DateTimeZone('UTC')
);

$holder = License\Holder::fromString('Andreas Möller');

$file = License\File::create(
    __DIR__ . '/LICENSE',
    License\Template::fromFile(__DIR__ . '/resource/license/MIT.txt'),
    $range,
    $holder
);

$file->save();

$header = License\Header::create(
    License\Template::fromFile(__DIR__ . '/resource/header.txt'),
    $range,
    $holder,
    $file,
    License\Url::fromString('https://github.com/ergebnis/license')
);

$config = Config\Factory::fromRuleSet(new Config\RuleSet\Php71($header->toString()));

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
