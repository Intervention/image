<?php
namespace Intervention\Image\Imagick\Font\Distortion;

use Intervention\Image\Imagick\Font\AbstractDistortion;

/**
 */
class Bulge extends AbstractDistortion
{
    public function distort($image)
    {
        $points = [
            0.0, 0.0, 0.0, 1.0,   0.0, 0.0, 0.5, 0.5
        ];
        $image->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $image->setImageBackgroundColor('transparent');
        $image->distortImage(\Imagick::DISTORTION_BARREL, $points, false);
    }
}