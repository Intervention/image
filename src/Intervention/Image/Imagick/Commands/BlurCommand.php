<?php

namespace Intervention\Image\Imagick\Commands;

class BlurCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Applies blur effect on image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $amount = $this->argument(0)->between(0, 100)->value(1);

        foreach ($image as $frame) {
            $frame->getCore()->blurImage(1 * $amount, 0.5 * $amount);
        }

        return true;
    }
}
