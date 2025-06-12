<?php

declare(strict_types=1);

namespace Intervention\Image;

use Error;
use Intervention\Image\Exceptions\NotSupportedException;

enum FileExtension: string
{
    case JPG = 'jpg';
    case JPEG = 'jpeg';
    case WEBP = 'webp';
    case AVIF = 'avif';
    case BMP = 'bmp';
    case GIF = 'gif';
    case PNG = 'png';
    case TIF = 'tif';
    case TIFF = 'tiff';
    case JP2 = 'jp2';
    case J2K = 'j2k';
    case JP2K = 'jp2k';
    case JPF = 'jpf';
    case JPM = 'jpm';
    case JPG2 = 'jpg2';
    case J2C = 'j2c';
    case JPC = 'jpc';
    case JPX = 'jpx';
    case HEIC = 'heic';
    case HEIF = 'heif';

    /**
     * Create file extension from given identifier
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @throws NotSupportedException
     */
    public static function create(string|self|Format|MediaType $identifier): self
    {
        if ($identifier instanceof self) {
            return $identifier;
        }

        if ($identifier instanceof Format) {
            return $identifier->fileExtension();
        }

        if ($identifier instanceof MediaType) {
            return $identifier->fileExtension();
        }

        try {
            $extension = self::from(strtolower($identifier));
        } catch (Error) {
            try {
                $extension = MediaType::from(strtolower($identifier))->fileExtension();
            } catch (Error) {
                throw new NotSupportedException('Unable to create file extension from "' . $identifier . '".');
            }
        }

        return $extension;
    }

    /**
     * Try to create media type from given identifier and return null on failure
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @return FileExtension|null
     */
    public static function tryCreate(string|self|Format|MediaType $identifier): ?self
    {
        try {
            return self::create($identifier);
        } catch (NotSupportedException) {
            return null;
        }
    }

    /**
     * Return the matching format for the current file extension
     */
    public function format(): Format
    {
        return match ($this) {
            self::JPEG,
            self::JPG => Format::JPEG,
            self::WEBP => Format::WEBP,
            self::GIF => Format::GIF,
            self::PNG => Format::PNG,
            self::AVIF => Format::AVIF,
            self::BMP => Format::BMP,
            self::TIF,
            self::TIFF => Format::TIFF,
            self::JP2,
            self::JP2K,
            self::J2K,
            self::JPF,
            self::JPM,
            self::JPG2,
            self::J2C,
            self::JPC,
            self::JPX => Format::JP2,
            self::HEIC,
            self::HEIF => Format::HEIC,
        };
    }

    /**
     * Return media types for the current format
     *
     * @return array<MediaType>
     */
    public function mediaTypes(): array
    {
        return $this->format()->mediaTypes();
    }

    /**
     * Return the first found media type for the current format
     */
    public function mediaType(): MediaType
    {
        return $this->format()->mediaType();
    }
}
