<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class RemoveAnimationModifier implements ModifierInterface
{
    public function __construct(protected int $position = 0)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        if (!$image->isAnimated()) {
            throw new RuntimeException('Image is not animated.');
        }

        $imagick = new Imagick();
        foreach ($image as $frame) {
            if ($frame->getCore()->getIteratorIndex() == $this->position) {
                $imagick->addImage($frame->getCore()->getImage());
            }
        }

        $image->destroy();

        return new Image($imagick);
    }
}
