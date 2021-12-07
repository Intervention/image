<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class RotateModifier extends AbstractRotateModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->getCore()->rotateImage(
                $this->backgroundColor()->getPixel(),
                $this->rotationAngle()
            );
        }

        return $image;
    }

    protected function rotationAngle(): float
    {
        return parent::rotationAngle() * -1;
    }
}
