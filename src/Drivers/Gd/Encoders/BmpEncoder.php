<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\BmpEncoder as GenericBmpEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\CanBufferOutput;
use Intervention\Image\Traits\IsDriverSpecialized;

class BmpEncoder extends GenericBmpEncoder implements SpecializedInterface
{
    use CanBufferOutput;
    use IsDriverSpecialized;

    public function encode(ImageInterface $image): EncodedImage
    {
        $data = $this->getBuffered(function () use ($image) {
            imagebmp($image->core()->native(), null, false);
        });

        return new EncodedImage($data, 'image/bmp');
    }
}
