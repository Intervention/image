<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\InsertModifier as GenericInsertModifier;

class InsertModifier extends GenericInsertModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->decodeImage($this->image);
        $position = $this->position($image, $watermark);

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);

            if ($this->transparency === 1.0) {
                $this->insertOpaque($frame, $watermark, $position);
            } else {
                $this->insertTransparent($frame, $watermark, $position);
            }
        }

        return $image;
    }

    /**
     * Insert watermark with 100% opacity
     *
     * @throws ModifierException
     */
    private function insertOpaque(FrameInterface $frame, ImageInterface $watermark, PointInterface $position): void
    {
        imagecopy(
            $frame->native(),
            $watermark->core()->native(),
            $position->x(),
            $position->y(),
            0,
            0,
            $watermark->width(),
            $watermark->height()
        );
    }

    /**
     * Insert watermark with the given partial transparency.
     *
     * The previous implementation copied the base region into an opaque black
     * scratch canvas and then used imagecopymerge() to blend the watermark
     * back. That worked for opaque base images but had two known failures:
     * imagecopymerge() does not preserve source alpha, and any transparent
     * pixel in the base region was overwritten with opaque black before the
     * merge ran, so the watermark's bounding box ended up filled with black
     * wherever the base used to be transparent.
     *
     * Instead, build a faded copy of the watermark by scaling each pixel's
     * alpha by the requested transparency factor, then composite that copy
     * with imagecopy() relying on the destination's alpha blending. The base
     * image's transparent regions stay transparent and partial alpha in the
     * watermark is preserved.
     *
     * @throws ModifierException
     */
    private function insertTransparent(FrameInterface $frame, ImageInterface $watermark, PointInterface $position): void
    {
        $width = $watermark->width();
        $height = $watermark->height();

        $faded = imagecreatetruecolor($width, $height);

        if ($faded === false) {
            throw new ModifierException('Failed to insert image');
        }

        imagealphablending($faded, false);
        imagesavealpha($faded, true);

        $watermarkNative = $watermark->core()->native();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($watermarkNative, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                // GD stores alpha as 0 (opaque) … 127 (transparent), so scale
                // the opacity (127 - alpha) and flip back to GD's convention.
                $newAlpha = 127 - (int) round((127 - $alpha) * $this->transparency);
                imagesetpixel($faded, $x, $y, ($newAlpha << 24) | ($color & 0xFFFFFF));
            }
        }

        imagecopy(
            $frame->native(),
            $faded,
            $position->x(),
            $position->y(),
            0,
            0,
            $width,
            $height,
        );
    }
}
