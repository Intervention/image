<?php

namespace Intervention\Image\Imagick;

class Encoder extends \Intervention\Image\AbstractEncoder
{
    private function processImage($compression, $format)
    {
        $imagick = $this->image->getCore();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return $imagick->getImagesBlob();
    }

    /**
     * Processes and returns encoded image as JPEG string
     *
     * @return string
     */
    protected function processJpeg()
    {
        $format = 'jpeg';
        $compression = \Imagick::COMPRESSION_JPEG;

        $imagick = $this->image->getCore();
        $imagick->setImageBackgroundColor('white');
        $imagick->setBackgroundColor('white');
        $imagick = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_MERGE);
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return $imagick->getImagesBlob();
    }

    /**
     * Processes and returns encoded image as PNG string
     *
     * @return string
     */
    protected function processPng()
    {
        $compression = \Imagick::COMPRESSION_ZIP;

        return $this->processImage($compression, 'png');
    }

    /**
     * Processes and returns encoded image as GIF string
     *
     * @return string
     */
    protected function processGif()
    {
        $compression = \Imagick::COMPRESSION_LZW;

        return $this->processImage($compression, 'gif');
    }

    /**
     * Processes and returns encoded image as TIFF string
     *
     * @return string
     */
    protected function processTiff()
    {
        $compression = \Imagick::COMPRESSION_UNDEFINED;

        return $this->processImage($compression, 'tiff');
    }

    /**
     * Processes and returns encoded image as BMP string
     *
     * @return string
     */
    protected function processBmp()
    {
        $compression = \Imagick::COMPRESSION_UNDEFINED;

        return $this->processImage($compression, 'bmp');
    }

    /**
     * Processes and returns encoded image as ICO string
     *
     * @return string
     */
    protected function processIco()
    {
        $compression = \Imagick::COMPRESSION_UNDEFINED;

        return $this->processImage($compression, 'ico');
    }

    /**
     * Processes and returns encoded image as PSD string
     *
     * @return string
     */
    protected function processPsd()
    {
        $compression = \Imagick::COMPRESSION_UNDEFINED;

        return $this->processImage($compression, 'psd');
    }

    /**
     * Processes and returns encoded image as Webp string
     *
     * @return string
     */
    protected function processWebp()
    {
        $compression = \Imagick::COMPRESSION_UNDEFINED;

        return $this->processImage($compression, 'webp');
    }
}
