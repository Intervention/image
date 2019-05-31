<?php
namespace Intervention\Image\Imagick\Font\Distortion;

use Intervention\Image\Imagick\Font\AbstractDistortion;

/**
 */
class ArchedDown extends AbstractDistortion
{
    public function distort($image)
    {
        $image->setImageBackgroundColor('transparent');
        $image->waveImage(30, $image->getImageWidth() * 2);
    }
}