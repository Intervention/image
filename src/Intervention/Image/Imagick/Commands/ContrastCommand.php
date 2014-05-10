<?php

namespace Intervention\Image\Imagick\Commands;

class ContrastCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $level = $this->getArgument(0);

        return $image->getCore()->sigmoidalContrastImage($level > 0, $level / 4, 0);
    }
}
