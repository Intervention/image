<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Size;

class FitCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $width = $this->getArgument(0);
        $height = $this->getArgument(1, $width);

        // calculate size
        $fitted = $image->getSize()->fit(new Size($width, $height));

        // crop image
        $image->getCore()->cropImage(
            $fitted->width, 
            $fitted->height, 
            $fitted->pivot->x, 
            $fitted->pivot->y
        );
        
        // resize image
        $image->getCore()->resizeImage($width, $height, \Imagick::FILTER_BOX, 1);
        $image->getCore()->setImagePage(0,0,0,0);
        
        return true;
    }
}
