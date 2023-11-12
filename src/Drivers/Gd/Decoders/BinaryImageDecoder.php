<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use GdImage;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
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

        if ($this->mediaType($input) == 'image/gif') {
            return $this->decodeGif($input); // decode (animated) gif
        }

        $gd = $this->coreFromString($input);

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

    private function coreFromString(string $input): GdImage
    {
        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new DecoderException('Unable to decode input');
        }

        if (!imageistruecolor($gd)) {
            imagepalettetotruecolor($gd);
        }

        imagesavealpha($gd, true);

        return $gd;
    }

    private function decodeGif(string $input): ImageInterface
    {
        $gif = GifDecoder::decode($input);

        if (!$gif->isAnimated()) {
            return new Image(
                new Collection([new Frame(
                    $this->coreFromString($input)
                )]),
            );
        }

        $image = new Image(new Collection());
        $image->setLoops($gif->getMainApplicationExtension()?->getLoops());

        $splitter = GifSplitter::create($gif)->split();

        $delays = $splitter->getDelays();
        foreach ($splitter->coalesceToResources() as $key => $gd) {
            $image->addFrame((new Frame($gd))->setDelay($delays[$key] / 100));
        }

        return $image;
    }
}
