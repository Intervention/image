<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Size;

class GetSizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $info = $image->getCore()->identifyImage(true);

        $this->setOutput(new Size(
            $info['geometry']['width'], 
            $info['geometry']['height']
        ));

        return true;
    }
}
