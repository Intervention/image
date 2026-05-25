<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawRectangleModifier as GenericDrawRectangleModifier;

class DrawRectangleModifier extends GenericDrawRectangleModifier implements SpecializedInterface
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
        $position = $this->drawable->position();
        $backgroundColor = $this->driver()->colorProcessor($image)->export($this->backgroundColor());
        $borderColor = $this->driver()->colorProcessor($image)->export($this->borderColor());

        foreach ($image as $frame) {
            if ($this->drawable->hasBackgroundColor()) {
                $this->drawRectangleBackground($frame->native(), $position, $backgroundColor);
            }

            if ($this->drawable->hasBorder()) {
                $this->drawRectangleBorder($frame->native(), $position, $borderColor);
            }
        }

        return $image;
    }

    /**
     * Draw background of rectangle.
     *
     * @throws ModifierException
     */
    private function drawRectangleBackground(GdImage $canvas, PointInterface $position, int $backgroundColor): void
    {
        imagealphablending($canvas, true);
        imagesetthickness($canvas, 0);
        imagefilledrectangle(
            $canvas,
            $position->x(),
            $position->y(),
            $position->x() + $this->drawable->width(),
            $position->y() + $this->drawable->height(),
            $backgroundColor,
        );
    }

    /**
     * Draw border of rectangle.
     *
     * @throws ModifierException
     */
    private function drawRectangleBorder(GdImage $canvas, PointInterface $position, int $borderColor): void
    {
        imagealphablending($canvas, true);
        imagesetthickness($canvas, $this->drawable->borderSize());
        imagerectangle(
            $canvas,
            $position->x(),
            $position->y(),
            $position->x() + $this->drawable->width(),
            $position->y() + $this->drawable->height(),
            $borderColor,
        );
    }
}
