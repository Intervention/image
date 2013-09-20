<?php

namespace Intervention\Image;

use Exception;
use Closure;

class Image
{
    /**
     * The image resource identifier of current image
     *
     * @var resource
     */
    public $resource;

    /**
     * Type of current image
     *
     * @var string
     */
    public $type;

    /**
     * Width of current image
     *
     * @var integer
     */
    public $width;

    /**
     * Height of current image
     *
     * @var integer
     */
    public $height;

    /**
     * Directory path of current image
     *
     * @var string
     */
    public $dirname;

    /**
     * Trailing name component of current image filename
     *
     * @var string
     */
    public $basename;

    /**
     * File extension of current image filename
     *
     * @var string
     */
    public $extension;

    /**
     * Combined filename (basename and extension)
     *
     * @var string
     */
    public $filename;

    /**
     * MIME type of image
     *
     * @var string
     */
    public $mime;

    /**
     * Attributes of the original created image
     *
     * @var Array
     */
    protected $original;

    /**
     * Identifier for cached images
     *
     * @var boolean
     */
    public $cached = false;

    /**
     * Create a new instance of Image class
     *
     * @param string  $source
     * @param integer $width
     * @param integer $height
     * @param mixed   $bgcolor
     */
    public function __construct($source = null, $width = null, $height = null, $bgcolor = null)
    {
        // set image properties
        if (!is_null($source)) {

            if ($this->isImageResource($source)) {

                // image properties come from gd image resource
                $this->initFromResource($source);

            } elseif ($this->isBinary($source)) {

                // image properties come from binary image string
                $this->initFromString($source);

            } else {
                
                // image properties come from image file
                $this->initFromPath($source);
            }

        } else {

            // new empty resource
            $this->initEmpty($width, $height, $bgcolor);
        }
    }

    /**
     * Open a new image resource from image file
     *
     * @param  mixed $source
     * @return Image
     */
    public static function make($source)
    {
        return new Image($source);
    }

    /**
     * Create a new empty image resource
     *
     * @param  int   $width
     * @param  int   $height
     * @param  mixed $bgcolor
     * @return Image
     */
    public static function canvas($width, $height, $bgcolor = null)
    {
        return new Image(null, $width, $height, $bgcolor);
    }

    /**
     * Create a new image resource with image data from string
     *
     * @param  string $data
     * @return Image
     */
    public static function raw($string)
    {
        return new Image($string);
    }

    /**
     * Create new cached image and run callback
     * (requires additional package intervention/imagecache)
     *
     * @param  Closure $callback
     * @param  integer $lifetime
     * @param  boolean $returnObj
     * @return Image
     */
    public static function cache(Closure $callback = null, $lifetime = null, $returnObj = false)
    {
        if (!class_exists('\Intervention\Image\ImageCache')) {
            throw new Exception('Please install package intervention/imagecache before running this function.');
        }

        // Create image and run callback
        $image = new \Intervention\Image\ImageCache;
        $image = is_callable($callback) ? $callback($image) : $image;

        return $image->get($lifetime, $returnObj);
    }

    /**
     * Set properties for image resource from image file
     *
     * @param string $path
     * @return void
     */
    private function initFromPath($path)
    {
        if (!file_exists($path)) {
            throw new Exception("Image file ({$path}) not found");
        }

        // set file info
        $this->setFileInfoFromPath($path);

        // set image info
        $this->setImageInfoFromPath($path);
    }

    /**
     * Set properties for image resource from string
     *
     * @param string $string
     * @return void
     */
    private function initFromString($string)
    {
        $this->setImageInfoFromString($string);
    }

    /**
     * Set image properties from GD image resource
     *
     * @param resource $resource
     */
    private function initFromResource($resource)
    {
        if (!$this->isImageResource($resource)) {
            throw new Exception("initFromResource expects parameter to be resource.");
        }

        $this->setImageInfoFromResource($resource);
    }

    /**
     * Set properties for empty image resource
     *
     * @param int   $width
     * @param int   $height
     * @param mixed $bgcolor
     * @return void
     */
    private function initEmpty($width, $height, $bgcolor = null)
    {
        $this->width = is_numeric($width) ? intval($width) : 1;
        $this->height = is_numeric($height) ? intval($height) : 1;

        $this->original['width'] = $this->width;
        $this->original['height'] = $this->height;

        // create empty image
        $this->resource = imagecreatetruecolor($this->width, $this->height);

        // set background color
        if (is_null($bgcolor)) {
            // fill with transparent background instead of black
            $bgcolor = imagecolorallocatealpha($this->resource, 0, 0, 0, 127);
        } else {
            $bgcolor = $this->parseColor($bgcolor);
        }

        imagefill($this->resource, 0, 0, $bgcolor);
        $this->original['bgcolor'] = $bgcolor;
    }

