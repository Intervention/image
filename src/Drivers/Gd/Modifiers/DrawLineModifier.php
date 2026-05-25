<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use GdImage;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawLineModifier as GenericDrawLineModifier;

class DrawLineModifier extends GenericDrawLineModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if (!$this->drawable->hasBackgroundColor()) {
            return $image;
        }

        $color = $this->driver()->colorProcessor($image)->export($this->backgroundColor());

        foreach ($image as $frame) {
            $this->drawLine($frame->native(), $color);
        }

        return $image;
    }

    /**
     * Draw current line on given canvas.
     *
     * @throws ModifierException
     */
    private function drawLine(GdImage $canvas, int $color): void
    {
        $this->abortUnless(imagealphablending($canvas, true), 'Unable to set alpha blending');
        $this->abortUnless(imageantialias($canvas, true), 'Unable to enable antialiasing');
        $this->abortUnless(
            imagesetthickness($canvas, $this->drawable->width()),
            'Unable to set line thickness',
        );
        $this->abortUnless(
            imageline(
                $canvas,
                $this->drawable->start()->x(),
                $this->drawable->start()->y(),
                $this->drawable->end()->x(),
                $this->drawable->end()->y(),
                $color
            ),
            'Unable to draw line'
        );
    }
}
