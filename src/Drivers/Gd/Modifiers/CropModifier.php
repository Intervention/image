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
 */
class CropModifier extends SpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->crop($image);

        foreach ($image as $frame) {
            $this->cropFrame($frame, $crop);
        }

        return $image;
    }

    protected function cropFrame(FrameInterface $frame, SizeInterface $resizeTo): void
    {
        // create new image
        $modified = Cloner::cloneEmpty($frame->native(), $resizeTo);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $frame->native(),
            0,
            0,
            $resizeTo->pivot()->x() + $this->offset_x,
            $resizeTo->pivot()->y() + $this->offset_y,
            $resizeTo->width(),
            $resizeTo->height(),
            $resizeTo->width(),
            $resizeTo->height(),
        );

        // set new content as recource
        $frame->setNative($modified);
    }
}
