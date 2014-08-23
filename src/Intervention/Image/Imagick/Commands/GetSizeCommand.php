<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Size;

class GetSizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Reads size of given image instance in pixels
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $info = $image->getCore()->identifyImage();

        $this->setOutput(new Size(
            $info['geometry']['width'],
            $info['geometry']['height']
        ));

        return true;
    }
}
