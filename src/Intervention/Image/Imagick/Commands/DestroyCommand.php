<?php

namespace Intervention\Image\Imagick\Commands;

class DestroyCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        return $image->getCore()->clear();
    }
}
