<?php

namespace Intervention\Image\Gd;

use \Intervention\Image\Image;
use \Intervention\Image\Size;

class Font extends \Intervention\Image\AbstractFont
{
    const PADDING = 10;

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
        // format text
        $text = $this->format();

        // box size
        $box = $this->isBoxed() ? $this->box : $this->getBoxSize($text);

        // create empty resource
        $canvas = imagecreatetruecolor(
            $box->getWidth() + self::PADDING * 2,
            $box->getHeight() + self::PADDING * 2
        );

        // set background color transparent (2147483647 (1291845632))
        imagefill($canvas, 0, 0, 2147483647);

        // parse text color
        $color = new Color($this->color);

        $lines = $this->getLines($text);

        if ($this->hasApplicableFontFile()) {

            // enable alphablending for imagettftext
            imagealphablending($image->getCore(), true);

            $baseline = $this->getGdBoxSize($lines[0]);

            $box->align(sprintf('%s-%s', $this->align, 'top'));

            $ystart = 0;

            if ($this->isBoxed()) {
                switch (strtolower($this->valign)) {
                    case 'bottom':
                        $ystart = $box->getHeight() - $this->getBoxSize($text)->getHeight();
                        break;
                    
                    case 'center':
                    case 'middle':
                        $ystart = ($box->getHeight() - $this->getBoxSize($text)->getHeight()) / 2;
                        break;
                }
            }

            // write line by line
            foreach ($lines as $count => $line) {

                $linesize = $this->getGdBoxSize($line);
                $relative = $box->relativePosition($linesize->align($this->align));

                // draw ttf text
                imagettftext(
                    $canvas,
                    $this->getPointSize(), // size
                    0, // angle
                    self::PADDING + $relative->x, // x 
                    self::PADDING + $ystart + $baseline->getHeight() + $count * $this->lineHeight * $this->size * 1.5, // y
                    $color->getInt(),
                    $this->file,
                    $line
                );
            }

            // valign
            switch (strtolower($this->valign)) {
                case 'top':
                    # nothing to do...
                    break;

                case 'center':
                case 'middle':
                    $box->pivot->moveY($box->getHeight() / 2);
                    break;

                case 'bottom':
                    $box->pivot->moveY($box->getHeight());
                    break;
                
                default:
                case 'baseline':
                    $box->pivot->moveY($baseline->getHeight());
                    break;
            }

            if ($this->isBoxed()) {
                $box->align('top-left');
            }

            // rotate canvas
            if ($this->angle != 0) {
                $canvas = imagerotate($canvas, $this->angle, 2147483647);
                $box->rotate($this->angle);
            }

            // insert canvas
            imagecopy(
                $image->getCore(), 
                $canvas, 
                $posx - $box->pivot->x - self::PADDING,
                $posy - $box->pivot->y - self::PADDING, 
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

            $lines = $this->getLines($text);
            $baseline = $this->getGdBoxSize($lines[0]);

            $width_values = array();

            // cycle through each line
            foreach ($lines as $line) {
                $width_values[] = $this->getGdBoxSize($line)->getWidth();
            }

            $width = max($width_values);

            $height = $baseline->getHeight() + (count($lines) - 1) * $this->lineHeight * $this->size * 1.5;
            $height = $height + $baseline->getHeight() / 3;

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

    private function getGdBoxSize($text = null)
    {
        $text = is_null($text) ? $this->text : $text;

        // get boxsize
        $box = imagettfbbox($this->getPointSize(), 0, $this->file, $text);
            
        // calculate width/height
        $width = intval(abs($box[4] - $box[0]));
        $height = intval(abs($box[5] - $box[1]));

        return new Size($width, $height);   
    }

    private function format()
    {
        if ($this->isBoxed()) {

            $line = array();
            $lines = array();

            foreach ($this->getWords() as $word) {
                
                $linesize = $this->getGdBoxSize(
                    implode(' ', array_merge($line, array($word)))
                );

                if ($linesize->getWidth() <= $this->box->getWidth()) {
                    $line[] = $word;
                } else {
                    $lines[] = implode(' ', $line);
                    $line = array($word);
                }
            }

            $lines[] = implode(' ', $line);

            return implode(PHP_EOL, $lines);
        }
    }
}
