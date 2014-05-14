<?php

namespace Intervention\Image\Gd\Commands;

class GammaCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $gamma = $this->argument(0)->type('numeric')->required()->value();

        return imagegammacorrect($image->getCore(), 1, $gamma);
    }
}
