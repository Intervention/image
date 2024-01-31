<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders\Traits;

use Intervention\Gif\Decoder as GifDecoder;
use Intervention\Gif\Splitter as GifSplitter;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;

trait CanDecodeGif
{
    /**
     * Decode image from given GIF source which can be either a file path or binary data
     *
     * @param mixed $input
     * @return ImageInterface
     */
    protected function decodeGif(mixed $input): ImageInterface
    {
        $gif = GifDecoder::decode($input);
        $splitter = GifSplitter::create($gif)->split();
        $delays = $splitter->getDelays();

        // build core
        $core = new Core();

        // set loops on core
        if ($loops = $gif->getMainApplicationExtension()?->getLoops()) {
            $core->setLoops($loops);
        }

        // add GDImage instances to core
        foreach ($splitter->coalesceToResources() as $key => $native) {
            $core->push(
                (new Frame($native))->setDelay($delays[$key] / 100)
            );
        }

        // create image
        $image = new Image(new Driver(), $core);

        // set media type
        $image->origin()->setMediaType('image/gif');

        return $image;
    }
}
