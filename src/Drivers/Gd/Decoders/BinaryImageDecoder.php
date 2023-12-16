<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Gif\Decoder as GifDecoder;
use Intervention\Gif\Splitter as GifSplitter;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Origin;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if ($this->isGifFormat($input)) {
            return $this->decodeGif($input); // decode (animated) gif
        }

        return $this->decodeString($input);
    }

    private function decodeString(string $input): ImageInterface
    {
        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new DecoderException('Unable to decode input');
        }

        if (!imageistruecolor($gd)) {
            imagepalettetotruecolor($gd);
        }

        imagesavealpha($gd, true);

        // build image instance
        $image =  new Image(
            new Driver(),
            new Core([
                new Frame($gd)
            ]),
            $this->extractExifData($input)
        );

        if ($info = getimagesizefromstring($input)) {
            $image->setOrigin(
                new Origin($info['mime'])
            );
        }

        // fix image orientation
        return match ($image->exif('IFD0.Orientation')) {
            2 => $image->flip(),
            3 => $image->rotate(180),
            4 => $image->rotate(180)->flip(),
            5 => $image->rotate(270)->flip(),
            6 => $image->rotate(270),
            7 => $image->rotate(90)->flip(),
            8 => $image->rotate(90),
            default => $image
        };
    }

    private function decodeGif(string $input): ImageInterface
    {
        $gif = GifDecoder::decode($input);

        if (!$gif->isAnimated()) {
            return $this->decodeString($input);
        }

        $splitter = GifSplitter::create($gif)->split();
        $delays = $splitter->getDelays();

        // build core
        $core = new Core();
        $core->setLoops($gif->getMainApplicationExtension()?->getLoops());
        foreach ($splitter->coalesceToResources() as $key => $data) {
            $core->push(
                (new Frame($data))->setDelay($delays[$key] / 100)
            );
        }

        $image = new Image(new Driver(), $core);

        return $image->setOrigin(
            new Origin('image/gif')
        );
    }
}
