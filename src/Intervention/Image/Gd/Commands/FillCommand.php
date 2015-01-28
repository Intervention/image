<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Gd\Decoder;
use Intervention\Image\Gd\Color;

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

        $width = imagesx($image->getCore());
        $height = imagesy($image->getCore());

        try {

            // set image tile filling
            $tile = $image->getDriver()->init($filling);

        } catch (\Intervention\Image\Exception\NotReadableException $e) {

            // set solid color filling
            $color = new Color($filling);
            $filling = $color->getInt();
        }


        foreach ($image as $frame) {

            if (isset($tile)) {
                imagesettile($frame->getCore(), $tile->getCore());
                $filling = IMG_COLOR_TILED;    
            }

            imagealphablending($frame->getCore(), true);

            if (is_int($x) && is_int($y)) {

                // resource should be visible through transparency
                $base = $image->getDriver()->newImage($width, $height)->getCore();
                imagecopy($base, $frame->getCore(), 0, 0, 0, 0, $width, $height);

                // floodfill if exact position is defined
                imagefill($frame->getCore(), $x, $y, $filling);

                // copy filled original over base
                imagecopy($base, $frame->getCore(), 0, 0, 0, 0, $width, $height);

                // set base as new resource-core
                imagedestroy($frame->getCore());
                $frame->setCore($base);

            } else {

                // fill whole image otherwise
                imagefilledrectangle(
                    $frame->getCore(),
                    0,
                    0,
                    ($width - 1),
                    ($height - 1),
                    $filling
                );

            }
        }

        isset($tile) ? imagedestroy($tile->getCore()) : null;

        return true;
    }
}
