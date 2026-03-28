<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Format;
use Intervention\Image\MediaType;

interface OriginInterface
{
    /**
     * Return media type of origin.
     */
    public function mediaType(): string;

    /**
     * Set media type of current instance.
     */
    public function setMediaType(string|MediaType $type): self;

    /**
     * Return file path of origin.
     */
    public function filePath(): ?string;

    /**
     * Set file path for origin.
     */
    public function setFilePath(string $path): self;

    /**
     * Return file extension if origin was created from file path.
     */
    public function fileExtension(): ?string;

    /**
     * Return format of the origin image.
     */
    public function format(): Format;
}
