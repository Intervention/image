<?php

namespace Intervention\Image\Imagick\Commands;

class SharpenCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Sharpen image
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $amount = $this->argument(0)->between(0, 100)->required()->value();

        return $image->getCore()->unsharpMaskImage(1, 1, $amount / 6.25, 0);
    }
}
