<?php

namespace Intervention\Image\Gd\Commands;

class InterlaceCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $mode = $this->getArgument(0, true);
        
        imageinterlace($image->getCore(), $mode);

        return true;
    }
}
