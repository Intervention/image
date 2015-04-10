<?php

namespace Intervention\Image;

abstract class AbstractDecoder
{
    /**
     * Initiates new image from path in filesystem
     *
     * @param  string $path
     * @return \Intervention\Image\Image
     */
    abstract public function initFromPath($path);

    /**
     * Initiates new image from binary data
     *
     * @param  string $data
     * @return \Intervention\Image\Image
     */
    abstract public function initFromBinary($data);

    /**
     * Initiates new image from GD resource
     *
     * @param  Resource $resource
     * @return \Intervention\Image\Image
     */
    abstract public function initFromGdResource($resource);

    /**
     * Initiates new image from Imagick object
     *
     * @param  Imagick $object
     * @return \Intervention\Image\Image
     */
    abstract public function initFromImagick(\Imagick $object);

    /**
     * Buffer of input data
     *
     * @var mixed
     */
    private $data;

    /**
     * Creates new Decoder with data
     *
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Init from fiven URL
     *
     * @param  string $url
     * @return \Intervention\Image\Image
     */
    public function initFromUrl($url)
    {
        if ($data = @file_get_contents($url)) {
            return $this->initFromBinary($data);
        }

        throw new \Intervention\Image\Exception\NotReadableException(
            "Unable to init from given url (".$url.")."
        );
    }

    /**
     * Init from given stream
     *
     * @param $stream
     * @return \Intervention\Image\Image
     */
    public function initFromStream($stream)
    {
        $offset = ftell($stream);
        rewind($stream);
        $data = @stream_get_contents($stream);
        fseek($stream, $offset);
        if ($data) {
            return $this->initFromBinary($data);
        }

        throw new \Intervention\Image\Exception\NotReadableException(
            "Unable to init from given stream"
        );
    }

    /**
     * Determines if current source data is GD resource
     *
     * @return boolean
     */
    public function isGdResource()
    {
        if (is_resource($this->data)) {
            return (get_resource_type($this->data) == 'gd');
        }

        return false;
    }

    /**
     * Determines if current source data is Imagick object
     *
     * @return boolean
     */
    public function isImagick()
    {
        return is_a($this->data, 'Imagick');
    }

    /**
     * Determines if current source data is Intervention\Image\Image object
     *
     * @return boolean
     */
    public function isInterventionImage()
    {
        return is_a($this->data, '\Intervention\Image\Image');
    }

    /**
     * Determines if current data is SplFileInfo object
     *
     * @return boolean
     */
    public function isSplFileInfo()
    {
        return is_a($this->data, 'SplFileInfo');
    }

    /**
     * Determines if current data is Symfony UploadedFile component
     *
     * @return boolean
     */
    public function isSymfonyUpload()
    {
        return is_a($this->data, 'Symfony\Component\HttpFoundation\File\UploadedFile');
    }

    /**
     * Determines if current source data is file path
     *
     * @return boolean
     */
    public function isFilePath()
    {
        if (is_string($this->data)) {
            return is_file($this->data);
        }

        return false;
    }

    /**
     * Determines if current source data is url
     *
     * @return boolean
     */
    public function isUrl()
    {
        return (bool) filter_var($this->data, FILTER_VALIDATE_URL);
    }

    /**
     * Determines if current source data is a stream resource
     *
     * @return boolean
     */
    public function isStream()
    {
        if (!is_resource($this->data)) return false;
        if (get_resource_type($this->data) !== 'stream') return false;

        return true;
    }

    /**
     * Determines if current source data is binary data
     *
     * @return boolean
     */
    public function isBinary()
    {
        if (is_string($this->data)) {
            $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->data);
            return (substr($mime, 0, 4) != 'text' && $mime != 'application/x-empty');
        }

        return false;
    }

    /**
     * Determines if current source data is data-url
     *
     * @return boolean
     */
    public function isDataUrl()
    {
        $data = $this->decodeDataUrl($this->data);

        return is_null($data) ? false : true;
    }

    /**
     * Determines if current source data is base64 encoded
     *
     * @return boolean
     */
    public function isBase64()
    {
        return base64_encode(base64_decode($this->data)) === $this->data;
    }

    /**
     * Initiates new Image from Intervention\Image\Image
     *
     * @param  Image $object
     * @return \Intervention\Image\Image
     */
    public function initFromInterventionImage($object)
    {
        return $object;
    }

    /**
     * Parses and decodes binary image data from data-url
     *
     * @param  string $data_url
     * @return string
     */
    private function decodeDataUrl($data_url)
    {
        $pattern = "/^data:(?:image\/[a-zA-Z\-\.]+)(?:charset=\".+\")?;base64,(?P<data>.+)$/";
        preg_match($pattern, $data_url, $matches);

        if (is_array($matches) && array_key_exists('data', $matches)) {
            return base64_decode($matches['data']);
        }

        return null;
    }

    /**
     * Initiates new image from mixed data
     *
     * @param  mixed $data
     * @return \Intervention\Image\Image
     */
    public function init($data)
    {
        $this->data = $data;

        switch (true) {

            case $this->isGdResource():
                return $this->initFromGdResource($this->data);

            case $this->isImagick():
                return $this->initFromImagick($this->data);

            case $this->isInterventionImage():
                return $this->initFromInterventionImage($this->data);

            case $this->isSplFileInfo():
                return $this->initFromPath($this->data->getRealPath());

            case $this->isBinary():
                return $this->initFromBinary($this->data);

            case $this->isUrl():
                return $this->initFromUrl($this->data);

            case $this->isStream():
                return $this->initFromStream($this->data);

            case $this->isFilePath():
                return $this->initFromPath($this->data);

            case $this->isDataUrl():
                return $this->initFromBinary($this->decodeDataUrl($this->data));

            case $this->isBase64():
                return $this->initFromBinary(base64_decode($this->data));

            default:
                throw new Exception\NotReadableException("Image source not readable");
        }
    }

    /**
     * Decoder object transforms to string source data
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->data;
    }
}
