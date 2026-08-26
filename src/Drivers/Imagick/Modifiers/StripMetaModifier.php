<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Collection;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class StripMetaModifier implements ModifierInterface, SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see Intervention\Image\Interfaces\ModifierInterface::apply()
     *
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // getImageProfiles(), stripImage() and profileImage() all act on the
        // frame the iterator is currently pointing at, so every frame has to
        // be stripped individually. Otherwise the meta data of an animated
        // image survives on every frame but one.
        foreach ($image as $frame) {
            // preserve icc profiles
            try {
                $profiles = $frame->native()->getImageProfiles('icc');
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to preserve icc profiles',
                    previous: $e,
                );
            }

            // remove meta data
            try {
                $result = $frame->native()->stripImage();
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to strip meta data',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to strip meta data',
                    previous: $e,
                );
            }

            if ($profiles !== []) {
                // re-apply icc profiles
                try {
                    $result = $frame->native()->profileImage("icc", $profiles['icc']);
                    if ($result === false) {
                        throw new ModifierException(
                            'Failed to apply ' . self::class . ', unable to re-apply icc profile',
                        );
                    }
                } catch (ImagickException $e) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to re-apply icc profile',
                        previous: $e,
                    );
                }
            }
        }

        $image->setExif(new Collection());

        return $image;
    }
}
