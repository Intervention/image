<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawEllipseModifier as GenericDrawEllipseModifier;

class DrawEllipseModifier extends GenericDrawEllipseModifier implements SpecializedInterface
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
        $backgroundColor = $this->driver()->colorProcessor($image)->export($this->backgroundColor());
        $borderColor = $this->driver()->colorProcessor($image)->export($this->borderColor());

        foreach ($image as $frame) {
            $this->drawEllipse($frame->native(), $backgroundColor, $borderColor);
        }

        return $image;
    }

    /**
     * Draw ellipse in given colors.
     *
     * @throws ModifierException
     */
    private function drawEllipse(GdImage $canvas, int $backgroundColor, int $borderColor): void
    {
        imagealphablending($canvas, true);

        // draw background
        if ($this->drawable->hasBackgroundColor()) {
            $this->abortUnless(imagesetthickness($canvas, 0), 'Unable to set line thickness');
            $this->abortUnless(
                imagefilledellipse(
                    $canvas,
                    $this->drawable->position()->x(),
                    $this->drawable->position()->y(),
                    // slightly smaller ellipse to keep 1px bordered edges clean
                    $this->drawable->hasBorder() ? $this->drawable->width() - 1 : $this->drawable->width(),
                    $this->drawable->hasBorder() ? $this->drawable->height() - 1 : $this->drawable->height(),
                    $backgroundColor,
                ),
                'Unable to draw ellipse',
            );
        }

        // draw border
        if ($this->drawable()->hasBorder()) {
            // gd's imageellipse ignores imagesetthickness
            // so i use imagearc with 360 degrees instead.
            $this->abortUnless(
                imagesetthickness($canvas, $this->drawable->borderSize()),
                'Unable to set line thickness'
            );
            $this->abortUnless(
                imagearc(
                    $canvas,
                    $this->drawable->position()->x(),
                    $this->drawable->position()->y(),
                    $this->drawable->width(),
                    $this->drawable->height(),
                    0,
                    360,
                    $borderColor,
                ),
                'Unable to draw ellipse border',
            );
        }
    }
}
