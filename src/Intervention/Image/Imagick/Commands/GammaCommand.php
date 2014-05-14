<?php

namespace Intervention\Image\Imagick\Commands;

class GammaCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $gamma = $this->argument(0)->type('numeric')->required()->value();

        return $image->getCore()->gammaImage($gamma);
    }
}
