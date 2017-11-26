<?php

namespace Intervention\Image\Gd;

use Intervention\Image\AbstractFont;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Image;

class Font extends AbstractFont
{
    /**
     * Get font size in points
     *
     * @return integer
     */
    protected function getPointSize()
    {
        return $this->size;
    }

    /**
     * Filter function to access internal integer font values
     *
     * @return integer
     */
    private function getInternalFont()
    {
        $internalfont = is_null($this->file) ? 1 : $this->file;
        $internalfont = is_numeric($internalfont) ? $internalfont : false;

        if (!in_array($internalfont, [1, 2, 3, 4, 5])) {
            throw new NotSupportedException(
                sprintf('Internal GD font (%s) not available. Use only 1-5.', $internalfont)
            );
        }

        return (int)$internalfont;
    }

    /**
     * Get width of an internal font character
     *
     * @return integer
     */
    private function getInternalFontWidth()
    {
        return $this->getInternalFont() + 4;
    }

    /**
     * Get height of an internal font character
     *
     * @return integer
     */
    private function getInternalFontHeight()
    {
        switch ($this->getInternalFont()) {
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

    /**
     * Calculates bounding box of current font setting
     *
     * @return array
     */
    public function getBoxSize()
    {
        $box = [];

        if ($this->hasApplicableFontFile()) {

            // get bounding box with angle 0
            $box = imagettfbbox($this->getPointSize(), 0, $this->file, $this->text);

            // rotate points manually
            if ($this->angle != 0) {

                $angle = pi() * 2 - $this->angle * pi() * 2 / 360;

                for ($i=0; $i<4; $i++) {
                    $x = $box[$i * 2];
                    $y = $box[$i * 2 + 1];
                    $box[$i * 2] = cos($angle) * $x - sin($angle) * $y;
                    $box[$i * 2 + 1] = sin($angle) * $x + cos($angle) * $y;
                }
            }

            $box['width'] = (int)abs($box[4] - $box[0]);
            $box['height'] = (int)abs($box[5] - $box[1]);

        } else {

            // get current internal font size
            $width = $this->getInternalFontWidth();
            $height = $this->getInternalFontHeight();

            if (strlen($this->text) == 0) {
                // no text -> no boxsize
                $box['width'] = 0;
                $box['height'] = 0;
            } else {
                // calculate boxsize
                $box['width'] = strlen($this->text) * $width;
                $box['height'] = $height;
            }
        }

        return $box;
    }

    /**
     * Draws font to given image at given position
     *
     * @param  Image   $image
     * @param  integer $posx
     * @param  integer $posy
     * @return void
     */
    public function applyToImage(Image $image, $posx = 0, $posy = 0)
    {
        // parse text color
        $color = new Color($this->color);

        if ($this->hasApplicableFontFile()) {

            if ($this->angle != 0 || is_string($this->align) || is_string($this->valign)) {

                $box = $this->getBoxSize();

                $align = is_null($this->align) ? 'left' : strtolower($this->align);
                $valign = is_null($this->valign) ? 'bottom' : strtolower($this->valign);

                // correction on position depending on v/h alignment
                switch ($align.'-'.$valign) {

                    case 'center-top':
                        $posx = $posx - round(($box[6]+$box[4])/2);
                        $posy = $posy - round(($box[7]+$box[5])/2);
                        break;

                    case 'right-top':
                        $posx = $posx - $box[4];
                        $posy = $posy - $box[5];
                        break;

                    case 'left-top':
                        $posx = $posx - $box[6];
                        $posy = $posy - $box[7];
                        break;

                    case 'center-center':
                    case 'center-middle':
                        $posx = $posx - round(($box[0]+$box[4])/2);
                        $posy = $posy - round(($box[1]+$box[5])/2);
                        break;

                    case 'right-center':
                    case 'right-middle':
                        $posx = $posx - round(($box[2]+$box[4])/2);
                        $posy = $posy - round(($box[3]+$box[5])/2);
                        break;

                    case 'left-center':
                    case 'left-middle':
                        $posx = $posx - round(($box[0]+$box[6])/2);
                        $posy = $posy - round(($box[1]+$box[7])/2);
                        break;

                    case 'center-bottom':
                        $posx = $posx - round(($box[0]+$box[2])/2);
                        $posy = $posy - round(($box[1]+$box[3])/2);
                        break;

                    case 'right-bottom':
                        $posx = $posx - $box[2];
                        $posy = $posy - $box[3];
                        break;

                    case 'left-bottom':
                        $posx = $posx - $box[0];
                        $posy = $posy - $box[1];
                        break;
                }
            }

            // enable alphablending for imagettftext
            if ($this->strokeWidth > 0) {
                $strokeColor = new Color($this->strokeColor);
                $this->strokeDrawLoop($posx, $posy, function($posX, $posY) use ($image, $strokeColor) {
                    imagettftext(
                        $image->getCore(),
                        $this->getPointSize(),
                        $this->angle,
                        $posX,
                        $posY,
                        $strokeColor->getInt(),
                        $this->file,
                        $this->text
                    );
                });
            }

            // draw ttf text
            imagettftext(
                $image->getCore(),
                $this->getPointSize(),
                $this->angle,
                $posx,
                $posy,
                $color->getInt(),
                $this->file,
                $this->text
            );

        } else {

            // get box size
            $box = $this->getBoxSize();
            $width = $box['width'];
            $height = $box['height'];

            // internal font specific position corrections
            if ($this->getInternalFont() == 1) {
                $top_correction = 1;
                $bottom_correction = 2;
            } elseif ($this->getInternalFont() == 3) {
                $top_correction = 2;
                $bottom_correction = 4;
            } else {
                $top_correction = 3;
                $bottom_correction = 4;
            }

            // x-position corrections for horizontal alignment
            switch (strtolower($this->align)) {
                case 'center':
                    $posx = ceil($posx - ($width / 2));
                    break;

                case 'right':
                    $posx = ceil($posx - $width) + 1;
                    break;
            }

            // y-position corrections for vertical alignment
            switch (strtolower($this->valign)) {
                case 'center':
                case 'middle':
                    $posy = ceil($posy - ($height / 2));
                    break;

                case 'top':
                    $posy = ceil($posy - $top_correction);
                    break;

                default:
                case 'bottom':
                    $posy = round($posy - $height + $bottom_correction);
                    break;
            }

            $font = $this->getInternalFont();
            if ($this->strokeWidth > 0) {
                $strokeColor = new Color($this->strokeColor);
                $this->strokeDrawLoop($posx, $posy, function($posX, $posY) use ($image, $font, $strokeColor) {
                    imagestring($image->getCore(), $font, $posX, $posY, $this->text, $strokeColor->getInt());
                });
            }

            // draw text
            imagestring($image->getCore(), $font, $posx, $posy, $this->text, $color->getInt());
        }
    }
}
