<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Test\Unit\Type;

use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Test;
use Ergebnis\License\Type;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\License\Type\None
 *
 * @uses \Ergebnis\License\File
 * @uses \Ergebnis\License\Header
 * @uses \Ergebnis\License\Holder
 * @uses \Ergebnis\License\Range
 * @uses \Ergebnis\License\Template
 * @uses \Ergebnis\License\Url
 * @uses \Ergebnis\License\Year
 */
final class NoneTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testHeaderReturnsHeaderForTextLicense(): void
    {
        $faker = self::faker();

        $range = Range::since(
            Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = Holder::fromString($faker->name());
        $url = Url::fromString($faker->url());

        $license = Type\None::text(
            $range,
            $holder,
            $url,
        );

        $expected = <<<TXT
Copyright (c) {$range->toString()} {$holder->toString()}

@see {$url->toString()}

TXT;

        self::assertSame($expected, $license->header());
    }
}
