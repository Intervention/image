<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @method SizeInterface getResizeSize(ImageInterface $image)
 * @method SizeInterface getCropSize(ImageInterface $image)
 */
class FitModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $frame->native()->extentImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x(),
                $crop->pivot()->y()
            );

            $frame->native()->scaleImage(
                $resize->width(),
                $resize->height()
            );
        }

        return $image;
    }
}
