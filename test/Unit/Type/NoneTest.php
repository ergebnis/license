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

namespace Ergebnis\License\Test\Unit\Type;

use Ergebnis\License;
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
    use License\Test\Util\Helper;

    public function testHeaderReturnsHeaderForTextLicense(): void
    {
        $faker = self::faker();

        $range = License\Range::since(
            License\Year::fromString($faker->year()),
            new \DateTimeZone($faker->timezone()),
        );
        $holder = License\Holder::fromString($faker->name());
        $url = License\Url::fromString($faker->url());

        $license = License\Type\None::text(
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
