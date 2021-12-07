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
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImageGif;
use Intervention\Gif\Decoder as GifDecoder;
use Intervention\Gif\Splitter as GifSplitter;

class BinaryImageDecoder extends AbstractDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            $this->fail();
        }

        if (! $this->inputType($input)->isBinary()) {
            $this->fail();
        }

        if (is_a($this->inputType($input), ImageGif::class)) {
            return $this->decodeGif($input); // decode (animated) gif
        }

        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            $this->fail();
        }

        if (! imageistruecolor($gd)) {
            imagepalettetotruecolor($gd);
        }

        imagesavealpha($gd, true);

        return new Image(new Collection([new Frame($gd)]));
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
