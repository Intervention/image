<?php

declare(strict_types=1);

namespace Intervention\Image;

use Error;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\HeicEncoder;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\EncoderInterface;

enum Format
{
    case AVIF;
    case BMP;
    case GIF;
    case HEIC;
    case JP2;
    case JPEG;
    case PNG;
    case TIFF;
    case WEBP;

    /**
     * Create format from given identifier
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @throws NotSupportedException
     * @return Format
     */
    public static function create(string|self|MediaType|FileExtension $identifier): self
    {
        if ($identifier instanceof self) {
            return $identifier;
        }

        if ($identifier instanceof MediaType) {
            return $identifier->format();
        }

        if ($identifier instanceof FileExtension) {
            return $identifier->format();
        }

        try {
            $format = MediaType::from(strtolower($identifier))->format();
        } catch (Error) {
            try {
                $format = FileExtension::from(strtolower($identifier))->format();
            } catch (Error) {
                throw new NotSupportedException('Unable to create format from "' . $identifier . '".');
            }
        }

        return $format;
    }

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
            self::JP2 => new Jpeg2000Encoder(...$options),
            self::JPEG => new JpegEncoder(...$options),
            self::PNG => new PngEncoder(...$options),
            self::TIFF => new TiffEncoder(...$options),
            self::WEBP => new WebpEncoder(...$options),
        };
    }
}
