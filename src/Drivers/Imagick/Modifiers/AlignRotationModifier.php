<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\AlignRotationModifier as GenericAlignRotationModifier;

class AlignRotationModifier extends GenericAlignRotationModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        try {
            $result = match ($image->core()->native()->getImageOrientation()) {
                Imagick::ORIENTATION_TOPRIGHT
                => $image->core()->native()->flopImage(), // 2

                Imagick::ORIENTATION_BOTTOMRIGHT
                => $image->core()->native()->rotateImage('#000', 180), // 3

                Imagick::ORIENTATION_BOTTOMLEFT
                => $image->core()->native()->rotateImage('#000', 180) && $image->core()->native()->flopImage(), // 4

                Imagick::ORIENTATION_LEFTTOP
                => $image->core()->native()->rotateImage('#000', -270) && $image->core()->native()->flopImage(), // 5

                Imagick::ORIENTATION_RIGHTTOP
                => $image->core()->native()->rotateImage('#000', -270), // 6

                Imagick::ORIENTATION_RIGHTBOTTOM
                => $image->core()->native()->rotateImage('#000', -90) && $image->core()->native()->flopImage(), // 7

                Imagick::ORIENTATION_LEFTBOTTOM
                => $image->core()->native()->rotateImage('#000', -90), // 8

                default => 'value',
            };

            // set new orientation in image
            $result = $result && $image->core()->native()->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to process rotation of image',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to process rotation',
                previous: $e
            );
        }

        return $image;
    }
}
