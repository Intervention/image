<?php

namespace Intervention\Image;

abstract class AbstractEncoder
{
    /**
     * Buffer of encode result data
     *
     * @var string
     */
    public $result;

    /**
     * Image object to encode
     *
     * @var Image
     */
    public $image;

    /**
     * Output format of encoder instance
     *
     * @var string
     */
    public $format;

    /**
     * Output quality of encoder instance
     *
     * @var integer
     */
    public $quality;
    
    /**
     * Processes and returns encoded image as JPEG string
     *
     * @return string
     */
    abstract protected function processJpeg();

    /**
     * Processes and returns encoded image as PNG string
     *
     * @return string
     */
    abstract protected function processPng();

    /**
     * Processes and returns encoded image as GIF string
     *
     * @return string
     */
    abstract protected function processGif();

    /**
     * Processes and returns encoded image as TIFF string
     *
     * @return string
     */
    abstract protected function processTiff();

    /**
     * Process a given image
     *
     * @param  Image   $image
     * @param  string  $format
     * @param  integer $quality
     * @return Image
     */
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

            case 'jpg':
            case 'jpeg':
            case 'image/jpg':
            case 'image/jpeg':
                $this->result = $this->processJpeg();
                break;

            case 'tif':
            case 'tiff':
            case 'image/tiff':
                $this->result = $this->processTiff();
                break;
                
            default:
                throw new \Intervention\Image\Exception\NotSupportedException(
                    "Encoding format ({$format}) is not supported."
                );
        }

        return $image->setEncoded($this->result);
    }

    /**
     * Processes and returns encoded image as data-url string
     *
     * @return string
     */
    protected function processDataUrl()
    {
        return sprintf('data:%s;base64,%s',
            $this->image->mime,
            base64_encode($this->process($this->image, null, $this->quality))
        );
    }

    /**
     * Sets image to process
     *
     * @param Image $image
     */
    protected function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Determines output format
     *
     * @param string $format
     */
    protected function setFormat($format = null)
    {
        if ($format == '' && $this->image instanceof Image) {
            $format = $this->image->mime;
        }

        $this->format = $format ? $format : 'jpg';

        return $this;
    }

    /**
     * Determines output quality
     *
     * @param integer $quality
     */
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
