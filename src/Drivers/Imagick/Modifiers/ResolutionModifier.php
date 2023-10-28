<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Traits\CanCheckType;

class ResolutionModifier implements ModifierInterface
{
    use CanCheckType;

    public function __construct(protected float $x, protected float $y)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $this->failIfNotClass($image, Image::class)->getImagick();
        $imagick->setImageResolution($this->x, $this->y);

        return $image;
    }
}
