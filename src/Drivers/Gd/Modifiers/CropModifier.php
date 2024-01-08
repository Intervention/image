<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Drivers\Gd\SpecializedModifier;
use Intervention\Image\Interfaces\ColorInterface;
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
        $background = $this->driver()->handleInput($this->background);

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
        // create new image
        $modified = Cloner::cloneEmpty($frame->native(), $resizeTo, $background);

        // define offset
        $offset_x = ($resizeTo->pivot()->x() + $this->offset_x);
        $offset_y = ($resizeTo->pivot()->y() + $this->offset_y);

        // define target width & height
        $targetWidth = min($resizeTo->width(), $originalSize->width());
        $targetHeight = min($resizeTo->height(), $originalSize->height());
        $targetWidth = $targetWidth < $originalSize->width() ? $targetWidth + $offset_x : $targetWidth;
        $targetHeight = $targetHeight < $originalSize->height() ? $targetHeight + $offset_y : $targetHeight;

        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha(
            $modified,
            $background->channel(Red::class)->value(),
            $background->channel(Green::class)->value(),
            $background->channel(Blue::class)->value(),
            127,
        );

        imagealphablending($modified, false); // do not blend / just overwrite
        // imagecolortransparent($modified, $transparent);
        imagefilledrectangle(
            $modified,
            $offset_x * -1,
            $offset_y * -1,
            $targetWidth,
            $targetHeight,
            $transparent
        );

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

        imagealphablending($modified, true);

        // set new content as recource
        $frame->setNative($modified);
    }
}
