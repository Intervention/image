<?php

namespace Intervention\Image\Imagick\Commands;

class FlipCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $mode = strtolower($this->getArgument(0, 'h'));

        if (in_array(strtolower($mode), array(2, 'v', 'vert', 'vertical'))) {
            // flip vertical
            return $image->getCore()->flopImage();
        } else {
            // flip horizontal
            return $image->getCore()->flipImage();
        }
    }
}
