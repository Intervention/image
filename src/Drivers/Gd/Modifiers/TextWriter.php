<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $position = $this->getAlignedPosition();
        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                imagettftext(
                    $frame->getCore(),
                    $this->getFont()->getSize(),
                    $this->getFont()->getAngle() * (-1),
                    $position->getX(),
                    $position->getY(),
                    $this->getFont()->getColor()->toInt(),
                    $this->getFont()->getFilename(),
                    $this->text
                );
            } else {
                imagestring(
                    $frame->getCore(),
                    $this->getFont()->getGdFont(),
                    $position->getX(),
                    $position->getY(),
                    $this->text,
                    $this->font->getColor()->toInt()
                );
            }
        }

        return $image;
    }

    private function getAlignedPosition(): Point
    {
        $poly = $this->font->getBoxSize($this->text);
        $poly->setPivotPoint($this->position);

        $poly->align($this->font->getAlign());
        $poly->valign($this->font->getValign());

        if ($this->font->getAngle() != 0) {
            $poly->rotate($this->font->getAngle());
        }

        return $poly->last();
    }

    private function getFont(): Font
    {
        if (!is_a($this->font, Font::class)) {
            throw new FontException('Font is not compatible to current driver.');
        }
        return $this->font;
    }
}
