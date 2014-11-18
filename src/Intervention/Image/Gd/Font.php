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

    /**
     * Filter function to access internal integer font values
     *
     * @return integer
     */
    private function getInternalFont()
    {
        $internalfont = is_null($this->file) ? 1 : $this->file;
        $internalfont = is_numeric($internalfont) ? $internalfont : false;

        if ( ! in_array($internalfont, array(1, 2, 3, 4, 5))) {
            throw new \Intervention\Image\Exception\NotSupportedException(
                sprintf('Internal GD font (%s) not available. Use only 1-5.', $internalfont)
            );
        }

        return intval($internalfont);
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

    private function getInternalFontBaseline()
    {
        switch ($this->getInternalFont()) {
            case 1:
                return 6;

            case 2:
                return 10;

            case 3:
                return 10;

            case 4:
                return 12;

            case 5:
                return 12;
        }
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

        imagealphablending($canvas, true);

        // set background color transparent (2147483647 (1291845632))
        imagefill($canvas, 0, 0, 2147483647);

        // parse text color
        $color = new Color($this->color);

        $lines = $this->getLines($text);

        $baseline = $this->getCoreBoxSize($lines[0]);

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

            $linesize = $this->getCoreBoxSize(trim($line));
            $relative = $box->relativePosition($linesize->align($this->align));

            if ($this->hasApplicableFontFile()) {

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

            } else {

                // draw text
                imagestring(
                    $canvas,
                    $this->getInternalFont(),
                    self::PADDING + $relative->x, // x
                    self::PADDING + $ystart + $count * $this->lineHeight * $baseline->getHeight(), // y
                    trim($line),
                    $color->getInt()
                );

            }

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
            $baseline = $this->hasApplicableFontFile() ? $baseline->getHeight() : $this->getInternalFontBaseline();
            $box->pivot->moveY($baseline);
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

        // enable alphablending for imagecopy
        imagealphablending($image->getCore(), true);

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

    /**
     * Calculate boxsize including own features
     *
     * @param  string $text
     * @return \Intervention\Image\Size
     */
    public function getBoxSize($text = null)
    {
        $text = is_null($text) ? $this->text : $text;

        $lines = $this->getLines($text);
        $baseline = $this->getCoreBoxSize($lines[0]);

        $width_values = array();

        // cycle through each line
        foreach ($lines as $line) {
            $width_values[] = $this->getCoreBoxSize($line)->getWidth();
        }

        // maximal line width is box width
        $width = max($width_values);

        if ($this->hasApplicableFontFile()) {

            $height = $baseline->getHeight() + (count($lines) - 1) * $this->lineHeight * $this->size * 1.5;
            $height = $height + $baseline->getHeight() / 3;

        } else {
            
            $height = $baseline->getHeight() + (count($lines) - 1) * $this->lineHeight * $this->getInternalFontHeight();

        }

        return new Size($width, $height);
    }

    /**
     * Get raw boxsize without any non-core features
     *
     * @param  string $text
     * @return \Intervention\Image\Size
     */
    protected function getCoreBoxSize($text = null)
    {
        $text = is_null($text) ? $this->text : $text;

        if ($this->hasApplicableFontFile()) {
            
            // get boxsize
            $box = imagettfbbox($this->getPointSize(), 0, $this->file, $text);
            
            // calculate width/height
            $width = intval(abs($box[4] - $box[0]));
            $height = intval(abs($box[5] - $box[1]));

        } else {

            if (strlen($text) == 0) {
                // no text -> no boxsize
                $width = 0;
                $height = 0;
            } else {
                // calculate boxsize
                $width = strlen($text) * $this->getInternalFontWidth();
                $height = $this->getInternalFontHeight();
            }
        }

        return new Size($width, $height);   
    }
}
