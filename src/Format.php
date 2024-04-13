<?php

declare(strict_types=1);

namespace Intervention\Image;

enum Format
{
    case AVIF;
    case BMP;
    case GIF;
    case HEIC;
    case JPEG2000;
    case JPEG;
    case PNG;
    case TIFF;
    case WEBP;

    /**
     * Return the possible media (MIME) types for the current format
     *
     * @return array
     */
    public function mediaTypes(): array
    {
        return array_filter(MediaType::cases(), function ($mediaType) {
            return $mediaType->format() === $this;
        });
    }

    /**
     * Return the possible file extension for the current format
     *
     * @return array
     */
    public function fileExtensions(): array
    {
        return array_filter(FileExtension::cases(), function ($fileExtension) {
            return $fileExtension->format() === $this;
        });
    }
}
