<?php

declare(strict_types=1);

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
use Intervention\Image\Origin;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($input);
        } catch (ImagickException) {
            throw new DecoderException('Unable to decode input');
        }

        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        if ($imagick->getImageFormat() != 'JPEG') {
            $imagick = $imagick->coalesceImages();
        }

        // fix image orientation
        switch ($imagick->getImageOrientation()) {
            case Imagick::ORIENTATION_TOPRIGHT: // 2
                $imagick->flopImage();
                break;

            case Imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $imagick->rotateimage("#000", 180);
                break;

            case Imagick::ORIENTATION_BOTTOMLEFT: // 4
                $imagick->rotateimage("#000", 180);
                $imagick->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTTOP: // 5
                $imagick->rotateimage("#000", -270);
                $imagick->flopImage();
                break;

            case Imagick::ORIENTATION_RIGHTTOP: // 6
                $imagick->rotateimage("#000", -270);
                break;

            case Imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $imagick->rotateimage("#000", -90);
                $imagick->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTBOTTOM: // 8
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

        $image->setOrigin(new Origin(
            $imagick->getImageMimeType()
        ));

        return $image;
    }
}
