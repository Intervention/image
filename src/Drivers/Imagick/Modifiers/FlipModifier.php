<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Direction;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FlipModifier as GenericFlipModifier;

class FlipModifier extends GenericFlipModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            try {
                $result = $this->direction === Direction::HORIZONTAL
                    ? $frame->native()->flopImage()
                    : $frame->native()->flipImage();
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to mirror image',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to mirror image',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
