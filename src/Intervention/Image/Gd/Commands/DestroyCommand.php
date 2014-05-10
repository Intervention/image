<?php

namespace Intervention\Image\Gd\Commands;

class DestroyCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        return imagedestroy($image->getCore());
    }
}
