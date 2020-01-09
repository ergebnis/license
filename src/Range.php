<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License;

final class Range implements Period
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param Year $start
     * @param Year $end
     *
     * @throws Exception\InvalidRange
     *
     * @return Period
     */
    public static function including(Year $start, Year $end): Period
    {
        if ($start->greaterThan($end)) {
            throw Exception\InvalidRange::startYearGreaterThanEndYear(
                $start,
                $end
            );
        }

        if ($start->equals($end)) {
            return $start;
        }

        return new self(\sprintf(
            '%s-%s',
            $start->toString(),
            $end->toString()
        ));
    }

    /**
     * @param Year          $start
     * @param \DateTimeZone $timeZone
     *
     * @throws Exception\InvalidRange
     *
     * @return Period
     */
    public static function since(Year $start, \DateTimeZone $timeZone): Period
    {
        $now = new \DateTimeImmutable(
            'now',
            $timeZone
        );

        $current = Year::fromString($now->format('Y'));

        if ($start->greaterThan($current)) {
            throw Exception\InvalidRange::startYearGreaterThanCurrentYear(
                $start,
                $current
            );
        }

        if ($start->equals($current)) {
            return $start;
        }

        return new self(\sprintf(
            '%s-%s',
            $start->toString(),
            $current->toString()
        ));
    }

    public function toString(): string
    {
        return $this->value;
    }
}