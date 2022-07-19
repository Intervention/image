<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickDraw;
use Intervention\Image\Drivers\Abstract\AbstractFont;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;

class Font extends AbstractFont
{
    public function toImagickDraw(): ImagickDraw
    {
        if (!$this->hasFilename()) {
            throw new FontException('No font file specified.');
        }

        $draw = new ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFont($this->getFilename());
        $draw->setFontSize($this->getSize());
        $draw->setFillColor($this->getColor()->getPixel());
        $draw->setTextAlignment(Imagick::ALIGN_LEFT);

        return $draw;
    }

    public function getColor(): ?ColorInterface
    {
        $color = parent::getColor();

        if (!is_a($color, Color::class)) {
            throw new FontException('Font is not compatible to current driver.');
        }

        return $color;
    }

    /**
     * Calculate box size of current font
     *
     * @return Polygon
     */
    public function getBoxSize(string $text): Polygon
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
}
