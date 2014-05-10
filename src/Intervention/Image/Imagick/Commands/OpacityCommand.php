<?php

namespace Intervention\Image\Imagick\Commands;

class OpacityCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $transparency = $this->getArgument(0);

        return $image->getCore()->setImageOpacity($transparency / 100);
    }
}
