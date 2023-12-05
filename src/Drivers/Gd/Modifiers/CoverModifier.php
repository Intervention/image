<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface getResizeSize(ImageInterface $image)
 * @method SizeInterface getCropSize(ImageInterface $image)
 */
class CoverModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $crop, $resize);
        }

        return $image;
    }

    protected function modifyFrame(FrameInterface $frame, SizeInterface $crop, SizeInterface $resize): void
    {
        // create new image
        $modified = $this->driver()->createImage(
            $resize->width(),
            $resize->height()
        )->core()->native();

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
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $resize->width(),
            $resize->height(),
            $crop->width(),
            $crop->height()
        );

        // set new content as resource
        $frame->setNative($modified);
    }
}
