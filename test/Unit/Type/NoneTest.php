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

namespace Ergebnis\License\Test\Unit\Type;

use Ergebnis\License\Header;
use Ergebnis\License\Holder;
use Ergebnis\License\Range;
use Ergebnis\License\Template;
use Ergebnis\License\Test;
use Ergebnis\License\Type;
use Ergebnis\License\Url;
use Ergebnis\License\Year;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Type\None::class)]
#[Framework\Attributes\UsesClass(Header::class)]
#[Framework\Attributes\UsesClass(Holder::class)]
#[Framework\Attributes\UsesClass(Range::class)]
#[Framework\Attributes\UsesClass(Template::class)]
#[Framework\Attributes\UsesClass(Url::class)]
#[Framework\Attributes\UsesClass(Year::class)]
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
