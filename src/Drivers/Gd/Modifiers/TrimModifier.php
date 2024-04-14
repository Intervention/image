<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\TrimModifier as GenericTrimModifier;

class TrimModifier extends GenericTrimModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($image->isAnimated()) {
            throw new NotSupportedException('Trim modifier cannot be applied to animated images.');
        }

        // apply tolerance with a min. value of .5 because the default tolerance of '0' should
        // already trim away similar colors which is not the case with imagecropauto.
        $trimmed = imagecropauto(
            $image->core()->native(),
            IMG_CROP_THRESHOLD,
            max([.5, $this->tolerance / 10]),
            $this->trimColor($image)
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
     * Create an average color from the colors of the four corner points of the given image
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @throws AnimationException
     * @return int
     */
    private function trimColor(ImageInterface $image): int
    {
        // trim color base
        $red = 0;
        $green = 0;
        $blue = 0;

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
            $rgb = imagecolorsforindex($image->core()->native(), $cornerColor);
            $red += round(round(($rgb['red'] / 51)) * 51);
            $green += round(round(($rgb['green'] / 51)) * 51);
            $blue += round(round(($rgb['blue'] / 51)) * 51);
        }

        $red = (int) round($red / 4);
        $green = (int) round($green / 4);
        $blue = (int) round($blue / 4);

        return imagecolorallocate($image->core()->native(), $red, $green, $blue);
    }
}
