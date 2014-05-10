<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Imagick\Color;

class PixelCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $color = new Color($this->getArgument(0));
        $x = $this->getArgument(1);
        $y = $this->getArgument(2);

        // prepare pixel
        $draw = new \ImagickDraw;
        $draw->setFillColor($color->getPixel());
        $draw->point($x, $y);

        // apply pixel
        return $image->getCore()->drawImage($draw);
    }
}
