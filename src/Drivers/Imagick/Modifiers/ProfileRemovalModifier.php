<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;

class ProfileRemovalModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $image->core()->native();
        $result = $imagick->profileImage('icc', null);

        if ($result === false) {
            throw new ColorException('ICC color profile could not be removed.');
        }

        return $image;
    }
}
