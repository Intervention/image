<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class RotateModifier extends AbstractRotateModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->failIfNotClass($this->backgroundColor(), Color::class);

        foreach ($image as $frame) {
            $frame->getCore()->rotateImage(
                $background->getPixel(),
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
