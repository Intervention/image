<?php

namespace Intervention\Image\Imagick\Commands;

class ResizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $width = $this->getArgument(0);
        $height = $this->getArgument(1);
        $constraints = $this->getArgument(2);

        // resize box
        $resized = $image->getSize()->resize($width, $height, $constraints);

        // modify image
        $image->getCore()->resizeImage($resized->getWidth(), $resized->getHeight(), \Imagick::FILTER_CATROM, 1);

        return true;
    }
}
