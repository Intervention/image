<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ProfileModifier as GenericProfileModifier;

class ProfileModifier extends GenericProfileModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $image->core()->native();
        $result = $imagick->profileImage('icc', (string) $this->profile);

        if ($result === false) {
            throw new ColorException('ICC color profile could not be set.');
        }

        return $image;
    }
}
