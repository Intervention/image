<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPixelModifier as GenericDrawPixelModifier;

class DrawPixelModifier extends GenericDrawPixelModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorProcessor($image)->export($this->color());

        foreach ($image as $frame) {
            $this->drawPixel($frame->native(), $color);
        }

        return $image;
    }

    /**
     * Draw pixel in given color at current position.
     *
     * @throws ModifierException
     */
    private function drawPixel(GdImage $canvas, int $color): void
    {
        $result = imagealphablending($canvas, true) && imagesetpixel(
            $canvas,
            $this->position->x(),
            $this->position->y(),
            $color
        );

        if ($result === false) {
            throw new ModifierException('Failed to apply ' . self::class . ', unable to draw pixel');
        }
    }
}
