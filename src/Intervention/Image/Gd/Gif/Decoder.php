<?php

namespace Intervention\Image\Gd\Gif;

class Decoder
{
    const IMAGE_SEPARATOR = "\x2C";
    const EXTENSION_BLOCK_MARKER = "\x21";
    const GRAPHICS_CONTROL_EXTENSION_MARKER = "\xF9";
    const NETSCAPE_EXTENSION_MARKER = "\xFF";
    const PLAINTEXT_EXTENSION_MARKER = "\x01";
    const COMMENT_EXTENSION_MARKER = "\xFE";
    const BLOCK_TERMINATOR = "\x00";
    const TRAILER_MARKER = "\x3B";

    /**
     * File pointer handle
     *
     * @var resource
     */
    private $handle;

    /**
     * Create new GifDecoder instance
     *
     * @param string $path
     */
    public function __construct($path = null)
    {
        if (is_null($path)) {
            $this->handle = fopen('php://memory', 'r+');
        } else {
            $this->handle = fopen($path, 'rb');
        }
    }

    /**
     * Close down GifDecoder instance
     */
    public function __destruct()
    {
        fclose($this->handle);
    }

    public function initFromData($data)
    {
        fwrite($this->handle, $data);
        rewind($this->handle);

        return $this;
    }

    /**
     * Read number of bytes and move file pointer
     *
     * @param  int $length
     * @return string
     */
    protected function getNextBytes($length)
    {
        return fread($this->handle, $length);
    }

     /**
     * Decode image stream
     *
     * @return Decoded
     */
    public function decode()
    {
        $gif = new Decoded;

        // read header
        $gif->setHeader($this->getNextBytes(6));

        // read logocal screen descriptor
        $gif->setlogicalScreenDescriptor($this->getNextBytes(7));

        // read global color table
        if ($gif->hasGlobalColorTable()) {
            $gif->setGlobalColorTable($this->getNextBytes(
                $gif->countGlobalColors() * 3
            ));
        }

        // read body
        while ( ! feof($this->handle)) {

            switch ($this->getNextBytes(1)) {

                case self::EXTENSION_BLOCK_MARKER:
                    $this->decodeExtension($gif);
                    break;

                case self::IMAGE_SEPARATOR:
                    $this->decodeImageDescriptor($gif);
                    $this->decodeImageData($gif);
                    break;

                case self::TRAILER_MARKER:
                    # code...
                    break 2;
                
                default:
                    // throw new \Intervention\Image\Exception\NotReadableException(
                    //     "Unable to decode GIF image."
                    // );
                    break;
            }
        }

        return $gif;
    }

    /**
     * Decode extension in image stream
     *
     * @return void
     */
    private function decodeExtension($gif)
    {
        switch ($this->getNextBytes(1)) {

            case self::GRAPHICS_CONTROL_EXTENSION_MARKER:
                $gif->addGraphicsControlExtension($this->getNextBytes(6));
                break;

            case self::NETSCAPE_EXTENSION_MARKER:
                $gif->setNetscapeExtension($this->getNextBytes(17));
                break;

            case self::PLAINTEXT_EXTENSION_MARKER:
                $blocksize = $this->getNextBytes(1);
                $blocksize = unpack('C', $blocksize);
                $gif->setPlaintextExtension($this->getNextBytes($blocksize));
                $this->getNextBytes(1); // null byte
                break;

            case self::COMMENT_EXTENSION_MARKER:
                $blocksize = $this->getNextBytes(1);
                $blocksize = unpack('C', $blocksize);
                $gif->setCommentExtension($this->getNextBytes($blocksize));
                $this->getNextBytes(1); // null byte
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * Decode Image Descriptor from image stream
     *
     * @return void
     */
    private function decodeImageDescriptor($gif)
    {
        $descriptor = $this->getNextBytes(9);

        // determine if descriptor has local color table
        $flag = substr($descriptor, 8, 1);
        $flag = unpack('C', $flag)[1];
        $flag = (bool) ($flag & bindec('10000000'));
        if ($flag) {
            // read local color table
            $byte = substr($descriptor, 8, 1);
            $byte = unpack('C', $byte)[1];
            $size = (int) ($byte & bindec('00000111'));
            $size = 3 * pow(2, $size + 1);
            
            $gif->addLocalColorTable($this->getNextBytes($size));

        } else {
            $gif->addLocalColorTable(null);
        }

        // determine if image is marked as interlaced
        $interlaced = substr($descriptor, 8, 1);
        $interlaced = unpack('C', $interlaced)[1];
        $interlaced = (bool) ($interlaced & bindec('01000000'));
        $gif->addInterlaced($interlaced);

        // decode image offsets
        $left = substr($descriptor, 0, 2);
        $left = unpack('C', $left)[1];
        $top = substr($descriptor, 2, 2);
        $top = unpack('C', $top)[1];
        $gif->addOffset($left, $top);

        // decode image dimensions
        $width = substr($descriptor, 4, 2);
        $width = unpack('v', $width)[1];
        $height = substr($descriptor, 6, 2);
        $height = unpack('v', $height)[1];
        $gif->addSize($width, $height);

        $gif->addImageDescriptors($descriptor);
    }

        /**
     * Decode Image data from image stream
     *
     * @return void
     */
    private function decodeImageData($gif)
    {
        $data = '';

        // LZW minimum code size
        $data .= $this->getNextBytes(1);

        do {

            $byte = $this->getNextBytes(1);

            if ($byte !== self::BLOCK_TERMINATOR) {
                $size = unpack('C', $byte)[1];
                $data .= $byte;
                $data .= $this->getNextBytes($size);
            } else {
                $data .= self::BLOCK_TERMINATOR;
            }

        } while ($byte !== self::BLOCK_TERMINATOR);

        $gif->addImageData($data);
    }
}
