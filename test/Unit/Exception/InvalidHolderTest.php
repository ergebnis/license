<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit\Exception;

use Ergebnis\License\Exception;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Exception\InvalidHolder
 */
final class InvalidHolderTest extends Framework\TestCase
{
    public function testBlankOrEmptyReturnsInvalidHolder(): void
    {
        $exception = Exception\InvalidHolder::blankOrEmpty();

        self::assertSame('Holder cannot be a blank or empty string.', $exception->getMessage());
    }

    public function testMultilineReturnsInvalidHolder(): void
    {
        $exception = Exception\InvalidHolder::multiline();

        self::assertSame('Holder cannot be a multiline string.', $exception->getMessage());
    }
}
