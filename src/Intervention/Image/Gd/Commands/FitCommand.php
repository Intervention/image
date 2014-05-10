<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Point;
use \Intervention\Image\Size;

class FitCommand extends ResizeCommand
{
    public function execute($image)
    {
        $width = $this->getArgument(0);
        $height = $this->getArgument(1, $width);

        // calculate size
        $fitted = $image->getSize()->fit(new Size($width, $height));

        // modify image
        $this->modify($image, 0, 0, $fitted->pivot->x, $fitted->pivot->y, $width, $height, $fitted->getWidth(), $fitted->getHeight());

        return true;
    }
}
