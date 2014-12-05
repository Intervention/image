<?php

namespace Intervention\Image\Tools\Gif;

class Encoder
{
    /**
     * Canvas width
     *
     * @var integer
     */
    public $canvasWidth;

    /**
     * Canvas height
     *
     * @var integer
     */
    public $canvasHeight;

    /**
     * Global color table
     *
     * @var string
     */
    public $globalColorTable;

    /**
     * Number of sequence loops
     *
     * @var integer
     */
    public $loops;

    /**
     * Image frames
     * 
     * @var array
     */
    public $frames = array();

    /**
     * Set canvas dimensions
     *
     * @param  int $width
     * @param  int $height
     * @return \Intervention\Image\Tools\Gif\Encoder
     */
    public function setCanvas($width, $height)
    {
        $this->canvasWidth = $width;
        $this->canvasHeight = $height;

        return $this;
    }

    /**
     * Set number of loops
     *
     * @param  int $value
     * @return \Intervention\Image\Tools\Gif\Encoder
     */
    public function setLoops($value)
    {
        $this->loops = $value;

        return $this;
    }

    /**
     * Set global color table
     *
     * @param  int $value
     * @return \Intervention\Image\Tools\Gif\Encoder
     */
    public function setGlobalColorTable($value)
    {
        $this->globalColorTable = $value;

        return $this;
    }

    public function addFrame(Frame $frame)
    {
        $this->frames[] = $frame;
    }

    public function createFrame()
    {
        $frame = new Frame;

        $this->frames[] = $frame;
    }

    public function createFrameFromGdResource($resource, $delay = null)
    {
        // get imagedata from resource
        $gifdata = $this->encodeGdResource($resource);
        $decoder = new Decoder;
        $gif = $decoder->initFromData($gifdata)->decode();

        $frame = $gif->getFrames()[0];
        $frame->setLocalColorTable($gif->getGlobalColorTable());
        $frame->setDelay($delay);

        $this->frames[] = $frame;
    }

    /**
     * Encode image data
     *
     * @return string
     */
    public function encode()
    {
        // create gif
        $encoded = $this->buildLogicalScreenDescriptor();

        // netscape extension
        if ($this->isAnimated()) {
            $encoded .= $this->buildNetscapeExtension();
        }

        // add frame(s)
        foreach ($this->frames as $frame) {
            $encoded .= $this->buildFrame($frame);
        }
        
        // EOF
        $encoded .= "\x3B";

        return $encoded;
    }

    /**
     * Build logical screen descriptor
     *
     * @return string
     */
    private function buildLogicalScreenDescriptor()
    {
        // gif header
        $descriptor = 'GIF89a';

        // canvas width/height
        $descriptor .= pack('v*', $this->canvasWidth);
        $descriptor .= pack('v*', $this->canvasHeight);

        // packed field
        $descriptor .= "\x00";

        // background color index
        $descriptor .= "\x00";

        // pixel aspect ratio
        $descriptor .= "\x00";

        return $descriptor;
    }

    private function buildNetscapeExtension()
    {
        $extension = "\x21";
        $extension .= "\xFF";
        $extension .= "\x0B";
        $extension .= 'NETSCAPE2.0';
        $extension .= "\x03";
        $extension .= "\x01";
        $extension .= pack('v', $this->loops);
        $extension .= "\x00";

        return $extension;
    }

    private function buildFrame(Frame $frame)
    {
        // graphics control extensions
        $encoded = $this->buildGraphicsControlExtension($frame);

        // image descriptor
        $encoded .= $this->buildImageDescriptor($frame);

        // add image data
        $encoded .= $frame->getImageData();

        return $encoded;
    }

    private function buildGraphicsControlExtension(Frame $frame)
    {
        // start
        $extension = "\x21\xF9";
        
        // byte size
        $extension .= "\x04";
        
        // packed field
        $extension .= "\x00";
        
        // delay
        $extension .= pack('v*', $frame->getDelay());
        
        // transparent color index
        $extension .= "\x00";
        
        // block terminator
        $extension .= "\x00";

        return $extension;
    }

    private function buildImageDescriptor(Frame $frame)
    {
        // seperator
        $descriptor = "\x2C";

        // image left/top
        $descriptor .= pack('v*', 
            $frame->offset->left, 
            $frame->offset->top
        );

        // image width/height
        $descriptor .= pack('v*', 
            $frame->size->width, 
            $frame->size->height
        );

        $interlacedFlag = $frame->isInterlaced() ? 1 : 0;
        $sortFlag = 0;
        $reserved1 = 0;
        $reserved2 = 0;

        if ($frame->hasLocalColorTable()) {
            $colorTableFlag = 1;
            $colorTableSize = log(strlen($frame->getLocalColorTable()) / 3, 2) - 1;
            $colorTableSize = decbin($colorTableSize);
        } else {
            $colorTableFlag = 0;
            $colorTableSize = 0;
        }

        // packed field
        $packed = $colorTableFlag.$interlacedFlag.$sortFlag.$reserved1.$reserved2.$colorTableSize;
        $descriptor .= pack('C', bindec($packed));

        if ($frame->hasLocalColorTable()) {
            // add local color table
            $descriptor .= $frame->getLocalColorTable();
        }
        
        return $descriptor;
    }

    private function hasGlobalColorTable()
    {
        return isset($this->globalColorTable);
    }

    private function encodeGdResource($resource)
    {
        ob_start();
        imagegif($resource);
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    public function isAnimated()
    {
        return count($this->frames) > 1;
    }

}
