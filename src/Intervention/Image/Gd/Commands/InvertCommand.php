<?php

namespace Intervention\Image\Gd\Commands;

class InvertCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        return imagefilter($image->getCore(), IMG_FILTER_NEGATE);
    }
}
