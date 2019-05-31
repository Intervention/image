<?php
namespace Intervention\Image\Imagick\Font\Distortion;

use Intervention\Image\Imagick\Font\AbstractDistortion;

/**
 */
class Bridge extends AbstractDistortion
{
    public function distort($image)
    {
        $points = [
            0.0, 0.0, 0.0, 1.0,   0.0, 0.0, -0.1, 1.3
        ];
        $image->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $image->setImageBackgroundColor('transparent');
        $image->distortImage(\Imagick::DISTORTION_BARREL, $points, true);
    }
}