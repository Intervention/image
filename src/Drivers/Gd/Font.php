<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract\AbstractFont;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;

class Font extends AbstractFont
{
    public function getSize(): float
    {
        return floatval(ceil(parent::getSize() * 0.75));
    }

    /**
     * Calculate size of bounding box of given text
     *
     * @return Polygon
     */
    public function getBoxSize(string $text): Polygon
    {
        if (!$this->hasFilename()) {
            // calculate box size from gd font
            $box = new Rectangle(0, 0);
            $chars = mb_strlen($text);
            if ($chars > 0) {
                $box->setWidth($chars * $this->getGdFontWidth());
                $box->setHeight($this->getGdFontHeight());
            }
            return $box;
        }

        // calculate box size from font file with angle 0
        $box = imageftbbox(
            $this->getSize(),
            0,
            $this->getFilename(),
            $text
        );

        // build polygon from points
        $polygon = new Polygon();
        $polygon->addPoint(new Point($box[6], $box[7]));
        $polygon->addPoint(new Point($box[4], $box[5]));
        $polygon->addPoint(new Point($box[2], $box[3]));
        $polygon->addPoint(new Point($box[0], $box[1]));

        return $polygon;
    }

    public function getGdFont(): int
    {
        if (is_numeric($this->filename)) {
            return $this->filename;
        }

        return 1;
    }

    protected function getGdFontWidth(): int
    {
        return $this->getGdFont() + 4;
    }

    protected function getGdFontHeight(): int
    {
        switch ($this->getGdFont()) {
            case 2:
                return 14;

            case 3:
                return 14;

            case 4:
                return 16;

            case 5:
                return 16;

            default:
            case 1:
                return 8;
        }
    }
}
