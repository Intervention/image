<?php

namespace Intervention\Image\Imagick;

use Intervention\Image\AbstractFont;
use Intervention\Image\Exception\RuntimeException;
use Intervention\Image\Image;

class Font extends AbstractFont
{
    public $isUseBuiltInImagickStroke = false;

    /**
     * Draws font to given image at given position
     *
     * @param  Image   $image
     * @param  int     $posx
     * @param  int     $posy
     * @return void
     */
    public function applyToImage(Image $image, $posx = 0, $posy = 0)
    {
        // build draw object
        $draw = new \ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);

        // set font file
        if ($this->hasApplicableFontFile()) {
            $draw->setFont($this->file);
        } else {
            throw new RuntimeException(
                "Font file must be provided to apply text to image."
            );
        }

        // parse text color
        $color = new Color($this->color);

        $draw->setFontSize($this->size);
        $draw->setFillColor($color->getPixel());

        // align horizontal
        switch (strtolower($this->align)) {
            case 'center':
                $align = \Imagick::ALIGN_CENTER;
                break;

            case 'right':
                $align = \Imagick::ALIGN_RIGHT;
                break;

            default:
                $align = \Imagick::ALIGN_LEFT;
                break;
        }

        $draw->setTextAlignment($align);

        // align vertical
        if (strtolower($this->valign) != 'bottom') {

            // calculate box size
            $dimensions = $image->getCore()->queryFontMetrics($draw, $this->text);

            // corrections on y-position
            switch (strtolower($this->valign)) {
                case 'center':
                case 'middle':
                $posy = $posy + $dimensions['textHeight'] * 0.65 / 2;
                break;

                case 'top':
                $posy = $posy + $dimensions['textHeight'] * 0.65;
                break;
            }
        }

        if ($this->strokeWidth > 0) {
            $strokeColor = new Color($this->strokeColor);
            if ($this->isUseBuiltInImagickStroke) {
                $draw->setStrokeWidth($this->strokeWidth);
                $draw->setStrokeColor($strokeColor->getPixel());
            } else {
                $originalFillColor = $draw->getFillColor();
                $draw->setFillColor($strokeColor->getPixel());
                $this->strokeDrawLoop($posx, $posy, function($posX, $posY) use ($image, $draw) {
                    $image->getCore()->annotateImage($draw, $posX, $posY, $this->angle * (-1), $this->text);
                });

                $draw->setFillColor($originalFillColor);
            }
        }

        // apply to image
        $image->getCore()->annotateImage($draw, $posx, $posy, $this->angle * (-1), $this->text);

        if (isset($this->textShape) && $this->textShape != '') {
            $distortion = $this->getDistortion();
            $distortion->distort($image->getCore());
        }

        $image->getCore()->trimImage(10);
    }

    private function getDistortion() {
        $className = '\\Intervention\\Image\\Imagick\\Font\\Distortion\\'.str_replace(' ' , '', ucwords(str_replace('_', ' ', $this->textShape)));
        if (!class_exists($className)) {
            throw new \Intervention\Image\Exception\NotSupportedException('Invalid text shape');
        }
        $distortion = new $className();
        return $distortion;
    }

    /**
     * Disable drawing stroke by loops and draw it by built in Imagick methods
     *
     * @return void
     */
    public function drawStrokeByBuiltInMethods()
    {
        $this->isUseBuiltInImagickStroke = true;
    }

    /**
     * Calculates bounding box of current font setting
     *
     * @return array
     * @throws \ImagickException
     * @throws RuntimeException
     */
    public function getBoxSize()
    {
        $box = [];

        // build draw object
        $draw = new \ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);

        // set font file
        if ($this->hasApplicableFontFile()) {
            $draw->setFont($this->file);
        } else {
            throw new \Intervention\Image\Exception\RuntimeException(
                "Font file must be provided to apply text to image."
            );
        }

        $draw->setFontSize($this->size);

        $dimensions = (new \Imagick())->queryFontMetrics($draw, $this->text);

        if (strlen($this->text) == 0) {
            // no text -> no boxsize
            $box['width'] = 0;
            $box['height'] = 0;
        } else {
            // get boxsize
            $box['width'] = intval(abs($dimensions['textWidth']));
            $box['height'] = intval(abs($dimensions['textHeight']));
        }

        return $box;
    }
}
