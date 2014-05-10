<?php

namespace Intervention\Image\Gd\Commands;

class WidenCommand extends ResizeCommand
{
    public function execute($image)
    {
        $width = $this->getArgument(0);

        $this->arguments[0] = $width;
        $this->arguments[1] = null;
        $this->arguments[2] = function ($constraint) {
            $constraint->aspectRatio();
        };
        
        return parent::execute($image);
    }
}
