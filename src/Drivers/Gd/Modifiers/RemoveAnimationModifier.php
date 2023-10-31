<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanCheckType;

class RemoveAnimationModifier implements ModifierInterface
{
    use CanCheckType;

    public function __construct(protected int $position = 0)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        if (!$image->isAnimated()) {
            throw new RuntimeException('Image is not animated.');
        }

        $image = $this->failIfNotClass($image, Image::class);

        $frames = new Collection();
        foreach ($image as $key => $frame) {
            if ($this->position == $key) {
                $frames->push($frame);
            }
        }

        return $image->setFrames($frames);
    }
}
