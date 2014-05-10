<?php

namespace Intervention\Image\Imagick\Commands;

class GammaCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $gamma = $this->getArgument(0);

        return $image->getCore()->gammaImage($gamma);
    }
}
