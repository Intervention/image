<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\TrimModifier as GenericTrimModifier;
use ValueError;

class TrimModifier extends GenericTrimModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws NotSupportedException
     * @throws StateException
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($image->isAnimated()) {
            throw new NotSupportedException('Trim modifier cannot be applied to animated images');
        }

        $canvas = $this->transparencySafeVersion($image->core()->native());

        // apply tolerance with a min. value of .5 because the default tolerance of '0' should
        // already trim away similar colors which is not the case with imagecropauto.
        $trimmed = imagecropauto(
            $canvas,
            IMG_CROP_THRESHOLD,
            max([.5, $this->tolerance / 10]),
            $this->trimColor($image),
        );

        // if the tolerance is very high, it is possible that no image is left.
        // imagick returns a 1x1 pixel image in this case. this does the same.
        if ($trimmed === false) {
            $trimmed = $this->driver()->createImage(1, 1)->core()->native();
        }

        $image->core()->setNative($trimmed);

        return $image;
    }

    /**
     * Copy the given GdImage to a fresh true color canvas that has no transparent color set.
     *
     * imagecropauto() internally drops every pixel that matches the image's "transparent
     * color". palette images that were converted to true color while decoding carry over
     * a bogus transparent color, which would turn all transparent areas opaque during the
     * crop. transfer the image to a fresh true color canvas without a transparent color
     * to preserve the alpha channel.
     *
     * @throws ModifierException
     */
    private function transparencySafeVersion(GdImage $gd): GdImage
    {
        $width = imagesx($gd);
        $height = imagesy($gd);

        $canvas = imagecreatetruecolor($width, $height);

        if ($canvas === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to create canvas',
            );
        }

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        // pre-fill with the original transparent color (if any).
        $transparent = imagecolortransparent($gd);
        if ($transparent !== -1) {
            imagefill($canvas, 0, 0, $transparent);
        }

        // copy resolution to canvas
        $resolution = imageresolution($gd);
        if (is_array($resolution) && array_key_exists(0, $resolution) && array_key_exists(1, $resolution)) {
            imageresolution($canvas, $resolution[0], $resolution[1]);
        }

        imagecopy($canvas, $gd, 0, 0, 0, 0, $width, $height);

        return $canvas;
    }

    /**
     * Create an average color from the colors of the four corner points of the given image
     *
     * @throws ModifierException
     */
    private function trimColor(ImageInterface $image): int
    {
        // trim color base
        $red = 0;
        $green = 0;
        $blue = 0;
        $alpha = 0;

        // corner coordinates
        $size = $image->size();
        $cornerPoints = [
            new Point(0, 0),
            new Point($size->width() - 1, 0),
            new Point(0, $size->height() - 1),
            new Point($size->width() - 1, $size->height() - 1),
        ];

        // create an average color to be used in trim operation
        foreach ($cornerPoints as $pos) {
            $cornerColor = imagecolorat($image->core()->native(), $pos->x(), $pos->y());

            if ($cornerColor === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to determine average color for process',
                );
            }

            try {
                $rgb = imagecolorsforindex($image->core()->native(), $cornerColor);
            } catch (ValueError) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to read trim color from index',
                );
            }

            $red += round(round($rgb['red'] / 51) * 51);
            $green += round(round($rgb['green'] / 51) * 51);
            $blue += round(round($rgb['blue'] / 51) * 51);
            $alpha += $rgb['alpha'];
        }

        $red = (int) round($red / 4);
        $green = (int) round($green / 4);
        $blue = (int) round($blue / 4);
        $alpha = (int) round($alpha / 4);

        $color = imagecolorallocatealpha($image->core()->native(), $red, $green, $blue, $alpha);

        if ($color === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to allocate trim color',
            );
        }

        return $color;
    }
}
