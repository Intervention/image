<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Point;
use \Intervention\Image\Size;

class FitCommand extends ResizeCommand
{
    /**
     * Crops and resized an image at the same time
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $width = $this->argument(0)->type('integer')->required()->value();
        $height = $this->argument(1)->type('integer')->value($width);

        // calculate size
        $fitted = $image->getSize()->fit(new Size($width, $height));

        // modify image
        $this->modify($image, 0, 0, $fitted->pivot->x, $fitted->pivot->y, $width, $height, $fitted->getWidth(), $fitted->getHeight());

        return true;
    }
}