    /**
     * Modify wrapper function used by resize and grab
     *
     * @param  integer $dst_x
     * @param  integer $dst_y
     * @param  integer $src_x
     * @param  integer $src_y
     * @param  integer $dst_w
     * @param  integer $dst_h
     * @param  integer $src_w
     * @param  integer $src_h
     * @return Image
     */
    private function modify($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
    {
        // create new image
        $image = imagecreatetruecolor($dst_w, $dst_h);

        // preserve transparency
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // copy content from resource
        imagecopyresampled($image, $this->resource, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        // set new content as recource
        $this->resource = $image;

        // set new dimensions
        $this->width = $dst_w;
        $this->height = $dst_h;

        return $this;
    }

    /**
     * Open a new image resource from image file
     *
     * @param  string $path
     * @return Image
     */
    public function open($path)
    {
        $this->initFromPath($path);

        return $this;
    }

    /**
     * Resize current image based on given width/height
     *
     * Width and height are optional, the not given parameter is calculated
     * based on the given. The ratio boolean decides whether the resizing
     * should keep the image ratio. You can also pass along a boolean to
     * prevent the image from being upsized.
     *
     * @param integer $width  The target width for the image
     * @param integer $height The target height for the image
     * @param boolean $ratio  Determines if the image ratio should be preserved
     * @param boolean $upsize Determines whether the image can be upsized
     *
     * @return Image
     */
    public function resize($width = null, $height = null, $ratio = false, $upsize = true)
    {
        // catch legacy call
        if (is_array($width)) {
            $dimensions = $width;
            return $this->legacyResize($dimensions);
        }

        // Evaluate passed parameters.
        $width = isset($width) ? intval($width) : null;
        $height = $max_height = isset($height) ? intval($height) : null;
        $ratio = $ratio ? true : false;
        $upsize = $upsize ? true : false;

        // If the ratio needs to be kept.
        if ($ratio) {

            // If both width and hight have been passed along, the width and
            // height parameters are maximum values.
            if (! is_null($width) && ! is_null($height)) {

                // First, calculate the height.
                $height = intval($width / $this->width * $this->height);

                // If the height is too large, set it to the maximum
                // height and calculate the width.
                if ($height > $max_height) {

                    $height = $max_height;
                    $width = intval($height / $this->height * $this->width);
                }
            
            } elseif ($ratio && ( ! is_null($width) or ! is_null($height))) {
                // If only one of width or height has been provided.
                $width = is_null($width) ? intval($height / $this->height * $this->width) : $width;
                $height = is_null($height) ? intval($width / $this->width * $this->height) : $height;
            }
        }

        // If the image can't be upsized, check if the given width and/or
        // height are too large.
        if (! $upsize) {
            // If the given width is larger then the image width,
            // then don't resize it.
            if (! is_null($width) && $width > $this->width) {
                $width = $this->width;

                // If ratio needs to be kept, height is recalculated.
                if ($ratio) {
                    $height = intval($width / $this->width * $this->height);
                }
            }

            // If the given height is larger then the image height,
            // then don't resize it.
            if (! is_null($height) && $height > $this->height) {
                $height = $this->height;

                // If ratio needs to be kept, width is recalculated.
                if ($ratio) {
                    $width = intval($height / $this->height * $this->width);
                }
            }
        }

        // If both the width and height haven't been passed along,
        // throw an exception.
        if (is_null($width) && is_null($height)) {

            throw new Exception('width or height needs to be defined');

        } elseif (is_null($width)) { // If only the width hasn't been set, keep the current width.

            $width = $this->width;

        } elseif (is_null($height)) { // If only the height hasn't been set, keep the current height.

            $height = $this->height;

        }

        // Create new image in new dimensions.
        return $this->modify(0, 0, 0, 0, $width, $height, $this->width, $this->height);
    }

    /**
     * Legacy method to support old resizing calls
     *
     * @param  array  $dimensions
     * @return Image
     */
    public function legacyResize($dimensions = array())
    {
        $width = array_key_exists('width', $dimensions) ? intval($dimensions['width']) : null;
        $height = array_key_exists('height', $dimensions) ? intval($dimensions['height']) : null;
        return $this->resize($width, $height, true);
    }

    /**
     * Resize image to new width, constraining proportions
     *
     * @param  integer $width
     * @return Image
     */
    public function widen($width)
    {
        return $this->resize($width, null, true);
    }

    /**
     * Resize image to new height, constraining proportions
     *
     * @param  integer $height
     * @return Image
     */
    public function heighten($height)
    {
        return $this->resize(null, $height, true);
    }

    /**
     * Resize image canvas
     *
     * @param  int  $width
     * @param  int  $height
     * @param  string  $anchor
     * @param  boolean $relative
     * @param  mixed  $bgcolor
     * @return Image
     */
    public function resizeCanvas($width, $height, $anchor = null, $relative = false, $bgcolor = null)
    {
        // check of only width or height is set
        $width = is_null($width) ? $this->width : intval($width);
        $height = is_null($height) ? $this->height : intval($height);

        // check on relative width/height
        if ($relative) {
            $width = $this->width + $width;
            $height = $this->height + $height;
        }

        // check for negative width
        if ($width <= 0) {
            $width = $this->width + $width;
        }

        // check for negative height
        if ($height <= 0) {
            $height = $this->height + $height;
        }

        // create new canvas
        $image = imagecreatetruecolor($width, $height);

        if ($width > $this->width || $height > $this->height) {
            $bgcolor = is_null($bgcolor) ? '000000' : $bgcolor;
            imagefill($image, 0, 0, $this->parseColor($bgcolor));
        }

        if ($width >= $this->width) {
            $src_w = $this->width;
        } else {
            $src_w = $width;
        }

        if ($height >= $this->height) {
            $src_h = $this->height;
        } else {
            $src_h = $height;
        }

        // define anchor
        switch ($anchor) {
            case 'top-left':
            case 'left-top':
                $src_x = 0;
                $src_y = 0;
                break;

            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $src_x = ($width < $this->width) ? intval(($this->width - $width) / 2) : 0;
                $src_y = 0;
                break;

            case 'top-right':
            case 'right-top':
                $src_x = ($width < $this->width) ? intval($this->width - $width) : 0;
                $src_y = 0;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $src_x = 0;
                $src_y = ($height < $this->height) ? intval(($this->height - $height) / 2) : 0;
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $src_x = ($width < $this->width) ? intval($this->width - $width) : 0;
                $src_y = ($height < $this->height) ? intval(($this->height - $height) / 2) : 0;
                break;

            case 'bottom-left':
            case 'left-bottom':
                $src_x = 0;
                $src_y = ($height < $this->height) ? intval($this->height - $height) : 0;
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $src_x = ($width < $this->width) ? intval(($this->width - $width) / 2) : 0;
                $src_y = ($height < $this->height) ? intval($this->height - $height) : 0;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $src_x = ($width < $this->width) ? intval($this->width - $width) : 0;
                $src_y = ($height < $this->height) ? intval($this->height - $height) : 0;
                break;

            default:
            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $src_x = ($width < $this->width) ? intval(($this->width - $width) / 2) : 0;
                $src_y = ($height < $this->height) ? intval(($this->height - $height) / 2) : 0;
                break;
        }

        // define dest. pos
        $dst_x = ($width <= $this->width) ? 0 : intval(($width - $this->width) / 2);
        $dst_y = ($height <= $this->height) ? 0 : intval(($height - $this->height) / 2);

        // copy content from resource
        imagecopy($image, $this->resource, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);

        // set new content as recource
        $this->resource = $image;

        // set new dimensions
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * Crop the current image
     *
     * @param  integer $width
     * @param  integer $height
     * @param  integer $pos_x
     * @param  integer $pos_y
     *
     * @return Image
     */
    public function crop($width, $height, $pos_x = null, $pos_y = null)
    {
        $width = is_numeric($width) ? intval($width) : null;
        $height = is_numeric($height) ? intval($height) : null;
        $pos_x = is_numeric($pos_x) ? intval($pos_x) : null;
        $pos_y = is_numeric($pos_y) ? intval($pos_y) : null;

        if (is_null($pos_x) && is_null($pos_y)) {
            // center position of width/height rectangle
            $pos_x = floor(($this->width - intval($width)) / 2);
            $pos_y = floor(($this->height - intval($height)) / 2);
        }

        if (is_null($width) || is_null($height)) {
            throw new Exception('width and height of cutout needs to be defined');
        }

        return $this->modify(0, 0, $pos_x, $pos_y, $width, $height, $width, $height);
    }

    /**
     * Cut out a detail of the image in given ratio and resize to output size
     *
     * @param  integer  $width
     * @param  integer  $height
     *
     * @return Image
     */
    public function grab($width = null, $height = null)
    {
        // catch legacy call
        if (is_array($width)) {
            $dimensions = $width;
            return $this->legacyGrab($dimensions);
        }

        $width = is_numeric($width) ? intval($width) : null;
        $height = is_numeric($height) ? intval($height) : null;

        if (! is_null($width) or ! is_null($height)) {
            // if width or height are not set, define values automatically
            $width = is_null($width) ? $height : $width;
            $height = is_null($height) ? $width : $height;
        } else {
            // width or height not defined (resume with original values)
            throw new Exception('width or height needs to be defined');
        }

        // ausschnitt berechnen
        $grab_width = $this->width;
        $ratio = $grab_width / $width;

        if ($height * $ratio <= $this->height) {
            $grab_height = round($height * $ratio);
            $src_x = 0;
            $src_y = round(($this->height - $grab_height) / 2);
        } else {
            $grab_height = $this->height;
            $ratio = $grab_height / $height;
            $grab_width = round($width * $ratio);
            $src_x = round(($this->width - $grab_width) / 2);
            $src_y = 0;
        }

        return $this->modify(0, 0, $src_x, $src_y, $width, $height, $grab_width, $grab_height);
    }

    /**
     * Legacy Method to support older grab calls
     *
     * @param  array  $dimensions
     * @return Image
     */
    public function legacyGrab($dimensions = array())
    {
        $width = array_key_exists('width', $dimensions) ? intval($dimensions['width']) : null;
        $height = array_key_exists('height', $dimensions) ? intval($dimensions['height']) : null;

        return $this->grab($width, $height);
    }

    /**
     * Mirror image horizontally or vertically
     *
     * @param  mixed $mode
     * @return Image
     */
    public function flip($mode = null)
    {
        $x = 0;
        $y = 0;
        $width = $this->width;
        $height = $this->height;

        switch (strtolower($mode)) {
            case 2:
            case 'v':
            case 'vert':
            case 'vertical':
                $y = $height - 1;
                $height = $height * (-1);
                break;

            default:
                $x = $width - 1;
                $width = $width * (-1);
                break;
        }

        return $this->modify(0, 0, $x, $y, $this->width, $this->height, $width, $height);
    }

    /**
     * Insert another image on top of the current image
     *
     * @param  mixed   $source
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @param  string  $anchor
     * @return Image
     */
    public function insert($source, $pos_x = 0, $pos_y = 0, $anchor = null)
    {
        $obj = is_a($source, 'Intervention\Image\Image') ? $source : (new Image($source));

        // define anchor
        switch ($anchor) {

            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $pos_x = intval((($this->width - $obj->width) / 2) + $pos_x);
                $pos_y = $pos_y;
                break;

            case 'top-right':
            case 'right-top':
                $pos_x = intval($this->width - $obj->width - $pos_x);
                $pos_y = $pos_y;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $pos_x = $pos_x;
                $pos_y = intval((($this->height - $obj->height) / 2) + $pos_y);
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $pos_x = intval($this->width - $obj->width - $pos_x);
                $pos_y = intval((($this->height - $obj->height) / 2) + $pos_y);
                break;

            case 'bottom-left':
            case 'left-bottom':
                $pos_x = $pos_x;
                $pos_y = intval($this->height - $obj->height - $pos_y);
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $pos_x = intval((($this->width - $obj->width) / 2) + $pos_x);
                $pos_y = intval($this->height - $obj->height - $pos_y);
                break;

            case 'bottom-right':
            case 'right-bottom':
                $pos_x = intval($this->width - $obj->width - $pos_x);
                $pos_y = intval($this->height - $obj->height - $pos_y);
                break;

            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $pos_x = intval((($this->width - $obj->width) / 2) + $pos_x);
                $pos_y = intval((($this->height - $obj->height) / 2) + $pos_y);
                break;

            default:
            case 'top-left':
            case 'left-top':
                $pos_x = intval($pos_x);
                $pos_y = intval($pos_y);
                break;
        }

        imagecopy($this->resource, $obj->resource, $pos_x, $pos_y, 0, 0, $obj->width, $obj->height);

        return $this;
    }

    /**
     * Set opacity of current image
     *
     * @param  integer $transparency
     * @return Image
     */
    public function opacity($transparency)
    {
        if ($transparency >= 0 && $transparency <= 100) {
            $transparency = intval($transparency) / 100;
        } else {
            throw new Exception('Opacity must be between 0 and 100');
        }

        // create alpha mask
        $alpha = new self(null, $this->width, $this->height);
        $alpha->fill(sprintf('rgba(0, 0, 0, %.1f)', $transparency));

        // apply alpha mask
        $this->mask($alpha, true);

        return $this;
    }

    /**
     * Apply given image as alpha mask on current image
     *
     * @param  mixed   $source
     * @param  boolean $mask_with_alpha
     * @return Image
     */
    public function mask($source, $mask_with_alpha = false)
    {
        // create new empty image
        $maskedImage = new Image(null, $this->width, $this->height);

        // create mask
        $mask = is_a($source, 'Intervention\Image\Image') ? $source : (new Image($source));

        // resize mask to size of current image (if necessary)
        if ($mask->width != $this->width || $mask->height != $this->height) {
            $mask->resize($this->width, $this->height);
        }

        // redraw old image pixel by pixel considering alpha map
        for ($x=0; $x < $this->width; $x++) {
            for ($y=0; $y < $this->height; $y++) {

                $color = $this->pickColor($x, $y, 'array');
                $alpha = $mask->pickColor($x, $y, 'array');

                if ($mask_with_alpha) {
                    $alpha = $alpha['a']; // use alpha channel as mask
                } else {
                    $alpha = floatval(round($alpha['r'] / 255, 2)); // use red channel as mask
                }

                // preserve alpha of original image...
                if ($color['a'] < $alpha) {
                    $alpha = $color['a'];
                }

                $pixelColor = array($color['r'], $color['g'], $color['b'], $alpha);
                $maskedImage->pixel($pixelColor, $x, $y);
            }
        }

        // apply masked image to current instance
        $this->resource = $maskedImage->resource;
        $this->width = $maskedImage->width;
        $this->height = $maskedImage->height;

        return $this;
    }

    /**
     * Rotate image with given angle
     *
     * @param  float    $angle
     * @param  string   $color
     * @param  int      $ignore_transparent
     * @return Image
     */
    public function rotate($angle = 0, $bgcolor = '#000000', $ignore_transparent = 0)
    {
        // rotate image
        $this->resource = imagerotate($this->resource, $angle, $this->parseColor($bgcolor), $ignore_transparent);

        // re-read width/height
        $this->width = imagesx($this->resource);
        $this->height = imagesy($this->resource);

        return $this;
    }

    /**
     * Fill image with given color or image source at position x,y
     *
     * @param  mixed   $source
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @return Image
     */
    public function fill($source, $pos_x = 0, $pos_y = 0)
    {
        if (is_a($source, 'Intervention\Image\Image')) {

            // fill with image
            imagesettile($this->resource, $source->resource);
            $source = IMG_COLOR_TILED;

        } elseif ($this->isImageResource($source)) {

            // fill with image resource
            imagesettile($this->resource, $source);
            $source = IMG_COLOR_TILED;

        } elseif (is_string($source) && $this->isBinary($source)) {

            // fill with image from binary string
            $img = new self($source);
            imagesettile($this->resource, $img->resource);
            $source = IMG_COLOR_TILED;

        } elseif (is_string($source) && file_exists(realpath($source))) {

            $img = new self($source);
            imagesettile($this->resource, $img->resource);
            $source = IMG_COLOR_TILED;

        } else {

            // fill with color
            $source = $this->parseColor($source);
        }

        imagefill($this->resource, $pos_x, $pos_y, $source);

        return $this;
    }

    /**
     * Set single pixel
     *
     * @param  string  $color
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @return Image
     */
    public function pixel($color, $pos_x = 0, $pos_y = 0)
    {
        imagesetpixel($this->resource, $pos_x, $pos_y, $this->parseColor($color));

        return $this;
    }

    /**
     * Draw rectangle in current image starting at point 1 and ending at point 2
     *
     * @param  string  $color
     * @param  integer $x1
     * @param  integer $y1
     * @param  integer $x2
     * @param  integer $y2
     * @param  boolean $filled
     * @return Image
     */
    public function rectangle($color, $x1 = 0, $y1 = 0, $x2 = 10, $y2 = 10, $filled = true)
    {
        $callback = $filled ? 'imagefilledrectangle' : 'imagerectangle';
        call_user_func($callback, $this->resource, $x1, $y1, $x2, $y2, $this->parseColor($color));

        return $this;
    }

    /**
     * Draw a line in current image starting at point 1 and ending at point 2
     *
     * @param  string  $color
     * @param  integer $x1
     * @param  integer $y1
     * @param  integer $x2
     * @param  integer $y2
     * @return Image
     */
    public function line($color, $x1 = 0, $y1 = 0, $x2 = 10, $y2 = 10)
    {
        imageline($this->resource, $x1, $y1, $x2, $y2, $this->parseColor($color));

        return $this;
    }

    /**
     * Draw an ellipse centered at given coordinates.
     *
     * @param  string  $color
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @param  integer $width
     * @param  integer $height
     * @return Image
     */
    public function ellipse($color, $pos_x = 0, $pos_y = 0, $width = 10, $height = 10, $filled = true)
    {
        $callback = $filled ? 'imagefilledellipse' : 'imageellipse';
        call_user_func($callback, $this->resource, $pos_x, $pos_y, $width, $height, $this->parseColor($color));

        return $this;
    }

    /**
     * Draw a circle centered at given coordinates
     *
     * @param  string  $color
     * @param  integer $x
     * @param  integer $y
     * @param  integer $radius
     * @param  boolean $filled
     * @return Image
     */
    public function circle($color, $x = 0, $y = 0, $radius = 10, $filled = true)
    {
        return $this->ellipse($color, $x, $y, $radius * 2, $radius * 2, $filled);
    }

    /**
     * Write text in current image
     *
     * @param  string  $text
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @param  integer $angle
     * @param  integer $size
     * @param  string  $color
     * @param  string  $fontfile
     * @return Image
     */
    public function text($text, $pos_x = 0, $pos_y = 0, $size = 16, $color = '000000', $angle = 0, $fontfile = null)
    {
        if (is_null($fontfile)) {

            imagestring($this->resource, $size, $pos_x, $pos_y, $text, $this->parseColor($color));

        } else {

            imagettftext($this->resource, $size, $angle, $pos_x, $pos_y, $this->parseColor($color), $fontfile, $text);

        }

        return $this;
    }

    /**
     * Changes the brightness of the current image
     *
     * @param  int $level [description]
     * @return Image
     */
    public function brightness($level)
    {
        // normalize level
        if ($level >= -100 && $level <= 100) {
            $level = $level * 2.55;
        } else {
            throw new Exception('Brightness level must be between -100 and +100');
        }

        imagefilter($this->resource, IMG_FILTER_BRIGHTNESS, $level);

        return $this;
    }

    /**
     * Changes the contrast of the current image
     *
     * @param  int $level
     * @return Image
     */
    public function contrast($level)
    {
        // normalize level
        if ($level >= -100 && $level <= 100) {
            $level = $level * (-1);
        } else {
            throw new Exception('Contrast level must be between -100 and +100');
        }

        imagefilter($this->resource, IMG_FILTER_CONTRAST, $level);

        return $this;
    }

    /**
     * Pixelate current image
     *
     * @param  integer $size
     * @param  boolean $advanced
     * @return Image
     */
    public function pixelate($size = 10, $advanced = true)
    {
        imagefilter($this->resource, IMG_FILTER_PIXELATE, $size, $advanced);

        return $this;
    }

    /**
     * Turn current image into a greyscale verision
     *
     * @return Image
     */
    public function grayscale()
    {
        imagefilter($this->resource, IMG_FILTER_GRAYSCALE);

        return $this;
    }

    /**
     * Alias of greyscale
     *
     * @return Image
     */
    public function greyscale()
    {
        $this->grayscale();

        return $this;
    }

    /**
     * Invert colors of current image
     *
     * @return Image
     */
    public function invert()
    {
        imagefilter($this->resource, IMG_FILTER_NEGATE);

        return $this;
    }

    /**
     * Apply blur filter on the current image
     *
     * @param  integer $amount
     * @return Image
     */
    public function blur($amount = 1)
    {
        for ($i=0; $i < intval($amount); $i++) {
            imagefilter($this->resource, IMG_FILTER_GAUSSIAN_BLUR);
        }

        return $this;
    }

    /**
     * Set a maximum number of colors for the current image
     *
     * @param integer $count
     * @param mixed   $matte
     * @return Image
     */
    public function limitColors($count = null, $matte = null)
    {
        // create empty canvas
        $resource = imagecreatetruecolor($this->width, $this->height);

        // define matte
        $matte = is_null($matte) ? imagecolorallocatealpha($resource, 0, 0, 0, 127) : $this->parseColor($matte);

        // fill with matte and copy original image
        imagefill($resource, 0, 0, $matte);

        // set transparency
        imagecolortransparent($resource, $matte);

        // copy original image
        imagecopy($resource, $this->resource, 0, 0, 0, 0, $this->width, $this->height);

        if (is_numeric($count) && $count <= 256) {
            // decrease colors
            imagetruecolortopalette($resource, true, intval($count));
        }

        // set new resource
        $this->resource = $resource;

        return $this;
    }

    /**
     * Reset to original image resource
     *
     * @return void
     */
    public function reset()
    {
        if (is_null($this->dirname) && is_null($this->basename)) {

            $this->initEmpty($this->original['width'], $this->original['height'], $this->original['bgcolor']);

        } else {

            $this->initFromPath($this->dirname .'/'. $this->basename);
        }

        return $this;
    }

    /**
     * Encode image in different formats
     *
     * @param string  $format
     * @param integer $quality
     * @return string
     */
    public function encode($format = null, $quality = 90)
    {
        $format = is_null($format) ? $this->type : $format;

        if ($quality < 0 || $quality > 100) {
            throw new Exception('Quality of image must range from 0 to 100.');
        }

        ob_start();

        switch (strtolower($format)) {
            case 'data-url':
                echo sprintf('data:%s;base64,%s', $this->mime, base64_encode($this->encode($this->type, $quality)));
                break;

            case 'gif':
            case 1:
                imagegif($this->resource);
                break;

            case 'png':
            case 3:
                $quality = round($quality / 11.11111111111); // transform quality to png setting
                imagealphablending($this->resource, false);
                imagesavealpha($this->resource, true);
                imagepng($this->resource, null, $quality);
                break;

            default:
            case 'jpg':
            case 'jpeg':
            case 2:
                imagejpeg($this->resource, null, $quality);
                break;
        }

        $data = ob_get_contents();

        ob_end_clean();
        return $data;
    }

    /**
     * Picks and formats color at position
     *
     * @param  int $x
     * @param  int $y
     * @param  string $format
     * @return mixed
     */
    public function pickColor($x, $y, $format = null)
    {
        // pick color at postion
        $color = imagecolorat($this->resource, $x, $y);

        // format color
        switch (strtolower($format)) {
            case 'rgb':
                $color = imagecolorsforindex($this->resource, $color);
                $color = sprintf('rgb(%d, %d, %d)', $color['red'], $color['green'], $color['blue']);
                break;

            case 'rgba':
                $color = imagecolorsforindex($this->resource, $color);
                $color = sprintf(
                    'rgba(%d, %d, %d, %.2f)',
                    $color['red'],
                    $color['green'],
                    $color['blue'],
                    $this->alpha2rgba($color['alpha'])
                );
                break;

            case 'hex':
                $color = imagecolorsforindex($this->resource, $color);
                $color = sprintf('#%02x%02x%02x', $color['red'], $color['green'], $color['blue']);
                break;

            case 'int':
            case 'integer':
                # in gd2 library color already is int...
                break;

            default:
            case 'array':
                $color = imagecolorsforindex($this->resource, $color);
                $color = array(
                    'r' => $color['red'],
                    'g' => $color['green'],
                    'b' => $color['blue'],
                    'a' => $this->alpha2rgba($color['alpha'])
                );
                break;
        }

        return $color;
    }

    /**
     * Allocate color from given string
     *
     * @param  string $value
     * @return int
     */
    public function parseColor($value)
    {
        $a = 0; // alpha value

        if (is_int($value)) {

            // color is alread allocated
            $allocatedColor = $value;

        } elseif (is_array($value)) {

            // parse color array like: array(155, 155, 155)
            if (count($value) == 4) {

                // color array with alpha value
                list($r, $g, $b, $a) = $value;
                $a = $this->alpha2gd($a);

            } elseif (count($value) == 3) {

                // color array without alpha value
                list($r, $g, $b) = $value;

            }

        } elseif (is_string($value)) {

            // parse color string in hexidecimal format like #cccccc or cccccc or ccc
            $hexPattern = '/^#?([a-f0-9]{1,2})([a-f0-9]{1,2})([a-f0-9]{1,2})$/i';

            // parse color string in format rgb(140, 140, 140)
            $rgbPattern = '/^rgb ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3})\)$/i';

            // parse color string in format rgba(255, 0, 0, 0.5)
            $rgbaPattern = '/^rgba ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9.]{1,4})\)$/i';
            
            if (preg_match($hexPattern, $value, $matches)) {

                $r = strlen($matches[1]) == '1' ? '0x'.$matches[1].$matches[1] : '0x'.$matches[1];
                $g = strlen($matches[2]) == '1' ? '0x'.$matches[2].$matches[2] : '0x'.$matches[2];
                $b = strlen($matches[3]) == '1' ? '0x'.$matches[3].$matches[3] : '0x'.$matches[3];
            
            } elseif (preg_match($rgbPattern, $value, $matches)) {

                $r = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
                $g = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
                $b = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
            
            } elseif (preg_match($rgbaPattern, $value, $matches)) {
                
                $r = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
                $g = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
                $b = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
                $a = $this->alpha2gd($matches[4]);
            
            }
        }

        if (isset($allocatedColor)) {

            return $allocatedColor;

        } elseif (isset($r) && isset($g) && isset($b)) {

            return imagecolorallocatealpha($this->resource, $r, $g, $b, $a);

        } else {

            throw new Exception("Error parsing color [{$value}]");
        }
    }

    /**
     * Save image in filesystem
     *
     * @param  string  $path
     * @param  integer $quality
     * @return Image
     */
    public function save($path = null, $quality = 90)
    {
        $path = is_null($path) ? ($this->dirname .'/'. $this->basename) : $path;
        file_put_contents($path, $this->encode(pathinfo($path, PATHINFO_EXTENSION), $quality));

        return $this;
    }

    /**
     * Read Exif data from the current image
     *
     * @param  string $key
     * @return mixed
     */
    public function exif($key = null)
    {
        $data = exif_read_data($this->dirname .'/'. $this->basename, 'EXIF', false);

        if (! is_null($key)) {
            return array_key_exists($key, $data) ? $data[$key] : null;
        }

        return $data;
    }

    /**
     * Convert rgba alpha (0-1) value to gd value (0-127)
     *
     * @param  float $input
     * @return int
     */
    private function alpha2gd($input)
    {
        $range_input = range(1, 0, 1/127);
        $range_output = range(0, 127);

        foreach ($range_input as $key => $value) {
            if ($value <= $input) {
                return $range_output[$key];
            }
        }

        return 127;
    }

    /**
     * Convert gd alpha (0-127) value to rgba alpha value (0-1)
     *
     * @param  int $input
     * @return float
     */
    private function alpha2rgba($input)
    {
        $range_input = range(0, 127);
        $range_output = range(1, 0, 1/127);

        foreach ($range_input as $key => $value) {
            if ($value >= $input) {
                return round($range_output[$key], 2);
            }
        }

        return 1;
    }

    /**
     * Checks if string contains printable characters
     *
     * @param  mixed  $input
     * @return boolean
     */
    private function isBinary($input)
    {
        return ( ! ctype_print($input));
    }

    /**
     * Checks if the input object is image resource
     *
     * @param  mixed  $input
     * @return boolean
     */
    private function isImageResource($input)
    {
        return (is_resource($input) && get_resource_type($input) == 'gd');
    }

    /**
     * Checks if the current image has (half) transparent pixels
     *
     * @return boolean
     */
    private function hasTransparency()
    {
        $step_x = min(max(floor($this->width/50), 1), 10);
        $step_y = min(max(floor($this->height/50), 1), 10);

        for ($x=0; $x<$this->width; $x=$x+$step_x) {
            for ($y=0; $y<$this->height; $y=$y+$step_y) {
                $color = $this->pickColor($x, $y);
                if ($color['a'] < 1) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Set file info from image path in filesystem
     *
     * @param string $path
     */
    private function setFileInfoFromPath($path)
    {
        // set file info
        $info = pathinfo($path);
        $this->dirname = array_key_exists('dirname', $info) ? $info['dirname'] : null;
        $this->basename = array_key_exists('basename', $info) ? $info['basename'] : null;
        $this->extension = array_key_exists('extension', $info) ? $info['extension'] : null;
        $this->filename = array_key_exists('filename', $info) ? $info['filename'] : null;
    }

    /**
     * Set image info from image path in filesystem
     *
     * @param string $path
     */
    private function setImageInfoFromPath($path)
    {
        $info = getimagesize($path);
        $this->width = $info[0];
        $this->height = $info[1];
        $this->type = $info[2];
        $this->mime = $info['mime'];

        // set resource
        switch ($this->type) {
            case IMG_PNG:
            case 3:
                $this->resource = imagecreatefrompng($path);
                break;

            case IMG_JPG:
            case 2:
                $this->resource = imagecreatefromjpeg($path);
                break;

            case IMG_GIF:
            case 1:
                $this->resource = imagecreatefromgif($path);
                break;

            default:
                throw new Exception("Wrong image type ({$this->type}) only use JPG, PNG or GIF images.");
                break;
        }
    }

    /**
     * Set local image info from GD resource
     *
     * @param resource $resource
     */
    private function setImageInfoFromResource($resource)
    {
        $this->resource = $resource;
        $this->width = imagesx($this->resource);
        $this->height = imagesy($this->resource);
        $this->original['width'] = $this->width;
        $this->original['height'] = $this->height;
    }

    /**
     * Set local image information from image string
     *
     * @param string $string
     */
    private function setImageInfoFromString($string)
    {
        $this->resource = imagecreatefromstring($string);
        $this->width = imagesx($this->resource);
        $this->height = imagesy($this->resource);
        $this->original['width'] = $this->width;
        $this->original['height'] = $this->height;
    }

    /**
     * Returns image stream
     *
     * @return string
     */
    public function __toString()
    {
        return $this->encode();
    }
}
