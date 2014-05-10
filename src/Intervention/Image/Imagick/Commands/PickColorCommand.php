<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Imagick\Color;

class PickColorCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x = $this->getArgument(0, 0);
        $y = $this->getArgument(1, 0);
        $format = $this->getArgument(2, 'array');

        // pick color
        $color = new Color($image->getCore()->getImagePixelColor($x, $y));

        // format to output
        $this->setOutput($color->format($format));

        return true;
    }
}
