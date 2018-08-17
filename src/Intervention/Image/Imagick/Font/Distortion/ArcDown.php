<?php
namespace Intervention\Image\Imagick\Font\Distortion;

use Intervention\Image\Imagick\Font\AbstractDistortion;

/**
 */
class ArcDown extends AbstractDistortion
{
    public function distort($image)
    {
        $distort = [180];
        $image->rotateImage('transparent', 180);
        $image->distortImage(\Imagick::DISTORTION_ARC, $distort, false);
        $image->rotateImage('transparent', 180);
    }
}