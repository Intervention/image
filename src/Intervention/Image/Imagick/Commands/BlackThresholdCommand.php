<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Commands\AbstractCommand;

class BlackThresholdCommand extends AbstractCommand
{
    /**
     * Force all pixels below the threshold into black
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $threshold = $this->argument(0)->between(0, 255)->required()->value();

        return $image->getCore()->blackThresholdImage("#". $threshold . $threshold . $threshold);
    }
}
