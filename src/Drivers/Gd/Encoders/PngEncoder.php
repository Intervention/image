<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\CanBufferOutput;
use Intervention\Image\Traits\IsDriverSpecialized;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    use CanBufferOutput;
    use IsDriverSpecialized;

    public function encode(ImageInterface $image): EncodedImage
    {
        $data = $this->getBuffered(function () use ($image) {
            imagepng($image->core()->native(), null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }
}
