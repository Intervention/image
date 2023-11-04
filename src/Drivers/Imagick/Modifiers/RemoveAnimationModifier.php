<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRemoveAnimationModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Traits\CanCheckType;

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

        // create new imagick with just one image
        $imagick = new Imagick();
        $frame = $this->chosenFrame($image, $this->position);
        $imagick->addImage($frame->core()->getImage());

        return $image->setImagick($imagick);
    }
}
