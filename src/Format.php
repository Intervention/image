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
use ReflectionClass;
use ReflectionParameter;

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
     * Try to create format from given identifier and return null on failure
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @return Format|null
     */
    public static function tryCreate(string|self|MediaType|FileExtension $identifier): ?self
    {
        try {
            return self::create($identifier);
        } catch (NotSupportedException) {
            return null;
        }
    }

    /**
     * Return the possible media (MIME) types for the current format
     *
     * @return array<MediaType>
     */
    public function mediaTypes(): array
    {
        return array_filter(
            MediaType::cases(),
            fn(MediaType $mediaType): bool => $mediaType->format() === $this
        );
    }

    /**
     * Return the first found media type for the current format
     */
    public function mediaType(): MediaType
    {
        $types = $this->mediaTypes();

        return reset($types);
    }

    /**
     * Return the possible file extension for the current format
     *
     * @return array<FileExtension>
     */
    public function fileExtensions(): array
    {
        return array_filter(
            FileExtension::cases(),
            fn(FileExtension $fileExtension): bool => $fileExtension->format() === $this
        );
    }

    /**
     * Return the first found file extension for the current format
     */
    public function fileExtension(): FileExtension
    {
        $extensions = $this->fileExtensions();

        return reset($extensions);
    }

    /**
     * Create an encoder instance with given options that matches the format
     */
    public function encoder(mixed ...$options): EncoderInterface
    {
        // get classname of target encoder from current format
        $classname = match ($this) {
            self::AVIF => AvifEncoder::class,
            self::BMP => BmpEncoder::class,
            self::GIF => GifEncoder::class,
            self::HEIC => HeicEncoder::class,
            self::JP2 => Jpeg2000Encoder::class,
            self::JPEG => JpegEncoder::class,
            self::PNG => PngEncoder::class,
            self::TIFF => TiffEncoder::class,
            self::WEBP => WebpEncoder::class,
        };

        // get parameters of target encoder
        $parameters = [];
        $reflectionClass = new ReflectionClass($classname);
        if ($constructor = $reflectionClass->getConstructor()) {
            $parameters = array_map(
                fn(ReflectionParameter $parameter): string => $parameter->getName(),
                $constructor->getParameters(),
            );
        }

        // filter out unavailable options of target encoder
        $options = array_filter(
            $options,
            fn(mixed $key): bool => in_array($key, $parameters),
            ARRAY_FILTER_USE_KEY,
        );

        return new $classname(...$options);
    }
}
