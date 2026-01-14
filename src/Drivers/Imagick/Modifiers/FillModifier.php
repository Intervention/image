<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FillModifier as ModifiersFillModifier;

class FillModifier extends ModifiersFillModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $pixel = $this->driver()->colorProcessor($image)->colorToNative(
            $this->driver()->handleColorInput($this->color)
        );

        foreach ($image->core()->native() as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $pixel);
            } else {
                $this->fillAllWithColor($frame, $pixel);
            }
        }

        return $image;
    }

    /**
     * @throws ModifierException
     */
    private function floodFillWithColor(Imagick $frame, ImagickPixel $pixel): void
    {
        try {
            $target = $frame->getImagePixelColor(
                $this->position->x(),
                $this->position->y()
            );
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to find target flood fill color',
                previous: $e
            );
        }

        try {
            $result = $frame->floodfillPaintImage(
                $pixel,
                100,
                $target,
                $this->position->x(),
                $this->position->y(),
                false,
                Imagick::CHANNEL_ALL
            );

            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to flood fill image',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to flood fill image',
                previous: $e
            );
        }
    }

    /**
     * @throws ModifierException
     */
    private function fillAllWithColor(Imagick $frame, ImagickPixel $pixel): void
    {
        try {
            $draw = new ImagickDraw();
            $draw->setFillColor($pixel);
            $draw->rectangle(0, 0, $frame->getImageWidth(), $frame->getImageHeight());
            $frame->drawImage($draw);
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e
            );
        }

        try {
            // deactive alpha channel when image was filled with opaque color
            if ($pixel->getColorValue(Imagick::COLOR_ALPHA) == 1) {
                $result = $frame->setImageAlphaChannel(Imagick::ALPHACHANNEL_DEACTIVATE);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to adjust alpha channel',
                    );
                }
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to adjust alpha channel',
                previous: $e
            );
        }
    }
}
