<?php

namespace Intervention\Image\Gd\Commands;

class ContrastCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $level = $this->getArgument(0);

        return imagefilter($image->getCore(), IMG_FILTER_CONTRAST, ($level * -1));
    }
}
