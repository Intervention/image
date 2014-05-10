<?php

namespace Intervention\Image\Gd\Commands;

class BlurCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $amount = $this->getArgument(0, 1);

        for ($i=0; $i < intval($amount); $i++) {
            imagefilter($image->getCore(), IMG_FILTER_GAUSSIAN_BLUR);
        }

        return true;
    }
}
