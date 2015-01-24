<?php

namespace Intervention\Image\Imagick\Commands;

class GammaCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Applies gamma correction to a given image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $gamma = $this->argument(0)->type('numeric')->required()->value();

        foreach ($image as $frame) {
            $frame->getCore()->gammaImage($gamma);
        }

        return true;
    }
}
