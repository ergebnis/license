<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Exception;

final class InvalidUrl extends \InvalidArgumentException implements Exception
{
    public static function fromValue(string $value): self
    {
        return new self(\sprintf(
            'Value "%s" is not a valid URL.',
            $value,
        ));
    }
}
