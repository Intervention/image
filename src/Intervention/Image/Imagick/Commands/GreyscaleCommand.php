<?php

namespace Intervention\Image\Imagick\Commands;

class GreyscaleCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        return $image->getCore()->modulateImage(100, 0, 100);
    }
}
