<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\WebpEncoder as GenericWebpEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\CanBufferOutput;
use Intervention\Image\Traits\IsDriverSpecialized;

class WebpEncoder extends GenericWebpEncoder implements SpecializedInterface
{
    use CanBufferOutput;
    use IsDriverSpecialized;

    public function encode(ImageInterface $image): EncodedImage
    {
        $quality = $this->quality === 100 ? IMG_WEBP_LOSSLESS : $this->quality;
        $data = $this->getBuffered(function () use ($image, $quality) {
            imagewebp($image->core()->native(), null, $quality);
        });

        return new EncodedImage($data, 'image/webp');
    }
}
