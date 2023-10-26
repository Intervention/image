<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractFitModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class FitModifier extends AbstractFitModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $frame->core()->extentImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->getX(),
                $crop->pivot()->getY()
            );

            $frame->core()->scaleImage(
                $resize->width(),
                $resize->height()
            );
        }

        return $image;
    }
}
