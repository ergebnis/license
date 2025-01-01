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

final class InvalidHolder extends \InvalidArgumentException implements Exception
{
    public static function blankOrEmpty(): self
    {
        return new self('Holder cannot be a blank or empty string.');
    }

    public static function multiline(): self
    {
        return new self('Holder cannot be a multiline string.');
    }
}
