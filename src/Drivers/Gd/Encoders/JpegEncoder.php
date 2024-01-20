<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $quality
 */
class JpegEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $output = Cloner::cloneBlended($image->core()->native(), background: $image->blendingColor());

        $data = $this->getBuffered(function () use ($output) {
            imagejpeg($output, null, $this->quality);
        });

        return new EncodedImage($data, 'image/jpeg');
    }
}
