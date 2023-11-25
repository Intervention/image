<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;

class FontProcessor extends AbstractFontProcessor
{
    /**
     * Calculate box size of current font
     *
     * @return Polygon
     */
    public function boxSize(string $text): Polygon
    {
        // no text - no box size
        if (mb_strlen($text) === 0) {
            return (new Rectangle(0, 0));
        }

        $draw = $this->toImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $dimensions = (new Imagick())->queryFontMetrics($draw, $text);

        return (new Rectangle(
            intval(round($dimensions['textWidth'])),
            intval(round($dimensions['ascender'] + $dimensions['descender'])),
        ));
    }

    public function toImagickDraw(?ImagickPixel $color = null): ImagickDraw
    {
        if (!$this->font->hasFilename()) {
            throw new FontException('No font file specified.');
        }

        $draw = new ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFont($this->font->filename());
        $draw->setFontSize($this->font->size());
        $draw->setTextAlignment(Imagick::ALIGN_LEFT);

        if ($color) {
            $draw->setFillColor($color);
        }

        return $draw;
    }
}
