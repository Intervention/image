<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Traits\CanDraw;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPixelModifier as GenericDrawPixelModifier;

class DrawPixelModifier extends GenericDrawPixelModifier implements SpecializedInterface
{
    use CanDraw;

    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $pixel = $this->pixel(
            $this->driver()->colorProcessor($image)->export($this->color()),
        );

        foreach ($image as $frame) {
            $this->draw($frame->native(), $pixel);
        }

        return $image;
    }

    /**
     * Build drawable pixel in given color.
     *
     * @throws ModifierException
     */
    private function pixel(ImagickPixel $color): ImagickDraw
    {
        try {
            $pixel = new ImagickDraw();
            $pixel->setFillColor($color);
            $pixel->point($this->position->x(), $this->position->y());

            return $pixel;
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e,
            );
        }
    }
}
