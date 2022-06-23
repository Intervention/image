<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class TextWriter implements ModifierInterface
{
    public function __construct(
        protected Point $position,
        protected Font $font
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $position = $this->getAlignedPosition();
        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                imagettftext(
                    $frame->getCore(),
                    $this->font->getSize(),
                    $this->font->getAngle() * (-1),
                    $position->getX(),
                    $position->getY(),
                    $this->font->getColor()->toInt(),
                    $this->font->getFilename(),
                    $this->font->getText()
                );
            } else {
                imagestring(
                    $frame->getCore(),
                    $this->font->getGdFont(),
                    $position->getX(),
                    $position->getY(),
                    $this->font->getText(),
                    $this->font->getColor()->toInt()
                );
            }
        }

        return $image;
    }

    public function getAlignedPosition(): Point
    {
        $poly = $this->font->getBoxSize();
        $poly->setPivotPoint($this->position);

        $poly->align($this->font->getAlign());
        $poly->valign($this->font->getValign());

        if ($this->font->getAngle() != 0) {
            $poly->rotate($this->font->getAngle());
        }

        return $poly->last();
    }
}
