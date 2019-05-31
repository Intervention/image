<?php
namespace Intervention\Image\Imagick\Font\Distortion;

use Intervention\Image\Imagick\Font\AbstractDistortion;

/**
 */
class ArcUp extends AbstractDistortion
{
    public function distort($image)
    {
        $distort = [180];
        $image->distortImage(\Imagick::DISTORTION_ARC, $distort, false);
    }
}