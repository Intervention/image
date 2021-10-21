<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (! $this->inputType($input)->isBinary()) {
            $this->fail();
        }

        $imagick = new Imagick();
        $imagick->readImageBlob($input);
        $imagick = $imagick->coalesceImages();

        $image = new Image(new Collection());

        foreach ($imagick as $frame_content) {
            $image->addFrame(new Frame($frame_content));
        }

        return $image;
    }
}
