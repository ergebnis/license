<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Exception;

use Ergebnis\License\Year;

final class InvalidRange extends \InvalidArgumentException implements Exception
{
    public static function startYearGreaterThanEndYear(
        Year $start,
        Year $end,
    ): self {
        return new self(\sprintf(
            'Start year "%s" can not be greater than end year "%s".',
            $start->toString(),
            $end->toString(),
        ));
    }

    public static function startYearGreaterThanCurrentYear(
        Year $start,
        Year $current,
    ): self {
        return new self(\sprintf(
            'Start year "%s" can not be greater than current year "%s".',
            $start->toString(),
            $current->toString(),
        ));
    }
}
