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

namespace Ergebnis\License\Type;

use Ergebnis\License\Header;
use Ergebnis\License\Holder;
use Ergebnis\License\Period;
use Ergebnis\License\Template;
use Ergebnis\License\Url;

final class None
{
    private Header $header;

    private function __construct(
        Template $headerTemplate,
        Period $period,
        Holder $holder,
        Url $url
    ) {
        $header = Header::createWithoutReferenceToLicenseFile(
            $headerTemplate,
            $period,
            $holder,
            $url,
        );

        $this->header = $header;
    }

    public static function text(
        Period $period,
        Holder $holder,
        Url $url
    ): self {
        return new self(
            Template::fromFile(__DIR__ . '/../../resource/header/without-reference-to-license-file.txt'),
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
