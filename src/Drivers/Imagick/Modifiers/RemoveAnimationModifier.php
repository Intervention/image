<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RemoveAnimationModifier as GenericRemoveAnimationModifier;

class RemoveAnimationModifier extends GenericRemoveAnimationModifier implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // create new imagick with just one image
        $imagick = new Imagick();
        $frame = $this->selectedFrame($image);

        try {
            $result = $imagick->addImage($frame->native()->getImage());
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to re-apply image frame',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to re-apply image frame',
                previous: $e
            );
        }

        // set new imagick to image
        $image->core()->setNative($imagick);

        return $image;
    }
}
