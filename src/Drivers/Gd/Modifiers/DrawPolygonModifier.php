<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPolygonModifier as GenericDrawPolygonModifier;

class DrawPolygonModifier extends GenericDrawPolygonModifier implements SpecializedInterface
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
        foreach ($image as $frame) {
            if ($this->drawable->hasBackgroundColor()) {
                $color = $this->driver()->colorProcessor($image)->export($this->backgroundColor());
                $this->drawPolygonBackground($frame->native(), $color);
            }

            if ($this->drawable->hasBorder()) {
                $borderColor = $this->driver()->colorProcessor($image)->export($this->borderColor());
                $this->drawPolygonBorder($frame->native(), $borderColor);
            }
        }

        return $image;
    }

    /**
     * Draw polygon background in given color.
     *
     * @throws ModifierException
     */
    private function drawPolygonBackground(GdImage $canvas, int $color): void
    {
        imagealphablending($canvas, true);
        imagesetthickness($canvas, 0);
        $this->abortUnless(
            imagefilledpolygon(
                $canvas,
                $this->drawable->toArray(),
                $color,
            ),
            'Unable to draw polygon background',
        );
    }

    /**
     * Draw polygon border in given color.
     *
     * @throws ModifierException
     */
    private function drawPolygonBorder(GdImage $canvas, int $borderColor): void
    {
        imagealphablending($canvas, true);
        imagesetthickness($canvas, $this->drawable->borderSize());
        $this->abortUnless(
            imagepolygon(
                $canvas,
                $this->drawable->toArray(),
                $borderColor,
            ),
            'Unable to draw polygon border',
        );
    }
}
