<?php

namespace Intervention\Image\Gd\Commands;

class InterlaceCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $mode = $this->argument(0)->type('bool')->value(true);
        
        imageinterlace($image->getCore(), $mode);

        return true;
    }
}
