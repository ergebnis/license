<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/license
 */

namespace Ergebnis\License\Exception;

final class InvalidReplacements extends \InvalidArgumentException implements Exception
{
    public static function keys(): self
    {
        return new self('Keys need to be strings.');
    }

    public static function values(): self
    {
        return new self('Values need to be strings.');
    }
}
