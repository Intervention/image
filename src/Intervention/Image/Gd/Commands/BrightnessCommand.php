<?php

namespace Intervention\Image\Gd\Commands;

class BrightnessCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $level = $this->getArgument(0);

        return imagefilter($image->getCore(), IMG_FILTER_BRIGHTNESS, ($level * 2.55));
    }
}
