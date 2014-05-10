<?php

namespace Intervention\Image\Imagick\Commands;

class InvertCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        return $image->getCore()->negateImage(false);
    }
}
