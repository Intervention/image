<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Gd\Color;

class PixelCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $color = new Color($this->getArgument(0));
        $x = $this->getArgument(1);
        $y = $this->getArgument(2);

        return imagesetpixel($image->getCore(), $x, $y, $color->getInt());
    }
}
