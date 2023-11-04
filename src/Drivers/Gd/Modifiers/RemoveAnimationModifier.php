<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRemoveAnimationModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanCheckType;
use Intervention\Image\Drivers\Gd\Image;

class RemoveAnimationModifier extends AbstractRemoveAnimationModifier
{
    use CanCheckType;

    public function __construct(protected int|string $position = 0)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $image = $this->failIfNotClass($image, Image::class);
        return $image->setFrames(new Collection([
            $this->chosenFrame($image, $this->position)
        ]));
    }
}
