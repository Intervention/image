<?php

namespace Intervention\Image;

class MimeDetector
{
    /**
     * Binary data to detect
     *
     * @var string
     */
    protected $data;

    /**
     * Constructor
     *
     * @param string $data
     */
    function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Set data to be detected
     *
     * @param string $value
     */
    public function setData($value)
    {
        $this->data = $value;

        return $this;
    }
    
    /**
     * Return MIME type of current data
     *
     * @return string
     */
    public function getMimeType()
    {
        switch (true) {

            case $this->isJpeg():
                return 'image/jpeg';

            case $this->isPng():
                return 'image/png';

            case $this->isGif():
                return 'image/gif';

            case $this->isBitmap():
                return 'image/bmp';

            case $this->isTiff():
                return 'image/tif';

            case $this->isWebp():
                return 'image/webp';

            case $this->isPsd():
                return 'image/vnd.adobe.photoshop';

            case $this->isIco():
                return 'image/x-icon';
        }

        throw new Exception\NotSupportedException(
            "MIME type could not be identified."
        );
    }

    /**
     * Determine if current data is PNG
     *
     * @return boolean
     */
    private function isPng()
    {
        return ($this->getHexBytes(0, 8) == '89504e470d0a1a0a');
    }

    /**
     * Determine if current data is JPG
     *
     * @return boolean
     */
    private function isJpeg()
    {
        $bytes1 = $this->getHexBytes(0, 4);
        $bytes2 = $this->getHexBytes(6, 5);

        return ($bytes1 == 'ffd8ffe0') && ($bytes2 == '4a46494600');
    }

    /**
     * Determine if current data is GIF
     *
     * @return boolean
     */
    private function isGif()
    {
        $bytes = $this->getHexBytes(0, 6);

        return ($bytes == '474946383761') || ($bytes == '474946383961');
    }

    /**
     * Determine if current data is Bitmap
     *
     * @return boolean
     */
    private function isBitmap()
    {
        return ($this->getHexBytes(0, 2) == '424d');
    }

    /**
     * Determine if current data is TIF
     *
     * @return boolean
     */
    private function isTiff()
    {
        $bytes1 = $this->getHexBytes(0, 3);
        $bytes2 = $this->getHexBytes(0, 4);

        return ($bytes1 == '492049') || ($bytes2 == '49492a00');
    }

    /**
     * Determine if current data is ICO
     *
     * @return boolean
     */
    private function isIco()
    {
        return ($this->getHexBytes(0, 4) == '00000100');
    }

    /**
     * Determine if current data is Photoshop
     *
     * @return boolean
     */
    private function isPsd()
    {
        return ($this->getHexBytes(0, 4) == '38425053');
    }

    /**
     * Determine if current data is WEBP
     *
     * @return boolean
     */
    private function isWebp()
    {
        $bytes1 = $this->getHexBytes(0, 4);
        $bytes2 = $this->getHexBytes(8, 4);

        return ($bytes1 == '52494646') && ($bytes2 == '57454250');
    }

    /**
     * Return hexadecimal formated bytes from current data
     *
     * @param  int $start
     * @param  int $length
     * @return string
     */
    private function getHexBytes($start, $length)
    {
        return bin2hex(substr($this->data, $start, $length));
    }
}
