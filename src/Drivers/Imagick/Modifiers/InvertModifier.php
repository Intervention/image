<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\InvertModifier as GenericInvertModifier;

class InvertModifier extends GenericInvertModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // only touch color channels for invertion
        $channel = Imagick::CHANNEL_ALL & ~Imagick::CHANNEL_ALPHA;

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->negateImage(false, $channel);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to invert image colors',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to invert image colors',
                    previous: $e,
                );
            }
        }

        return $image;
    }
}
