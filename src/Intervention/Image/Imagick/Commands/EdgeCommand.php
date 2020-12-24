<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Commands\AbstractCommand;

class EdgeCommand extends AbstractCommand
{
    /**
     * Enhance edges within the image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        return $image->getCore()->edgeImage(1);
    }
}
