<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\CropModifier as GenericCropModifier;

class CropModifier extends GenericCropModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $originalSize = $image->size();
        $crop = $this->crop($image);
        $background = $this->backgroundColor()->convertTo(RgbColorspace::class);

        foreach ($image as $frame) {
            $this->cropFrame($frame, $originalSize, $crop, $background);
        }

        return $image;
    }

    protected function cropFrame(
        FrameInterface $frame,
        SizeInterface $originalSize,
        SizeInterface $resizeTo,
        ColorInterface $background
    ): void {
        // create new image with transparent background
        $modified = Cloner::cloneEmpty($frame->native(), $resizeTo, $background);

        // define offset
        $offsetX = $resizeTo->pivot()->x() + $this->x;
        $offsetY = $resizeTo->pivot()->y() + $this->y;

        // define target width & height
        $targetWidth = min($resizeTo->width(), $originalSize->width());
        $targetHeight = min($resizeTo->height(), $originalSize->height());
        $targetWidth = $targetWidth < $originalSize->width() ? $targetWidth + $offsetX : $targetWidth;
        $targetHeight = $targetHeight < $originalSize->height() ? $targetHeight + $offsetY : $targetHeight;

        // don't alpha blend for copy operation to keep transparent areas of original image
        $result = imagealphablending($modified, false);

        if ($result === false) {
            throw new ModifierException('Failed to set alpha blending');
        }

        // copy content from resource
        $result = imagecopyresampled(
            $modified,
            $frame->native(),
            $offsetX * -1,
            $offsetY * -1,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $targetWidth,
            $targetHeight
        );

        if ($result === false) {
            throw new ModifierException('Failed to resize image');
        }

        // set new content as resource
        $frame->setNative($modified);
    }
}
