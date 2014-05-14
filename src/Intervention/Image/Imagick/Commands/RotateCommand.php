<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Imagick\Color;

class RotateCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $angle = $this->argument(0)->type('numeric')->required()->value();
        $color = $this->argument(1)->value();
        $color = new Color($color);

        // rotate image
        $image->getCore()->rotateImage($color->getPixel(), ($angle * -1));

        return true;
    }
}
