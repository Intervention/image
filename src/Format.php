<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\HeicEncoder;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Interfaces\EncoderInterface;

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

    /**
     * Create an encoder instance that matches the format
     *
     * @param array $options
     * @return EncoderInterface
     */
    public function encoder(mixed ...$options): EncoderInterface
    {
        return match ($this) {
            self::AVIF => new AvifEncoder(...$options),
            self::BMP => new BmpEncoder(...$options),
            self::GIF => new GifEncoder(...$options),
            self::HEIC => new HeicEncoder(...$options),
            self::JPEG2000 => new Jpeg2000Encoder(...$options),
            self::JPEG => new JpegEncoder(...$options),
            self::PNG => new PngEncoder(...$options),
            self::TIFF => new TiffEncoder(...$options),
            self::WEBP => new WebpEncoder(...$options),
        };
    }
}
