<?php

namespace Intervention\Image\Imagick\Commands;

class WidenCommand extends ResizeCommand
{
    /**
     * Resize image proportionally to given width
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $width = $this->argument(0)->type('integer')->required()->value();

        $this->arguments[0] = $width;
        $this->arguments[1] = null;
        $this->arguments[2] = function ($constraint) {
            $constraint->aspectRatio();
        };

        return parent::execute($image);
    }
}
