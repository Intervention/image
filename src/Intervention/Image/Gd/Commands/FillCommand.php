<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Gd\Source;
use Intervention\Image\Gd\Color;

class FillCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $filling = $this->getArgument(0);
        $x = $this->getArgument(1);
        $y = $this->getArgument(2);

        $width = $image->getWidth();
        $height = $image->getHeight();
        $resource = $image->getCore();

        try {

            // set image tile filling
            $source = new Source;
            $tile = $source->init($filling);
            imagesettile($image->getCore(), $tile->getCore());
            $filling = IMG_COLOR_TILED;

        } catch (\Intervention\Image\Exception\NotReadableException $e) {

            // set solid color filling
            $color = new Color($filling);
            $filling = $color->getInt();
        }

        imagealphablending($resource, true);

        if (is_int($x) && is_int($y)) {
            
            // resource should be visible through transparency
            $base = imagecreatetruecolor($width, $height);
            imagecopy($base, $resource, 0, 0, 0, 0, $width, $height);

            // floodfill if exact position is defined
            imagefill($resource, $x, $y, $filling);

            // copy filled original over base
            imagecopy($base, $resource, 0, 0, 0, 0, $width, $height);

            // set base as new resource-core
            $image->setCore($base);
            imagedestroy($resource);

        } else {
            // fill whole image otherwise
            imagefilledrectangle($resource, 0, 0, $width - 1, $height - 1, $filling);
        }

        isset($tile) ? imagedestroy($tile->getCore()) : null;

        return true;
    }
}
