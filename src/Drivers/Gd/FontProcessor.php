<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;

class FontProcessor extends AbstractFontProcessor
{
    /**
     * Calculate size of bounding box of given text
     *
     * @return Polygon
     */
    public function boxSize(string $text): Polygon
    {
        if (!$this->font->hasFilename()) {
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
            $this->adjustedSize(),
            0,
            $this->font->filename(),
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

    public function adjustedSize(): int
    {
        return floatval(ceil($this->font->size() * .75));
    }

    public function getGdFont(): int
    {
        if (is_numeric($this->font->filename())) {
            return $this->font->filename();
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
