<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\CoverModifier as GenericCoverModifier;

class CoverModifier extends GenericCoverModifier implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->cropSize($image);
        $resize = $this->resizeSize($crop);

        foreach ($image as $frame) {
            try {
                $frame->native()->cropImage(
                    $crop->width(),
                    $crop->height(),
                    $crop->pivot()->x(),
                    $crop->pivot()->y()
                );

                $frame->native()->scaleImage(
                    $resize->width(),
                    $resize->height()
                );

                $frame->native()->setImagePage(0, 0, 0, 0);
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to resize image',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
