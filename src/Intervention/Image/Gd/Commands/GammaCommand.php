<?php

namespace Intervention\Image\Gd\Commands;

class GammaCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $gamma = $this->getArgument(0);

        return imagegammacorrect($image->getCore(), 1, $gamma);
    }
}
