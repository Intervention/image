<?php

declare(strict_types=1);

namespace Intervention\Image;

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
    case JPF = 'jpf';
    case JPM = 'jpm';
    case JPG2 = 'jpg2';
    case J2C = 'j2c';
    case JPC = 'jpc';
    case JPX = 'jpx';
    case HEIC = 'heic';
    case HEIF = 'heif';

    /**
     * Return the matching format for the current file extension
     *
     * @return Format
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
     *
     * @return MediaType
     */
    public function mediaType(): MediaType
    {
        return $this->format()->mediaType();
    }
}
