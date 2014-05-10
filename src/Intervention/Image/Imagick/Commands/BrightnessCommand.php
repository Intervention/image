<?php

namespace Intervention\Image\Imagick\Commands;

class BrightnessCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $level = $this->getArgument(0);

        return $image->getCore()->modulateImage(100 + $level, 100, 100);
    }
}
