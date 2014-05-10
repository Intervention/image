<?php

namespace Intervention\Image;

abstract class AbstractSource
{
    abstract public function initFromPath($path);
    abstract public function initFromBinary($data);
    abstract public function initFromGdResource($resource);
    abstract public function initFromImagick($object);

    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function isGdResource()
    {
        if (is_resource($this->data)) {
            return (get_resource_type($this->data) == 'gd');
        }

        return false;
    }

    public function isImagick()
    {
        return is_a($this->data, 'Imagick');
    }

    public function isInterventionImage()
    {
        return is_a($this->data, '\Intervention\Image\Image');
    }

    public function isFilePath()
    {
        if (is_string($this->data)) {
            return is_file($this->data);
        }

        return false;
    }

    public function isUrl()
    {
        return (bool) filter_var($this->data, FILTER_VALIDATE_URL);
    }

    public function isBinary()
    {
        if (is_string($this->data)) {
            $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->data);
            return (substr($mime, 0, 4) != 'text' && $mime != 'application/x-empty');
        }

        return false;
    }

    public function initFromInterventionImage($object)
    {
        return $object;
    }

    public function init($data)
    {
        $this->data = $data;

        switch (true) {

            case $this->isGdResource():
                return $this->initFromGdResource($this->data);
                break;

            case $this->isImagick():
                return $this->initFromImagick($this->data);
                break;

            case $this->isInterventionImage():
                return $this->initFromInterventionImage($this->data);
                break;

            case $this->isBinary():
                return $this->initFromBinary($this->data);
                break;

            case $this->isUrl():
                return $this->initFromBinary(file_get_contents($this->data));
                break;

            case $this->isFilePath():
                return $this->initFromPath($this->data);
                break;

            default:
                throw new Exception\NotReadableException("Image source not readable");
                break;
        }
    }

    public function __toString()
    {
        return (string) $this->data;
    }
}
