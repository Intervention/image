<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RotateModifier as GenericRotateModifier;

class RotateModifier extends GenericRotateModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative(
                $this->backgroundColor()
            );

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->rotateImage(
                    $background,
                    $this->rotationAngle() * -1
                );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to rotate image',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to rotate image',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
