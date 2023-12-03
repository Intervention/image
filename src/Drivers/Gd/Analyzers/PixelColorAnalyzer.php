<?php

namespace Intervention\Image\Drivers\Gd\Analyzers;

use GdImage;
use Intervention\Image\Drivers\DriverSpecializedAnalyzer;
use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $x
 * @property int $y
 * @property int $frame_key
 */
class PixelColorAnalyzer extends DriverSpecializedAnalyzer
{
    public function analyze(ImageInterface $image): mixed
    {
        return $this->colorAt(
            $image->colorspace(),
            $image->core()->frame($this->frame_key)->native()
        );
    }

    protected function colorAt(ColorspaceInterface $colorspace, GdImage $gd): ColorInterface
    {
        $index = @imagecolorat($gd, $this->x, $this->y);

        if ($index === false) {
            throw new GeometryException(
                'The specified position is not in the valid image area.'
            );
        }

        return $this->driver()->colorProcessor($colorspace)->nativeToColor($index);
    }
}
