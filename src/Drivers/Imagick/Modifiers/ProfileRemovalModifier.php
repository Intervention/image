<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanCheckType;

class ProfileRemovalModifier implements ModifierInterface
{
    use CanCheckType;

    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $this->failIfNotClass($image, Image::class)->getImagick();
        $result = $imagick->profileImage('icc', null);

        if ($result === false) {
            throw new ColorException('ICC color profile could not be removed.');
        }

        return $image;
    }
}
