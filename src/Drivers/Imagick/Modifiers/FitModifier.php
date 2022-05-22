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
            $frame->getCore()->extentImage(
                $crop->getWidth(),
                $crop->getHeight(),
                $crop->getPivot()->getX(),
                $crop->getPivot()->getY()
            );

            $frame->getCore()->scaleImage(
                $resize->getWidth(),
                $resize->getHeight()
            );
        }

        return $image;
    }
}
