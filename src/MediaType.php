<?php

declare(strict_types=1);

namespace Intervention\Image;

use Error;
use Intervention\Image\Exceptions\NotSupportedException;

enum MediaType: string
{
    case IMAGE_JPEG = 'image/jpeg';
    case IMAGE_JPG = 'image/jpg';
    case IMAGE_PJPEG = 'image/pjpeg';
    case IMAGE_X_JPEG = 'image/x-jpeg';
    case IMAGE_WEBP = 'image/webp';
    case IMAGE_X_WEBP = 'image/x-webp';
    case IMAGE_GIF = 'image/gif';
    case IMAGE_PNG = 'image/png';
    case IMAGE_X_PNG = 'image/x-png';
    case IMAGE_AVIF = 'image/avif';
    case IMAGE_X_AVIF = 'image/x-avif';
    case IMAGE_BMP = 'image/bmp';
    case IMAGE_MS_BMP = 'image/ms-bmp';
    case IMAGE_X_BITMAP = 'image/x-bitmap';
    case IMAGE_X_BMP = 'image/x-bmp';
    case IMAGE_X_MS_BMP = 'image/x-ms-bmp';
    case IMAGE_X_WINDOWS_BMP = 'image/x-windows-bmp';
    case IMAGE_X_WIN_BITMAP = 'image/x-win-bitmap';
    case IMAGE_X_XBITMAP = 'image/x-xbitmap';
    case IMAGE_X_BMP3 = 'image/x-bmp3';
    case IMAGE_TIFF = 'image/tiff';
    case IMAGE_JP2 = 'image/jp2';
    case IMAGE_X_JP2_CODESTREAM = 'image/x-jp2-codestream';
    case IMAGE_JPX = 'image/jpx';
    case IMAGE_JPM = 'image/jpm';
    case IMAGE_HEIC = 'image/heic';
    case IMAGE_X_HEIC = 'image/x-heic';
    case IMAGE_HEIF = 'image/heif';

    /**
     * Create media type from given identifier
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @throws NotSupportedException
     */
    public static function create(string|self|Format|FileExtension $identifier): self
    {
        if ($identifier instanceof self) {
            return $identifier;
        }

        if ($identifier instanceof Format) {
            return $identifier->mediaType();
        }

        if ($identifier instanceof FileExtension) {
            return $identifier->mediaType();
        }

        try {
            $type = self::from(strtolower($identifier));
        } catch (Error) {
            try {
                $type = FileExtension::from(strtolower($identifier))->mediaType();
            } catch (Error) {
                throw new NotSupportedException('Unable to create media type from "' . $identifier . '".');
            }
        }

        return $type;
    }

    /**
     * Try to create media type from given identifier and return null on failure
     *
     * @param string|Format|MediaType|FileExtension $identifier
     * @return MediaType|null
     */
    public static function tryCreate(string|self|Format|FileExtension $identifier): ?self
    {
        try {
            return self::create($identifier);
        } catch (NotSupportedException) {
            return null;
        }
    }

    /**
     * Return the matching format for the current media (MIME) type
     */
    public function format(): Format
    {
        return match ($this) {
            self::IMAGE_JPEG,
            self::IMAGE_JPG,
            self::IMAGE_PJPEG,
            self::IMAGE_X_JPEG => Format::JPEG,
            self::IMAGE_WEBP,
            self::IMAGE_X_WEBP => Format::WEBP,
            self::IMAGE_GIF => Format::GIF,
            self::IMAGE_PNG,
            self::IMAGE_X_PNG => Format::PNG,
            self::IMAGE_AVIF,
            self::IMAGE_X_AVIF => Format::AVIF,
            self::IMAGE_BMP,
            self::IMAGE_MS_BMP,
            self::IMAGE_X_BITMAP,
            self::IMAGE_X_BMP,
            self::IMAGE_X_MS_BMP,
            self::IMAGE_X_XBITMAP,
            self::IMAGE_X_WINDOWS_BMP,
            self::IMAGE_X_BMP3,
            self::IMAGE_X_WIN_BITMAP => Format::BMP,
            self::IMAGE_TIFF => Format::TIFF,
            self::IMAGE_JP2,
            self::IMAGE_JPX,
            self::IMAGE_X_JP2_CODESTREAM,
            self::IMAGE_JPM => Format::JP2,
            self::IMAGE_HEIF,
            self::IMAGE_HEIC,
            self::IMAGE_X_HEIC => Format::HEIC,
        };
    }

    /**
     * Return the possible file extension for the current media type
     *
     * @return array<FileExtension>
     */
    public function fileExtensions(): array
    {
        return $this->format()->fileExtensions();
    }

    /**
     * Return the first file extension for the current media type
     */
    public function fileExtension(): FileExtension
    {
        return $this->format()->fileExtension();
    }
}
