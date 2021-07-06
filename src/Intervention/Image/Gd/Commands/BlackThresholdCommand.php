<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Commands\AbstractCommand;

class BlackThresholdCommand extends AbstractCommand
{
    /**
     * Force all pixels below the threshold into black
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $threshold = $this->argument(0)->between(0, 255)->required()->value();

        $core = $image->getCore();

        $width = imagesx($core);
        $height = imagesy($core);

        $black = imagecolorallocate($core, 0, 0, 0);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($core, $x, $y);

                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if ($r < $threshold && $g < $threshold && $b < $threshold) {
                    imagesetpixel($core, $x, $y, $black);
                }
            }
        }

        return true;
    }
}
