<?php

namespace Intervention\Image\Imagick;

use \Intervention\Image\Image;
use \Intervention\Image\Size;

class Font extends \Intervention\Image\AbstractFont
{
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
        $draw = new \ImagickDraw();

        if ($this->hasApplicableFontFile()) {
            $draw->setStrokeAntialias(true);
            $draw->setTextAntialias(true);
                
            // font file
            $draw->setFont($this->file);

            // font size
            $draw->setFontSize($this->size);

            // parse text color
            $color = new Color($this->color);
            $draw->setFillColor($color->getPixel());

        } else {
            throw new \Intervention\Image\Exception\RuntimeException(
                "Font file must be provided to apply text to image."
            );
        }

        // format text
        $text = $this->format();

        // box size
        $box = $this->isBoxed() ? $this->box : $this->getBoxSize($text);

        // create empty canvas
        $canvas = $image->getDriver()->newImage(
            $box->getWidth() + self::PADDING * 2,
            $box->getHeight() + self::PADDING * 2
        )->getCore();

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

            // write line of text
            $canvas->annotateImage(
                $draw, 
                self::PADDING + $relative->x, // x
                self::PADDING + $ystart + $baseline->getHeight() + $count * $this->lineHeight * $this->size * 1.5, // y
                0, // angle
                trim($line)
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
            $canvas->rotateImage(new \ImagickPixel('none'), ($this->angle * -1));
            $box->rotate($this->angle);
        }

        // insert canvas
        $image->getCore()->compositeImage(
            $canvas,
            \Imagick::COMPOSITE_DEFAULT,
            $posx - $box->pivot->x - self::PADDING,
            $posy - $box->pivot->y - self::PADDING
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

        // calculate height
        $height = $baseline->getHeight() + (count($lines) - 1) * $this->lineHeight * $this->size * 1.5;
        $height = $height + $baseline->getHeight() / 3;

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

        $imagick = new \Imagick();
        $draw = new \ImagickDraw();

        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFontSize($this->size);
        $draw->setFont($this->file);

        // get boxsize
        $size = $imagick->queryFontMetrics($draw, $text);

        return new Size($size['textWidth'], $size['textHeight']);
    }
}
