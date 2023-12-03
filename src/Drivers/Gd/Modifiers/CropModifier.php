<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface crop(ImageInterface $image)
 * @property int $offset_x
 * @property int $offset_y
 */
class CropModifier extends DriverSpecializedModifier
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
        $modified = $this->driver()
            ->createImage($resizeTo->width(), $resizeTo->height())
            ->core()
            ->native();

        // get original image
        $original = $frame->native();

        // preserve transparency
        $transIndex = imagecolortransparent($original);

        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);
        }

        // copy content from resource
        imagecopyresampled(
            $modified,
            $original,
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
