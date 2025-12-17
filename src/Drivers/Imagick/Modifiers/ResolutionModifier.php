<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResolutionModifier as GenericResolutionModifier;

class ResolutionModifier extends GenericResolutionModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $image->core()->native();

        try {
            $result = $imagick->setImageResolution($this->x, $this->y);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set image resolution',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to set image resolution',
                previous: $e
            );
        }

        return $image;
    }
}
