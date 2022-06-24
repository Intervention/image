<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickDraw;
use Intervention\Image\Drivers\Abstract\AbstractFont;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Size;

class Font extends AbstractFont
{
    public function toImagickDraw(): ImagickDraw
    {
        if (!$this->hasFilename()) {
            throw new FontException('No font file specified.');
        }

        $color = $this->getColor();
        if (!is_a($color, Color::class)) {
            throw new DecoderException('Unable to decode font color.');
        }

        $draw = new ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFont($this->getFilename());
        $draw->setFontSize($this->getSize());
        $draw->setFillColor($color->getPixel());
        $draw->setTextAlignment($this->getImagickAlign());

        return $draw;
    }

    public function getAngle(): float
    {
        return parent::getAngle() * (-1);
    }

    public function getImagickAlign(): int
    {
        switch (strtolower($this->getAlign())) {
            case 'center':
                return Imagick::ALIGN_CENTER;

            case 'right':
                return Imagick::ALIGN_RIGHT;
        }

        return Imagick::ALIGN_LEFT;
    }

    /**
     * Calculate box size of current font
     *
     * @return Polygon
     */
    public function getBoxSize(): Polygon
    {
        // no text - no box size
        if (mb_strlen($this->getText()) === 0) {
            return (new Size(0, 0))->toPolygon();
        }

        $dimensions = (new Imagick())->queryFontMetrics(
            $this->toImagickDraw(),
            $this->getText()
        );

        return (new Size(
            intval(round(abs($dimensions['boundingBox']['x1'] - $dimensions['boundingBox']['x2']))),
            intval(round(abs($dimensions['boundingBox']['y1'] - $dimensions['boundingBox']['y2']))),
        ))->toPolygon();
    }
}
