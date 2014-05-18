<?php

namespace Intervention\Image\Gd\Commands;

class HeightenCommand extends ResizeCommand
{
    /**
     * Resize image proportionally to given height
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
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
