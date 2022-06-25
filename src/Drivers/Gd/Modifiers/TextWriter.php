<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $box = $this->getBoundingBox();
        $position = clone $box->last();
        $leading = $this->getFont()->leadingInPixels();

        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                $position->moveY($this->getFont()->capHeight());
                $posx = $position->getX();
                $posy = $position->getY();
                foreach ($this->getTextBlock() as $line) {
                    imagettftext(
                        $frame->getCore(),
                        $this->getFont()->getSize(),
                        $this->getFont()->getAngle() * (-1),
                        $posx,
                        $posy,
                        $this->getFont()->getColor()->toInt(),
                        $this->getFont()->getFilename(),
                        $line
                    );
                    $posy += $leading;
                }

                // debug
                imagepolygon($frame->getCore(), $box->toArray(), 0);
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

    private function getBoundingBox(): Polygon
    {
        $size = new Size(
            $this->getTextBlock()->longestLine()->width($this->font),
            $this->getFont()->leadingInPixels() * $this->getTextBlock()->count()
        );

        $poly = $size->toPolygon();
        $poly->setPivotPoint($this->position);
        $poly->align($this->getFont()->getAlign());
        $poly->valign($this->getFont()->getValign());

        return $poly;
    }

    // private function getAlignedPosition(): Point
    // {
    //     $poly = $this->font->getBoxSize($this->text);
    //     $poly->setPivotPoint($this->position);
    //
    //     $poly->align($this->font->getAlign());
    //     $poly->valign($this->font->getValign());
    //
    //     if ($this->font->getAngle() != 0) {
    //         $poly->rotate($this->font->getAngle());
    //     }
    //
    //     return $poly->last();
    // }

    private function getFont(): Font
    {
        if (!is_a($this->font, Font::class)) {
            throw new FontException('Font is not compatible to current driver.');
        }
        return $this->font;
    }
}
