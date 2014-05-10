<?php

namespace Intervention\Image\Imagick\Commands;

class InterlaceCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $mode = $this->getArgument(0);

        if ($mode) {
            $mode = \Imagick::INTERLACE_LINE;
        } else {
            $mode = \Imagick::INTERLACE_NO;
        }

        $image->getCore()->setInterlaceScheme($mode);

        return true;
    }
}
