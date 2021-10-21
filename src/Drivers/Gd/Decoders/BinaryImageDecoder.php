<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

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
        if (! $this->inputType($input)->isBinary()) {
            $this->fail();
        }

        if (is_a($this->inputType($input), ImageGif::class)) {
            return $this->decodeGif($input); // decode (animated) gif
        }

        $resource = @imagecreatefromstring($input);

        if ($resource === false) {
            $this->fail();
        }

        return new Image(new Collection([new Frame($resource)]));
    }

    protected function decodeGif($input): ImageInterface
    {
        $image = new Image(new Collection());
        $gif = GifDecoder::decode($input);

        $image->setLoops($gif->getMainApplicationExtension()?->getLoops());

        if (!$gif->isAnimated()) {
            return $image->addFrame(new Frame(@imagecreatefromstring($input)));
        }

        $splitter = GifSplitter::create($gif)->split();
        $delays = $splitter->getDelays();
        foreach ($splitter->coalesceToResources() as $key => $gd) {
            $image->addFrame((new Frame($gd))->setDelay($delays[$key] / 100));
        }

        return $image;
    }
}
