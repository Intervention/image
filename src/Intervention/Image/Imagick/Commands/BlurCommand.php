<?php

namespace Intervention\Image\Imagick\Commands;

class BlurCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $amount = intval($this->getArgument(0, 1));

        return $image->getCore()->blurImage(1 * $amount, 0.5 * $amount);
    }
}
