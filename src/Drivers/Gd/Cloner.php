<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use GdImage;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\SizeInterface;

class Cloner
{
    /**
     * Create a clone of the given GdImage
     *
     * @param GdImage $gd
     * @throws ColorException
     * @return GdImage
     */
    public static function clone(GdImage $gd): GdImage
    {
        // create empty canvas with same size
        $clone = static::cloneEmpty($gd);

        // transfer actual image to clone
        imagecopy($clone, $gd, 0, 0, 0, 0, imagesx($gd), imagesy($gd));

        return $clone;
    }

    /**
     * Create an "empty" clone of the given GdImage
     *
     * This only retains the basic data without transferring the actual image.
     * It is optionally possible to change the size of the result and set a
     * background color.
     *
     * @param GdImage $gd
     * @param null|SizeInterface $size
     * @param ColorInterface $background
     * @throws ColorException
     * @return GdImage
     */
    public static function cloneEmpty(
        GdImage $gd,
        ?SizeInterface $size = null,
        ColorInterface $background = new Color(255, 255, 255, 0)
    ): GdImage {
        // define size
        $size = match (true) {
            is_null($size) => new Rectangle(imagesx($gd), imagesy($gd)),
            default => $size,
        };

        // create new gd image with same size or new given size
        $clone = imagecreatetruecolor($size->width(), $size->height());

        // copy resolution to clone
        $resolution = imageresolution($gd);
        if (is_array($resolution) && array_key_exists(0, $resolution) && array_key_exists(1, $resolution)) {
            imageresolution($clone, $resolution[0], $resolution[1]);
        }

        // fill with background
        $processor = new ColorProcessor();
        imagefill($clone, 0, 0, $processor->colorToNative($background));
        imagealphablending($clone, true);
        imagesavealpha($clone, true);

        return $clone;
    }

    /**
     * Create a clone of an GdImage that is positioned on the specified background color.
     * Possible transparent areas are mixed with this color.
     *
     * @param GdImage $gd
     * @param ColorInterface $background
     * @throws ColorException
     * @return GdImage
     */
    public static function cloneBlended(GdImage $gd, ColorInterface $background): GdImage
    {
        // create empty canvas with same size
        $clone = static::cloneEmpty($gd, background: $background);

        // transfer actual image to clone
        imagecopy($clone, $gd, 0, 0, 0, 0, imagesx($gd), imagesy($gd));

        return $clone;
    }
}
