<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ImageException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
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
        $watermark = $this->watermark();
        $position = $this->position($image, $watermark);
        $watermarkSize = $watermark->size();

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imagecopy(
                $frame->native(),
                $watermark->core()->native(),
                $position->x(),
                $position->y(),
                0,
                0,
                $watermarkSize->width(),
                $watermarkSize->height(),
            );
        }

        return $image;
    }

    /**
     * Build watermark image.
     *
     * @throws ModifierException
     */
    private function watermark(): ImageInterface
    {
        try {
            $watermark = $this->driver()->decodeImage($this->image);

            return $this->transparency === 1.0 ? $watermark : $this->buildFadedWatermark($watermark);
        } catch (ImageException $e) {
            throw new ModifierException('Failed to build watermark', previous: $e);
        }
    }

    /**
     * Build a faded copy of the watermark by scaling each pixel's alpha
     * by the requested transparency factor. Created once and reused for
     * every frame.
     *
     * @throws ModifierException
     */
    private function buildFadedWatermark(ImageInterface $watermark): ImageInterface
    {
        $width = $watermark->width();
        $height = $watermark->height();

        $faded = imagecreatetruecolor($width, $height);

        if ($faded === false) {
            throw new ModifierException('Failed to build watermark image');
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

        try {
            return $this->driver()->decodeImage($faded);
        } catch (StateException $e) {
            throw new ModifierException('Failed to build watermark', previous: $e);
        }
    }
}
