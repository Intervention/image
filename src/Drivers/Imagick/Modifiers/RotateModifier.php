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
            ->colorProcessor($image)
            ->export($this->backgroundColor());

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->rotateImage($background, $this->rotationAngle());

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to rotate image',
                    );
                }

                // Reset the virtual canvas page that rotateImage() leaves behind. A
                // non-right angle produces a negative page offset which otherwise
                // corrupts the animated-AVIF (libheif sequences) writer, leaving the
                // bottom/right region transparent. Mirrors TrimModifier/CoverModifier.
                $frame->native()->setImagePage(0, 0, 0, 0);
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to rotate image',
                    previous: $e,
                );
            }
        }

        return $image;
    }
}
