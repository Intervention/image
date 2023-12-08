<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($input);
        } catch (ImagickException $e) {
            throw new DecoderException('Unable to decode input');
        }

        $imagick = $imagick->coalesceImages();

        // fix image orientation
        switch ($imagick->getImageOrientation()) {
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                $imagick->rotateimage("#000", 180);
                break;

            case Imagick::ORIENTATION_RIGHTTOP:
                $imagick->rotateimage("#000", 90);
                break;

            case Imagick::ORIENTATION_LEFTBOTTOM:
                $imagick->rotateimage("#000", -90);
                break;
        }

        // set new orientation in image
        $imagick->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        $image = new Image(
            new Driver(),
            new Core($imagick),
            $this->extractExifData($input)
        );

        return $image;
    }
}
