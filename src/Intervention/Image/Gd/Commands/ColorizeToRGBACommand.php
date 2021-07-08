<?php

namespace Intervention\Image\Gd\Commands;

class ColorizeToRGBACommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Changes balance of different RGBA color channels
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $red = $this->argument(0)->between(0, 255)->required()->value();
        $green = $this->argument(1)->between(0, 255)->required()->value();
        $blue = $this->argument(2)->between(0, 255)->required()->value();
        $alpha = $this->argument(3)->between(0, 100)->required()->value();

        $alpha = $this->convertAlphaToGdAlpha($alpha);

        // apply filter
        return imagefilter($image->getCore(), IMG_FILTER_COLORIZE, $red, $green, $blue, $alpha);
    }

    /**
     * Alpha channel must be a value between 0 and 127.
     * 0 indicates completely opaque while 127 indicates completely transparent.
     * http://php.net/manual/en/function.imagefilter.php
     *
     * @param int $alpha
     * @return float
     */
    private function convertAlphaToGdAlpha($alpha)
    {
        return $alpha * 1.27;
    }
}
