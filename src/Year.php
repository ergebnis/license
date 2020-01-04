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

final class Year
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @throws Exception\InvalidYear
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match('/^\d{4}$/', $value)) {
            throw Exception\InvalidYear::fromValue($value);
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
