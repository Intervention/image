<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class RotateModifier extends AbstractRotateModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->setCore(
                imagerotate(
                    $frame->getCore(),
                    $this->rotationAngle(),
                    $this->backgroundColor()->toInt()
                )
            );
        }

        return $image;
    }
}
