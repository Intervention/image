<?php

namespace Intervention\Image\Imagick\Commands;

class OpacityCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $transparency = $this->argument(0)->between(0, 100)->required()->value();

        return $image->getCore()->setImageOpacity($transparency / 100);
    }
}
