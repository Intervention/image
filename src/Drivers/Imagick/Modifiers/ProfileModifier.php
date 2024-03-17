<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\ProfileInterface;

/**
 * @property ProfileInterface $profile
 */
class ProfileModifier implements ModifierInterface
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
