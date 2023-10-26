<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\MimeSniffer\Types\ImageGif;
use Intervention\Gif\Decoder as GifDecoder;
use Intervention\Gif\Splitter as GifSplitter;
use Intervention\Image\Exceptions\DecoderException;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (! $this->inputType($input)->isBinary()) {
            throw new DecoderException('Unable to decode input');
        }

        if (is_a($this->inputType($input), ImageGif::class)) {
            return $this->decodeGif($input); // decode (animated) gif
        }

        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new DecoderException('Unable to decode input');
        }

        if (! imageistruecolor($gd)) {
            imagepalettetotruecolor($gd);
        }

        imagesavealpha($gd, true);

        // build image instance
        $image = new Image(new Collection([new Frame($gd)]));
        $image->setExif($this->decodeExifData($input));

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

    protected function decodeGif($input): ImageInterface
    {
        $image = new Image(new Collection());
        $gif = GifDecoder::decode($input);


        if (!$gif->isAnimated()) {
            return $image->addFrame(new Frame(@imagecreatefromstring($input)));
        }

        $image->setLoops($gif->getMainApplicationExtension()?->getLoops());

        $splitter = GifSplitter::create($gif)->split();
        $delays = $splitter->getDelays();
        foreach ($splitter->coalesceToResources() as $key => $gd) {
            $image->addFrame((new Frame($gd))->setDelay($delays[$key] / 100));
        }

        return $image;
    }
}
