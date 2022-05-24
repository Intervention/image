<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract\AbstractFont;
use Intervention\Image\Geometry\Size;

class Font extends AbstractFont
{
    public function getSize(): float
    {
        return floatval(ceil(parent::getSize() * 0.75));
    }

    /**
     * Calculate size of bounding box of current text
     *
     * @return Size
     */
    public function getBoxSize(): Size
    {
        if (!$this->hasFilename()) {
            // calculate box size from gd font
            $box = new Size(0, 0);
            $chars = mb_strlen($this->getText());
            if ($chars > 0) {
                $box->setWidth($chars * $this->getGdFontWidth());
                $box->setHeight($this->getGdFontHeight());
            }
            return $box;
        }

        // calculate box size from font file
        $box = imageftbbox(
            $this->getSize(),
            $this->getAngle(),
            $this->getFilename(),
            $this->getText()
        );

        return new Size(abs($box[0] - $box[2]), abs($box[1] - $box[7]));
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
            case 1:
                return 8;

            case 2:
                return 14;

            case 3:
                return 14;

            case 4:
                return 16;

            case 5:
                return 16;
        }
    }
}
