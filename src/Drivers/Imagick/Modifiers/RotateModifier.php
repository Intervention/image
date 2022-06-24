<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class RotateModifier extends AbstractRotateModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->backgroundColor();
        if (!is_a($background, Color::class)) {
            throw new DecoderException('Unable to decode given background color.');
        }

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
