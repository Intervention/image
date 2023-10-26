<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Image;
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

        $frames = new Collection();
        foreach ($image as $key => $frame) {
            if ($this->position == $key) {
                $frames->push($frame);
            } else {
                imagedestroy($frame->core());
            }
        }

        return new Image($frames);
    }
}
