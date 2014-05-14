<?php

namespace Intervention\Image\Gd\Commands;

class ContrastCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $level = $this->argument(0)->between(-100, 100)->required()->value();

        return imagefilter($image->getCore(), IMG_FILTER_CONTRAST, ($level * -1));
    }
}
