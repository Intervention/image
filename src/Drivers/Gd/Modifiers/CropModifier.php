<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Drivers\Gd\SpecializedModifier;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface crop(ImageInterface $image)
 * @property int $offset_x
 * @property int $offset_y
 * @property mixed $background
 */
class CropModifier extends SpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $originalSize = $image->size();
        $crop = $this->crop($image);
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        foreach ($image as $frame) {
            $this->cropFrame($frame, $originalSize, $crop, $background);
        }

        return $image;
    }

    protected function cropFrame(
        FrameInterface $frame,
        SizeInterface $originalSize,
        SizeInterface $resizeTo,
        int $background
    ): void {
        // create new image with transparent background
        $modified = Cloner::cloneEmpty($frame->native(), $resizeTo);

        // define offset
        $offset_x = ($resizeTo->pivot()->x() + $this->offset_x);
        $offset_y = ($resizeTo->pivot()->y() + $this->offset_y);

        // define target width & height
        $targetWidth = min($resizeTo->width(), $originalSize->width());
        $targetHeight = min($resizeTo->height(), $originalSize->height());
        $targetWidth = $targetWidth < $originalSize->width() ? $targetWidth + $offset_x : $targetWidth;
        $targetHeight = $targetHeight < $originalSize->height() ? $targetHeight + $offset_y : $targetHeight;

        // copy content from resource
        imagecopyresampled(
            $modified,
            $frame->native(),
            $offset_x * -1,
            $offset_y * -1,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $targetWidth,
            $targetHeight
        );

        imagealphablending($modified, false);
        // cover the possible newly created areas with background color
        if ($resizeTo->width() > $originalSize->width() || $this->offset_x > 0) {
            imagefilledrectangle(
                $modified,
                $originalSize->width() + ($this->offset_x * -1),
                0,
                $resizeTo->width(),
                $resizeTo->height(),
                $background
            );
        }

        // cover the possible newly created areas with background color
        if ($resizeTo->width() > $originalSize->height() || $this->offset_y > 0) {
            imagefilledrectangle(
                $modified,
                ($this->offset_x * -1),
                $originalSize->height() + ($this->offset_y * -1),
                ($this->offset_x * -1) + $originalSize->width() - 1,
                $resizeTo->height(),
                $background
            );
        }

        // cover the possible newly created areas with background color
        if ($this->offset_x < 0) {
            imagefilledrectangle(
                $modified,
                0,
                0,
                ($this->offset_x * -1) - 1,
                $resizeTo->height(),
                $background
            );
        }

        // cover the possible newly created areas with background color
        if ($this->offset_y < 0) {
            imagefilledrectangle(
                $modified,
                $this->offset_x * -1,
                0,
                ($this->offset_x * -1) + $originalSize->width() - 1,
                ($this->offset_y * -1) - 1,
                $background
            );
        }

        // set new content as recource
        $frame->setNative($modified);
    }
}
