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

namespace Ergebnis\License\Test\Unit\Exception;

use Ergebnis\License;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\License\Exception\InvalidFile
 *
 * @uses \Ergebnis\License\Year
 */
final class InvalidFileTest extends Framework\TestCase
{
    use License\Test\Util\Helper;

    public function testEmptyFileNameReturnsInvalidFile(): void
    {
        $exception = License\Exception\InvalidFile::emptyFileName();

        self::assertSame('File name can not be empty.', $exception->getMessage());
    }

    public function testDoesNotExistReturnsInvalidFile(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s.%s',
            $faker->slug(),
            $faker->fileExtension(),
        );

        $exception = License\Exception\InvalidFile::doesNotExist($name);

        $expected = \sprintf(
            'A file with name "%s" does not exist.',
            $name,
        );

        self::assertSame($expected, $exception->getMessage());
    }

    public function testCanNotBeReadReturnsInvalidFile(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s.%s',
            $faker->slug(),
            $faker->fileExtension(),
        );

        $exception = License\Exception\InvalidFile::canNotBeRead($name);

        $expected = \sprintf(
            'File with name "%s" can not be read.',
            $name,
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
