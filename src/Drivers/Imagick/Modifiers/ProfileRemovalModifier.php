<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ProfileRemovalModifier as GenericProfileRemovalModifier;

class ProfileRemovalModifier extends GenericProfileRemovalModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $image->core()->native();

        try {
            $result = $imagick->profileImage('icc', null);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to remove ICC color profile',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to remove ICC color profile',
                previous: $e
            );
        }

        return $image;
    }
}
