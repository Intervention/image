<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Point;
use \Intervention\Image\Size;

class CropCommand extends ResizeCommand
{
    public function execute($image)
    {
        $width = $this->getArgument(0);
        $height = $this->getArgument(1);
        $x = $this->getArgument(2);
        $y = $this->getArgument(3);

        if (is_null($width) || is_null($height)) {
            throw new \Intervention\Image\Exception\InvalidArgumentException(
                "Width and height of cutout needs to be defined."
            );
        }

        $cropped = new Size($width, $height);
        $position = new Point($x, $y);

        // align boxes
        if (is_null($x) && is_null($y)) {
            $position = $image->getSize()->align('center')->relativePosition($cropped->align('center'));
        }

        // crop image core
        return $this->modify($image, 0, 0, $position->x, $position->y, $cropped->width, $cropped->height, $cropped->width, $cropped->height);
    }
}
