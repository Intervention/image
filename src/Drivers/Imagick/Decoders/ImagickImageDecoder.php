<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Origin;

class ImagickImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_object($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!($input instanceof Imagick)) {
            throw new DecoderException('Unable to decode input');
        }

        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        if ($input->getImageFormat() != 'JPEG') {
            $input = $input->coalesceImages();
        }

        // fix image orientation
        switch ($input->getImageOrientation()) {
            case Imagick::ORIENTATION_TOPRIGHT: // 2
                $input->flopImage();
                break;

            case Imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $input->rotateimage("#000", 180);
                break;

            case Imagick::ORIENTATION_BOTTOMLEFT: // 4
                $input->rotateimage("#000", 180);
                $input->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTTOP: // 5
                $input->rotateimage("#000", -270);
                $input->flopImage();
                break;

            case Imagick::ORIENTATION_RIGHTTOP: // 6
                $input->rotateimage("#000", -270);
                break;

            case Imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $input->rotateimage("#000", -90);
                $input->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTBOTTOM: // 8
                $input->rotateimage("#000", -90);
                break;
        }

        // set new orientation in image
        $input->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        $image = new Image(
            new Driver(),
            new Core($input)
        );

        $image->setOrigin(new Origin(
            $input->getImageMimeType()
        ));

        return $image;
    }
}
