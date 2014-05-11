<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Gd\Color;

class PickColorCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $x = $this->getArgument(0);
        $y = $this->getArgument(1);
        $format = $this->getArgument(2, 'array');

        // pick color
        $color = imagecolorat($image->getCore(), $x, $y);

        if ( ! imageistruecolor($image->getCore())) {
            $color = imagecolorsforindex($image->getCore(), $color);    
            $color['alpha'] = round(1 - $color['alpha'] / 127, 2);
        }

        $color = new Color($color);

        // format to output
        $this->setOutput($color->format($format));

        return true;
    }
}
