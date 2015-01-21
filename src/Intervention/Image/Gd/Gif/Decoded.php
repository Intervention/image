<?php

namespace Intervention\Image\Gd\Gif;

use Intervention\Image\Frame as ContainerFrame;
use Intervention\Image\Gd\Container;
use Intervention\Image\Gd\Helper;
use Intervention\Image\Gd\Driver;

class Decoded
{
    /**
     * GIF header
     *
     * @var string
     */
    protected $header;

    /**
     * GIF Logical Screen Descriptor
     *
     * @var string
     */
    protected $logicalScreenDescriptor;

    /**
     * Global Color Table (if present)
     *
     * @var string
     */
    protected $globalColorTable;

    /**
     * Netscape Extension (if present)
     *
     * @var string
     */
    protected $netscapeExtension;

    /**
     * Plaintext Extension (if present)
     *
     * @var string
     */
    protected $plaintextExtension;

    /**
     * Comment extention (if present)
     *
     * @var string
     */
    protected $commentExtension;

    /**
     * Array of image frame objects
     *
     * @var array
     */
    protected $frames = array();

    /**
     * Sets GIF header
     *
     * @param string $value
     */
    public function setHeader($value)
    {
        $this->header = $value;

        return $this;
    }

    /**
     * Gets GIF header of current instance
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set Logical Screen Descriptor
     *
     * @param  string $value
     * @return Decoded
     */
    public function setlogicalScreenDescriptor($value)
    {
        $this->logicalScreenDescriptor = $value;

        return $this;
    }

    /**
     * Returns Logical Screen Descriptor of current instance
     *
     * @return string
     */
    public function getlogicalScreenDescriptor()
    {
        return $this->logicalScreenDescriptor;
    }

    /**
     * Sets global color table
     *
     * @param string $value
     */
    public function setGlobalColorTable($value)
    {
        $this->globalColorTable = $value;

        return $this;
    }

    /**
     * Gets global color table
     *
     * @return string
     */
    public function getGlobalColorTable()
    {
        return $this->globalColorTable;
    }

    /**
     * Sets Netscape extension
     *
     * @param string $value
     */
    public function setNetscapeExtension($value)
    {
        $this->netscapeExtension = $value;

        return $this;
    }

    /**
     * Returns Netscape extension
     *
     * @return string
     */
    public function getNetscapeExtension()
    {
        return $this->netscapeExtension;
    }

    /**
     * Sets plain text extension
     *
     * @param string $value
     */
    public function setPlaintextExtension($value)
    {
        $this->plaintextExtension = $value;

        return $this;
    }

    /**
     * Returns plain text extension
     *
     * @return string
     */
    public function getPlaintextExtension()
    {
        return $this->plaintextExtension;
    }

    /**
     * Sets comment extension
     *
     * @param string $value
     */
    public function setCommentExtension($value)
    {
        $this->commentExtension = $value;

        return $this;
    }

    /**
     * Returns comment extension
     *
     * @return string
     */
    public function getCommentExtension()
    {
        return $this->commentExtension;
    }

    /**
     * Returns frames
     *
     * @return array
     */
    public function getFrames()
    {
        return $this->frames;
    }

    /**
     * Returns particular frame
     *
     * @param  integer $index
     * @return \Intervention\Image\Gd\Gif\Frame
     */
    public function getFrame($index = 0)
    {
        if (array_key_exists($index, $this->frames)) {
            return $this->frames[$index];
        }

        throw new \Intervention\Image\Exception\RuntimeException(
            "Frame with index ({$index}) does not exists."
        );
    }

    /**
     * Returns number of current frames
     *
     * @return integer
     */
    public function countFrames()
    {
        return count($this->frames);
    }

    /**
     * Adds graphic control extension to first frame without this extension
     *
     * @param string $value
     */
    public function addGraphicsControlExtension($value)
    {
        $this->addToFirstFrameWithoutProperty($value, 'graphicsControlExtension');
    }

    /**
     * Adds local color table to first frame without local color table
     *
     * @param string $value
     */
    public function addLocalColorTable($value)
    {
        $this->addToFirstFrameWithoutProperty($value, 'localColorTable');
    }

    /**
     * Adds interlaced flag to first frame without flag
     *
     * @param string $value
     */
    public function addInterlaced($value)
    {
        $this->addToFirstFrameWithoutProperty($value, 'interlaced');
    }

    /**
     * Adds offset to first frame without offset
     *
     * @param integer $left
     * @param integer $top
     */
    public function addOffset($left, $top)
    {
        $offset = new \StdClass;
        $offset->left = $left;
        $offset->top = $top;

        $this->addToFirstFrameWithoutProperty($offset, 'offset');
    }

