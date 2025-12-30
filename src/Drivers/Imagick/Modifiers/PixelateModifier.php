<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\PixelateModifier as GenericPixelateModifier;

class PixelateModifier extends GenericPixelateModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->pixelateFrame($frame);
        }

        return $image;
    }

    /**
     * @throws ModifierException
     */
    protected function pixelateFrame(FrameInterface $frame): void
    {
        $size = $frame->size();

        try {
            $result = $frame->native()->scaleImage(
                (int) round(max(1, $size->width() / $this->size)),
                (int) round(max(1, $size->height() / $this->size))
            ) && $frame->native()->scaleImage(
                $size->width(),
                $size->height()
            );

            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to pixelate image',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to pixelate image',
                previous: $e
            );
        }
    }
}
