<?php

namespace Intervention\Image\Gd;

use \Intervention\Image\Image;

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
        // create empty resource
        $canvas = imagecreatetruecolor(400, 400);

        // set background color (2147483647)
        imagefill($canvas, 0, 0, 3242424);

        // parse text color
        $color = new Color($this->color);

        if ($this->hasApplicableFontFile()) {

            // enable alphablending for imagettftext
            imagealphablending($image->getCore(), true);

            // draw ttf text
            imagettftext(
                $canvas,
                $this->getPointSize(),
                0,
                16,
                16,
                $color->getInt(),
                $this->file,
                $this->text
            );

            if ($this->angle != 0) {
                $canvas = imagerotate($canvas, $this->angle, 45454);
            }
            

            // insert canvas
            imagecopy(
                $image->getCore(), 
                $canvas, 
                $posx, 
                $posy, 
                0, 
                0, 
                400, 
                400
            );

        }
    }

    public function getBoxSize($text = null)
    {
        $text = is_null($text) ? $this->text : $text;
    }
}
