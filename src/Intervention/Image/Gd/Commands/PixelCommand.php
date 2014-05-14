<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Gd\Color;

class PixelCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $color = $this->argument(0)->required()->value();
        $color = new Color($color);
        $x = $this->argument(1)->type('integer')->required()->value();
        $y = $this->argument(2)->type('integer')->required()->value();

        return imagesetpixel($image->getCore(), $x, $y, $color->getInt());
    }
}
