<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\OrientModifier as GenericOrientModifier;

class OrientModifier extends GenericOrientModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // getImageOrientation() reads the frame the iterator is pointing at as
        // well, so read it off the first frame rather than off whichever one
        // the last operation happened to leave it on
        $image->core()->native()->setFirstIterator();

        $orientation = $image->core()->native()->getImageOrientation();
        $orientation = $orientation === Imagick::ORIENTATION_UNDEFINED
            ? $image->core()->meta()->get('originalImageOrientation', 0)
            : $orientation;

        // rotateImage() and flopImage() only act on the frame the iterator is
        // currently pointing at, so every frame has to be transformed
        // individually. Otherwise an animated source comes back with only one
        // frame turned and a mixed geometry sequence.
        foreach ($image as $frame) {
            try {
                $result = match ($orientation) {
                    Imagick::ORIENTATION_TOPRIGHT
                    => $frame->native()->flopImage(), // 2

                    Imagick::ORIENTATION_BOTTOMRIGHT
                    => $frame->native()->rotateImage('#000', 180), // 3

                    Imagick::ORIENTATION_BOTTOMLEFT
                    => $frame->native()->rotateImage('#000', 180) && $frame->native()->flopImage(), // 4

                    Imagick::ORIENTATION_LEFTTOP
                    => $frame->native()->rotateImage('#000', 90) && $frame->native()->flopImage(), // 5

                    Imagick::ORIENTATION_RIGHTTOP
                    => $frame->native()->rotateImage('#000', 90), // 6

                    Imagick::ORIENTATION_RIGHTBOTTOM
                    => $frame->native()->rotateImage('#000', 270) && $frame->native()->flopImage(), // 7

                    Imagick::ORIENTATION_LEFTBOTTOM
                    => $frame->native()->rotateImage('#000', 270), // 8

                    default => true,
                };

                // set new orientation in frame
                $result = $result && $frame->native()->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to process rotation of image',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to process rotation',
                    previous: $e,
                );
            }
        }

        return $image;
    }
}