    /**
     * Adds size to first frame without size
     *
     * @param integer $width
     * @param integer $height
     */
    public function addSize($width, $height)
    {
        $size = new \StdClass;
        $size->width = $width;
        $size->height = $height;

        $this->addToFirstFrameWithoutProperty($size, 'size');
    }

    /**
     * Adds image descriptor to first frame without this data
     *
     * @param string $value
     */
    public function addImageDescriptors($value)
    {
        $this->addToFirstFrameWithoutProperty($value, 'imageDescriptor');
    }

    /**
     * Adds image data to first frame without this data
     *
     * @param string $value
     */
    public function addImageData($value)
    {
        $this->addToFirstFrameWithoutProperty($value, 'imageData');
    }

    /**
     * Get canvas width
     *
     * @return int
     */
    public function getCanvasWidth()
    {
        if ($this->logicalScreenDescriptor) {
            return (int) unpack('v',
                substr($this->logicalScreenDescriptor, 0, 2)
            )[1];
        }

        return false;
    }

    /**
     * Get canvas height
     *
     * @return int
     */
    public function getCanvasHeight()
    {
        if ($this->logicalScreenDescriptor) {
            return (int) unpack('v', 
                substr($this->logicalScreenDescriptor, 2, 2)
            )[1];
        }

        return false;
    }

    /**
     * Returns loops of animation
     *
     * @return integer|null
     */
    public function getLoops()
    {
        if ($this->netscapeExtension) {
            $loops = substr($this->netscapeExtension, 14, 2);
            $loops = unpack('C', $loops)[1];

            return $loops;
        }

        return null;
    }

    /**
     * Determines if image has global color table
     *
     * @return boolean
     */
    public function hasGlobalColorTable()
    {
        $byte = substr($this->logicalScreenDescriptor, 4, 1);
        
        if (strlen($byte) == 1) {
            $byte = unpack('C', $byte)[1];
            $bit = $byte & bindec('10000000');
        }

        return isset($bit) ? (bool) $bit : false;
    }

    /**
     * Get number colors in global color palette
     *
     * @return int
     */
    public function countGlobalColors()
    {
        $byte = substr($this->logicalScreenDescriptor, 4, 1);

        if (strlen($byte) == 1) {
            $byte = unpack('C', $byte)[1];
            $bit = $byte & bindec('00000111');
        }
        
        // length of the global color table is 2^(N+1)        
        return isset($bit) ? pow(2, $bit + 1) : 0;
    }

    /**
     * Returns background color index
     *
     * @return integer
     */
    public function getBackgroundColorIndex()
    {
        if ($this->logicalScreenDescriptor) {
            $index = substr($this->logicalScreenDescriptor, 5, 1);
            $index = unpack('C', $index)[1];

            return $index;
        }

        return 0;
    }

    /**
     * Adds value to first frame without this particular value
     *
     * @param mixed $value
     * @param mixed $property
     */
    private function addToFirstFrameWithoutProperty($value, $property)
    {
        $added = false;

        foreach ($this->frames as $key => $frame) {
            if ( ! $frame->propertyIsSet($property)) {
                $frame->setProperty($property, $value);
                $added = true;
                break;
            }
        }

        if ( ! $added) {
            $this->newFrameWithProperty($property, $value);
        }

        return $added;
    }

    /**
     * Adds new frame with given property
     *
     * @param  mixed $property
     * @param  mixed $value
     * @return void
     */
    private function newFrameWithProperty($property, $value)
    {
        $frame = new Frame;
        $this->frames[] = $frame->setProperty($property, $value);
    }

    /**
     * Returns Container object from decoded data
     *
     * @return \Intervention\Image\Gd\Container
     */
    public function createContainer()
    {
        $container = new Container;
        $container->setLoops($this->getLoops());

        // create empty canvas
        $driver = new Driver;
        $canvas = $driver->newImage($this->getCanvasWidth(), $this->getCanvasHeight())->getCore();

        foreach ($this->frames as $key => $frame) {

            // create resource from frame
            $encoder = new Encoder;
            $encoder->setFromDecoded($this, $key);
            $frame_resource = imagecreatefromstring($encoder->encode());

            // insert frame image data into canvas
            imagecopy(
                $canvas,
                $frame_resource,
                $frame->getOffset()->left,
                $frame->getOffset()->top,
                0,
                0,
                $frame->getSize()->width,
                $frame->getSize()->height
            );

            // destory frame resource
            imagedestroy($frame_resource);

            // add frame to container
            $container->addFrame(new ContainerFrame(
                $canvas, 
                $frame->getDelay()
            ));

            // prepare next canvas
            $canvas = Helper::cloneResource($canvas);
        }

        return $container;
    }
}
