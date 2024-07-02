<?php

declare(strict_types=1);

namespace Intervention\Image;

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
    case IMAGE_TIFF = 'image/tiff';
    case IMAGE_JP2 = 'image/jp2';
    case IMAGE_JPX = 'image/jpx';
    case IMAGE_JPM = 'image/jpm';
    case IMAGE_HEIC = 'image/heic';
    case IMAGE_HEIF = 'image/heif';

    /**
     * Return the matching format for the current media (MIME) type
     *
     * @return Format
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
            self::IMAGE_X_WIN_BITMAP => Format::BMP,
            self::IMAGE_TIFF => Format::TIFF,
            self::IMAGE_JP2,
            self::IMAGE_JPX,
            self::IMAGE_JPM => Format::JP2,
            self::IMAGE_HEIF,
            self::IMAGE_HEIC => Format::HEIC,
        };
    }
}
