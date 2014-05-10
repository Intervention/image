<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Gd\Color;

class RotateCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $angle = $this->getArgument(0);
        $color = new Color($this->getArgument(1));

        // rotate image
        $image->setCore(imagerotate($image->getCore(), $angle, $color->getInt()));

        return true;
    }
}
