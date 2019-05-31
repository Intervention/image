<?php
namespace Intervention\Image\Imagick\Font\Distortion;

use Intervention\Image\Imagick\Font\AbstractDistortion;

/**
 */
class Flag extends AbstractDistortion
{
    public function distort($image)
    {
        $image->setImageBackgroundColor('transparent');
        $image->waveImage(10, $image->getImageWidth()/5);
    }
}