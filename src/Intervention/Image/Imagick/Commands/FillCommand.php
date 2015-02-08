<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Image;
use \Intervention\Image\Imagick\Decoder;
use \Intervention\Image\Imagick\Color;

class FillCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Fills image with color or pattern
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $filling = $this->argument(0)->value();
        $x = $this->argument(1)->type('digit')->value();
        $y = $this->argument(2)->type('digit')->value();

        $width = $image->width();
        $height = $image->height();

        $filling = $this->decodeFilling($filling);

        // flood fill if coordinates are set
        if (is_int($x) && is_int($y)) {

            // flood fill with texture
            if ($filling instanceof Image) {

                foreach ($image as $frame) {
                    // create tile
                    $tile = clone $frame->getCore()->getImage();

                    // mask away color at position
                    $tile->transparentPaintImage($tile->getImagePixelColor($x, $y), 0, 0, false);

                    // create canvas
                    $canvas = clone $frame->getCore()->getImage();

                    // fill canvas with texture
                    $canvas = $canvas->textureImage($filling->getCore());

                    // merge canvas and tile
                    $canvas->compositeImage($tile, \Imagick::COMPOSITE_DEFAULT, 0, 0);

                    // replace image core
                    $frame->getCore()->setImage($canvas);
                }

            // flood fill with color
            } elseif ($filling instanceof Color) {

                foreach ($image as $frame) {
                    // create tile
                    $tile = clone $frame->getCore()->getImage();

                    // mask away color at position
                    $tile->transparentPaintImage($tile->getImagePixelColor($x, $y), 0, 0, false);

                    // create canvas filled with color
                    $canvas = clone $frame->getCore()->getImage();

                    // setup draw object
                    $draw = new \ImagickDraw();
                    $draw->setFillColor($filling->getPixel());
                    $draw->rectangle(0, 0, $width, $height);

                    // fill canvas with color
                    $canvas->drawImage($draw);    

                    // merge canvas and tile
                    $canvas->compositeImage($tile, \Imagick::COMPOSITE_DEFAULT, 0, 0);

                    // replace image core
                    $frame->getCore()->setImage($canvas);

                }
            }

        } else {

            if ($filling instanceof Image) {

                // fill each frame with texture
                foreach ($image as $frame) {
                    $filled = $frame->getCore()->textureImage($filling->getCore());
                    $frame->getCore()->setImage($filled);
                }

            } elseif ($filling instanceof Color) {

                // setup draw object
                $draw = new \ImagickDraw();
                $draw->setFillColor($filling->getPixel());
                $draw->rectangle(0, 0, $width, $height);

                // fill each frame with color
                foreach ($image as $frame) {
                    $frame->getCore()->drawImage($draw);    
                }
            }
        }

        return true;
    }

    /**
     * Decodes given filling value into Image or Color object
     *
     * @param  mixed $value
     * @return Decoder|Color
     */
    private function decodeFilling($value)
    {
        try {
            // set image filling
            $source = new Decoder;
            $filling = $source->init($value);

        } catch (\Intervention\Image\Exception\NotReadableException $e) {

            // set solid color filling
            $filling = new Color($value);
        }

        return $filling;
    }
}
