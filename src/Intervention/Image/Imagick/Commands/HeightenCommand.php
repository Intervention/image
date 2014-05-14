<?php

namespace Intervention\Image\Imagick\Commands;

class HeightenCommand extends ResizeCommand
{
    public function execute($image)
    {
        $height = $this->argument(0)->type('integer')->required()->value();

        $this->arguments[0] = null;
        $this->arguments[1] = $height;
        $this->arguments[2] = function ($constraint) {
            $constraint->aspectRatio();
        };
        
        return parent::execute($image);
    }
}
