<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ProfileModifier as GenericProfileModifier;

class ProfileModifier extends GenericProfileModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // read the profile once, casting it rewinds and drains its stream
        $profile = (string) $this->profile;

        // profileImage() only acts on the frame the iterator is currently
        // pointing at, so every frame has to be profiled individually.
        foreach ($image as $frame) {
            try {
                $result = $frame->native()->profileImage('icc', $profile);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set ICC color profile',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to set ICC color profile',
                    previous: $e,
                );
            }
        }

        return $image;
    }
}
