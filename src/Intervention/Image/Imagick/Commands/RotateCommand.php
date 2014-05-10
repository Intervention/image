<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Imagick\Color;

class RotateCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $angle = $this->getArgument(0);
        $color = new Color($this->getArgument(1));

        // rotate image
        $image->getCore()->rotateImage($color->getPixel(), ($angle * -1));

        return true;
    }
}
