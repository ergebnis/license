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

final class MIT
{
    private License\File $file;
    private License\Header $header;

    private function __construct(
        string $name,
        License\Template $fileTemplate,
        License\Template $headerTemplate,
        License\Period $period,
        License\Holder $holder,
        License\Url $url
    ) {
        $file = License\File::create(
            $name,
            $fileTemplate,
            $period,
            $holder,
        );

        $header = License\Header::createWithReferenceToLicenseFile(
            $headerTemplate,
            $period,
            $holder,
            $file,
            $url,
        );

        $this->file = $file;
        $this->header = $header;
    }

    public static function markdown(
        string $name,
        License\Period $period,
        License\Holder $holder,
        License\Url $url
    ): self {
        return new self(
            $name,
            License\Template::fromFile(__DIR__ . '/../../resource/license/MIT.md'),
            License\Template::fromFile(__DIR__ . '/../../resource/header/with-reference-to-license-file.txt'),
            $period,
            $holder,
            $url,
        );
    }

    public static function text(
        string $name,
        License\Period $period,
        License\Holder $holder,
        License\Url $url
    ): self {
        return new self(
            $name,
            License\Template::fromFile(__DIR__ . '/../../resource/license/MIT.txt'),
            License\Template::fromFile(__DIR__ . '/../../resource/header/with-reference-to-license-file.txt'),
            $period,
            $holder,
            $url,
        );
    }

    public function save(): void
    {
        $this->file->save();
    }

    public function header(): string
    {
        return $this->header->toString();
    }
}
