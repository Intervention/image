<?php

namespace Intervention\Image\Imagick\Commands;

class ColorizeToRGBACommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Changes balance of different RGB color channels
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

        $alpha = $this->convertAlphaToImagickAlpha($alpha);

        // apply
        $color = sprintf(
            'rgba(%s,%s,%s,%s)',
            $red,
            $blue,
            $green,
            $alpha
        );

        return $image->getCore()->colorizeImage($color);
    }

    /**
     * Alpha must be a float between 0 and 1.
     *
     * http://php.net/manual/en/imagickpixel.construct.php
     * @param int $alpha
     * @return float|int
     */
    private function convertAlphaToImagickAlpha($alpha)
    {
        return $alpha / 100;
    }
}
