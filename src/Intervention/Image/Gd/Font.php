<?php

namespace Intervention\Image\Gd;

use \Intervention\Image\Image;
use \Intervention\Image\Size;

class Font extends \Intervention\Image\AbstractFont
{
    /**
     * Get font size in points
     *
     * @return integer
     */
    protected function getPointSize()
    {
        return intval(ceil($this->size * 0.75));
    }

    public function applyToImage(Image $image, $posx = 0, $posy = 0)
    {
        // box size
        $box = $this->getBoxSize();

        // draw box (debug)
        // $image->rectangle($posx, $posy, $posx + $box->getWidth(), $posy + $box->getHeight(), function ($draw) {
        //     $draw->border(1, '555');
        // });
        
        // create empty resource
        $canvas = imagecreatetruecolor($box->getWidth(), $box->getHeight());

        // set background color (2147483647)
        imagefill($canvas, 0, 0, 2147483647);

        // parse text color
        $color = new Color($this->color);

        if ($this->hasApplicableFontFile()) {

            // enable alphablending for imagettftext
            imagealphablending($image->getCore(), true);

            // draw ttf text
            imagettftext(
                $canvas,
                $this->getPointSize(), // size
                0, // angle
                0, // x 
                49, // y
                $color->getInt(),
                $this->file,
                $this->text
            );

            if ($this->angle != 0) {
                $canvas = imagerotate($canvas, $this->angle, 2147483647);
                $box->rotate($this->angle);
            }
            
            // insert canvas
            imagecopy(
                $image->getCore(), 
                $canvas, 
                $posx - $box->pivot->x,
                $posy - $box->pivot->y, 
                0, 
                0, 
                imagesx($canvas), 
                imagesy($canvas)
            );

        }
    }

    public function getBoxSize($text = null)
    {
        $text = is_null($text) ? $this->text : $text;

        if ($this->hasApplicableFontFile()) {

            // get bounding box with angle 0
            $box = imagettfbbox($this->getPointSize(), 0, $this->file, $this->text);

            // rotate points manually
            if ($this->angle != 0) {
                /*
                $angle = pi() * 2 - $this->angle * pi() * 2 / 360;

                for ($i=0; $i<4; $i++) {
                    $x = $box[$i * 2];
                    $y = $box[$i * 2 + 1];
                    $box[$i * 2] = cos($angle) * $x - sin($angle) * $y;
                    $box[$i * 2 + 1] = sin($angle) * $x + cos($angle) * $y;
                }
                */
            }

            $width = intval(abs($box[4] - $box[0]));
            $height = intval(abs($box[5] - $box[1]));

        } else {

            // get current internal font size
            $w = $this->getInternalFontWidth();
            $h = $this->getInternalFontHeight();

            if (strlen($this->text) == 0) {
                // no text -> no boxsize
                $width = 0;
                $height = 0;
            } else {
                // calculate boxsize
                $width = strlen($this->text) * $w;
                $height = $h;
            }
        }

        return new Size($width, $height);
    }
}
