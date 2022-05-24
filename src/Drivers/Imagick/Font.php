<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickDraw;
use Intervention\Image\Drivers\Abstract\AbstractFont;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Size;

class Font extends AbstractFont
{
    public function toImagickDraw(): ImagickDraw
    {
        if (!$this->hasFilename()) {
            throw new FontException('No font file.');
        }

        $draw = new ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFont($this->getFilename());
        $draw->setFontSize($this->getSize());
        $draw->setFillColor($this->getColor()->getPixel());
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
                break;

            case 'right':
                return Imagick::ALIGN_RIGHT;
                break;
        }

        return Imagick::ALIGN_LEFT;
    }

    /**
     * Calculate box size of current font
     *
     * @return Size
     */
    public function getBoxSize(): Size
    {
        $foo = null;
        // no text - no box size
        if (mb_strlen($this->getText()) === 0) {
            return new Size(0, 0);
        }

        $dimensions = (new Imagick())->queryFontMetrics(
            $this->toImagickDraw(),
            $this->getText()
        );

        return new Size(
            intval(abs($dimensions['textWidth'])),
            intval(abs($dimensions['textHeight']))
        );
    }
}
