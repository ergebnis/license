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

namespace Ergebnis\License\Type;

use Ergebnis\License;

final class None
{
    private License\Header $header;

    private function __construct(
        License\Template $headerTemplate,
        License\Period $period,
        License\Holder $holder,
        License\Url $url
    ) {
        $header = License\Header::createWithoutReferenceToLicenseFile(
            $headerTemplate,
            $period,
            $holder,
            $url,
        );

        $this->header = $header;
    }

    public static function text(
        License\Period $period,
        License\Holder $holder,
        License\Url $url
    ): self {
        return new self(
            License\Template::fromFile(__DIR__ . '/../../resource/header/without-reference-to-license-file.txt'),
            $period,
            $holder,
            $url,
        );
    }

    public function header(): string
    {
        return $this->header->toString();
    }
}
