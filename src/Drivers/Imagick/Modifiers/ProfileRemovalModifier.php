<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ProfileRemovalModifier extends DriverSpecialized implements ModifierInterface
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
