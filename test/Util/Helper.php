<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Util;

use Faker\Factory;
use Faker\Generator;

trait Helper
{
    final protected static function faker(string $locale = 'en_US'): Generator
    {
        /**
         * @var array<string, Generator> $fakers
         */
        static $fakers = [];

        if (!\array_key_exists($locale, $fakers)) {
            $faker = Factory::create($locale);

            $faker->seed(9001);

            $fakers[$locale] = $faker;
        }

        return $fakers[$locale];
    }

    private static function temporaryDirectory(): string
    {
        return __DIR__ . '/../../.build/test';
    }
}
