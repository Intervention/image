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

        $gd = $this->gdImageToTruecolor($gd);

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

    /**
     * Transform GD image into truecolor version
     *
     * @param  GdImage $gd
     * @return bool
     */
    public function gdImageToTruecolor(GdImage $gd): GdImage
    {
        $width = imagesx($gd);
        $height = imagesy($gd);

        // new canvas
        $canvas = imagecreatetruecolor($width, $height);

        // fill with transparent color
        imagealphablending($canvas, false);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
        imagecolortransparent($canvas, $transparent);
        imagealphablending($canvas, true);

        // copy original
        imagecopy($canvas, $gd, 0, 0, 0, 0, $width, $height);
        imagedestroy($gd);

        return $canvas;
    }
}
