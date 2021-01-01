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

use Ergebnis\License\Exception\InvalidFile;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\License\Exception\InvalidFile
 *
 * @uses \Ergebnis\License\Year
 */
final class InvalidFileTest extends Framework\TestCase
{
    use Helper;

    public function testEmptyFileNameReturnsInvalidFile(): void
    {
        $exception = InvalidFile::emptyFileName();

        self::assertSame('File name can not be empty.', $exception->getMessage());
    }

    public function testDoesNotExistReturnsInvalidFile(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s.%s',
            $faker->slug,
            $faker->fileExtension
        );

        $exception = InvalidFile::doesNotExist($name);

        $expected = \sprintf(
            'A file with name "%s" does not exist.',
            $name
        );

        self::assertSame($expected, $exception->getMessage());
    }

    public function testCanNotBeReadReturnsInvalidFile(): void
    {
        $faker = self::faker();

        $name = \sprintf(
            '%s.%s',
            $faker->slug,
            $faker->fileExtension
        );

        $exception = InvalidFile::canNotBeRead($name);

        $expected = \sprintf(
            'File with name "%s" can not be read.',
            $name
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
