<?php

namespace Intervention\Image\Gd\Commands;

use \Intervention\Image\Size;

class GetSizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $this->setOutput(new Size(
            imagesx($image->getCore()), 
            imagesy($image->getCore())
        ));

        return true;
    }
}
