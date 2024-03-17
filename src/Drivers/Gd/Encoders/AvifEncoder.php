<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\AvifEncoder as GenericAvifEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\CanBufferOutput;
use Intervention\Image\Traits\IsDriverSpecialized;

class AvifEncoder extends GenericAvifEncoder implements SpecializedInterface
{
    use CanBufferOutput;
    use IsDriverSpecialized;

    public function encode(ImageInterface $image): EncodedImage
    {
        $gd = $image->core()->native();
        $data = $this->getBuffered(function () use ($gd) {
            imageavif($gd, null, $this->quality);
        });

        return new EncodedImage($data, 'image/avif');
    }
}
