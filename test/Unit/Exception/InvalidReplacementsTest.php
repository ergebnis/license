<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit\Exception;

use Ergebnis\License\Exception\InvalidReplacements;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Exception\InvalidReplacements
 *
 * @uses \Ergebnis\License\Year
 */
final class InvalidReplacementsTest extends Framework\TestCase
{
    public function testKeysReturnsInvalidReplacements(): void
    {
        $exception = InvalidReplacements::keys();

        self::assertSame('Keys need to be strings.', $exception->getMessage());
    }

    public function testValuesReturnsInvalidReplacements(): void
    {
        $exception = InvalidReplacements::values();

        self::assertSame('Values need to be strings.', $exception->getMessage());
    }
}
