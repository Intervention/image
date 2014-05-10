<?php

namespace Intervention\Image;

abstract class AbstractEncoder
{
    abstract protected function processJpeg();
    abstract protected function processPng();
    abstract protected function processGif();

    public $result;

    public function process(Image $image, $format = null, $quality = null)
    {
        $this->setImage($image);
        $this->setFormat($format);
        $this->setQuality($quality);

        switch (strtolower($this->format)) {

            case 'data-url':
                $this->result = $this->processDataUrl();
                break;

            case 'gif':
            case 'image/gif':
                $this->result = $this->processGif();
                break;

            case 'png':
            case 'image/png':
                $this->result = $this->processPng();
                break;

            default:
            case 'jpg':
            case 'jpeg':
            case 'image/jpg':
            case 'image/jpeg':
                $this->result = $this->processJpeg();
                break;
        }

        return $image->setEncoded($this->result);
    }

    protected function processDataUrl()
    {
        return sprintf('data:%s;base64,%s', 
            $this->image->mime, 
            base64_encode($this->process($this->image, $this->quality))
        );
    }

    protected function setImage($image)
    {
        $this->image = $image;
    }

    protected function setFormat($format = null)
    {
        if (is_null($format) && $this->image instanceof Image) {
            $format = $this->image->mime;
        }

        $this->format = $format;

        return $this;
    }

    protected function setQuality($quality)
    {
        $quality = is_null($quality) ? 90 : $quality;
        $quality = $quality === 0 ? 1 : $quality;

        if ($quality < 0 || $quality > 100) {
            throw new \Intervention\Image\Exception\InvalidArgumentException(
                'Quality must range from 0 to 100.'
            );
        }

        $this->quality = intval($quality);

        return $this;
    }
}
