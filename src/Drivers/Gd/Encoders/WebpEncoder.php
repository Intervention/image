<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $quality
 */
class WebpEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $quality = $this->quality === 100 ? IMG_WEBP_LOSSLESS : $this->quality;
        $data = $this->getBuffered(function () use ($image, $quality) {
            imagewebp($image->core()->native(), null, $quality);
        });

        return new EncodedImage($data, 'image/webp');
    }
}
