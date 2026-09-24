<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

use Ergebnis\License;
use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Type;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use Ergebnis\PhpCsFixer;
use PhpCsFixer\Finder;

$license = Type\MIT::markdown(
    __DIR__ . '/LICENSE.md',
    Range::since(
        Year::fromString('2020'),
        new DateTimeZone('UTC'),
    ),
    Holder::fromString('Andreas Möller'),
    Url::fromString('https://github.com/ergebnis/license'),
);

$license->save();

$ruleSet = PhpCsFixer\Config\RuleSet\Php74::create()->withHeader($license->header());

$finder = Finder::create()
    ->exclude([
        '.build/',
        '.github/',
        '.note/',
    ])
    ->ignoreDotFiles(false)
    ->in(__DIR__);

$config = PhpCsFixer\Config\Factory::fromRuleSet($ruleSet);

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php-cs-fixer.cache');
$config->setFinder($finder);

return $config;
