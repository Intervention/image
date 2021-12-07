<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractFitModifier;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

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
