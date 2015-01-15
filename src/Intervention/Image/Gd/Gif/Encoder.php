<?php

namespace Intervention\Image\Gd\Gif;

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
     * Index of background color
     *
     * @var int
     */
    public $backgroundColorIndex = 0;

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
     * @return \Intervention\Image\Gd\Gif\Encoder
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
     * @return \Intervention\Image\Gd\Gif\Encoder
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
     * @return \Intervention\Image\Gd\Gif\Encoder
     */
    public function setGlobalColorTable($value)
    {
        $this->globalColorTable = $value;

        return $this;
    }

    /**
     * Return global color table of encoder
     *
     * @return string
     */
    public function getGlobalColorTable()
    {
        return $this->globalColorTable;
    }

    /**
     * Setup frame stack of encoder
     *
     * @param Array $frames
     */
    public function setFrames(Array $frames)
    {
        $this->frames = $frames;

        return $this;
    }

    /**
     * Set background color index for encoder
     *
     * @param string $index
     */
    public function setBackgroundColorIndex($index)
    {
        $this->backgroundColorIndex = $index;

        return $this;
    }

    /**
     * Setup encoder from Decoded object
     *
     * @param Decoded  $decoded
     * @param int|null $frameIndex
     * @return \Intervention\Image\Gd\Gif\Encoder
     */
    public function setFromDecoded(Decoded $decoded, $frameIndex = null)
    {
        if (is_null($frameIndex)) {
            // setup from all decoded data
            $width = $decoded->getCanvasWidth();
            $height = $decoded->getCanvasHeight();
            $colorTable = $decoded->getGlobalColorTable();
            $loops = $decoded->getLoops();
            $frames = $decoded->getFrames();
        } else {
            // setup only one specific frame
            $frame = $decoded->getFrame($frameIndex);
            $frame->setOffset(0, 0);
            $width = $frame->decodeWidth();
            $height = $frame->decodeHeight();
            $colorTable = $frame->hasLocalColorTable() ? $frame->getLocalColorTable() : $decoded->getGlobalColorTable();
            $loops = 0;
            $frames = array($frame);
        }

        // setup
        $this->setCanvas($width, $height);
        $this->setGlobalColorTable($colorTable);
        $this->setBackgroundColorIndex($decoded->getBackgroundColorIndex());
        $this->setLoops($loops);
        $this->setFrames($frames);

        return $this;
    }

    /**
     * Add one frame to stack
     *
     * @param Frame $frame
     */
    public function addFrame(Frame $frame)
    {
        $this->frames[] = $frame;
    }

    /**
     * Create and add new frame from GD resource
     *
     * @param  resource $resource
     * @param  integer  $delay
     * @return void
     */
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

        if ($this->hasGlobalColorTable()) {
            $encoded .= $this->getGlobalColorTable();
        }

        // netscape extension
        if ($this->isAnimated() && $this->doesLoop()) {
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
        $colorResolution = 111;
        $sortFlag = 0;

        if ($this->hasGlobalColorTable()) {

            $globalColorTableFlag = 1;
            $globalColorTableSize = log(strlen($this->getGlobalColorTable()) / 3, 2) - 1;
            $globalColorTableSize = decbin($globalColorTableSize);
            $globalColorTableSize = str_pad($globalColorTableSize, 3, 0, STR_PAD_LEFT);

        } else {
            $globalColorTableFlag = 0;
            $globalColorTableSize = 0;
        }

        $packed = $globalColorTableFlag.$colorResolution.$sortFlag.$globalColorTableSize;
        
        $descriptor .= pack('C', bindec($packed));

        // background color index
        $descriptor .= pack('C', $this->backgroundColorIndex);

        // pixel aspect ratio
        $descriptor .= "\x00";

        return $descriptor;
    }

    /**
     * Build Netscape extension
     *
     * @return string
     */
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

    /**
     * Build encoded Frame
     *
     * @param  Frame  $frame
     * @return string
     */
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

    /**
     * Build encoded graphics control extension for frame
     *
     * @param  Frame  $frame
     * @return string
     */
    private function buildGraphicsControlExtension(Frame $frame)
    {
        // start
        $extension = "\x21\xF9";
        
        // byte size
        $extension .= "\x04";
        
        // packed field
        $disposalMethod = decbin($frame->getDisposalMethod());
        $disposalMethod = str_pad($disposalMethod, 3, 0, STR_PAD_LEFT);
        $userInputFlat = 0;
        $transparentColorFlag = $frame->hasTransparentColor() ? 1 : 0;
        $packed = $disposalMethod.$userInputFlat.$transparentColorFlag;
        $packed = str_pad($packed, 3, 0, STR_PAD_LEFT);
        $extension .= pack('C', bindec($packed));
        
        // delay
        $extension .= pack('v*', $frame->getDelay());
        
        // transparent color index
        if ($frame->hasTransparentColor()) {
            $extension .= $frame->getTransparentColorIndex();
        } else {
            $extension .= "\x00";
        }
        
        // block terminator
        $extension .= "\x00";

        return $extension;
    }

    /**
     * Build encoded image descriptor for frame
     *
     * @param  Frame  $frame
     * @return string
     */
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
        $reserved1 = 0;
        $reserved2 = 0;

        if ($frame->hasLocalColorTable()) {
            $colorTableFlag = 1;
            $colorTableSize = log(strlen($frame->getLocalColorTable()) / 3, 2) - 1;
            $colorTableSize = decbin($colorTableSize);
            $sortFlag = 0;
        } else {
            $colorTableFlag = 0;
            $colorTableSize = 0;
            $sortFlag = 0;
        }

        $colorTableSize = str_pad($colorTableSize, 3, 0, STR_PAD_LEFT);

        // packed field
        $packed = $colorTableFlag.$interlacedFlag.$sortFlag.$reserved1.$reserved2.$colorTableSize;
        $descriptor .= pack('C', bindec($packed));

        if ($frame->hasLocalColorTable()) {
            // add local color table
            $descriptor .= $frame->getLocalColorTable();
        }
        
        return $descriptor;
    }

    /**
     * Determines if encoder has global color table
     *
     * @return boolean
     */
    private function hasGlobalColorTable()
    {
        return isset($this->globalColorTable);
    }

    /**
     * Encode GD resource to GIF string
     *
     * @param  resource $resource
     * @return string
     */
    private function encodeGdResource($resource)
    {
        ob_start();
        imagegif($resource);
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    /**
     * Determines if current encoder is set up animated
     *
     * @return boolean
     */
    public function isAnimated()
    {
        return count($this->frames) > 1;
    }

    /**
     * Determines if current encoder is set up to loop animation
     *
     * @return boolean
     */
    public function doesLoop()
    {
        return is_integer($this->loops);
    }
}
