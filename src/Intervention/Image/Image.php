<?php

namespace Intervention\Image;

use Exception;
use Illuminate\Filesystem\Filesystem;

class Image
{
    /**
     * The image resource identifier of current image
     * @var resource
     */
    public $resource;

    /**
     * Type of current image
     * @var string
     */
    public $type;

    /**
     * Width of current image
     * @var integer
     */
    public $width;

    /**
     * Height of current image
     * @var integer
     */
    public $height;
    
    /**
     * Directory path of current image
     * @var string
     */
    public $dirname;

    /**
     * Trailing name component of current image filename
     * @var string
     */
    public $basename;

    /**
     * File extension of current image filename
     * @var string
     */
    public $extension;

    /**
     * Combined filename (basename and extension)
     * @var string
     */
    public $filename;

    /**
     * Instance of Illuminate\Filesystem\Filesystem
     * @var Filesystem
     */
    protected $filesystem;
    
    /**
     * Create a new instance of Image class
     * 
     * @param string $path
     */
    public function __construct($path = null, $width = null, $height = null) 
    {
        $this->filesystem = new Filesystem;
        $this->setProperties($path, $width, $height);
    }

    /**
     * Create a new image resource from image file
     * 
     * @param  string $path
     * @return Image
     */
    public static function make($path)
    {
        return new Image($path);
    }

    /**
     * Set local properties for image resource
     * 
     * @param string $path
     */
    private function setProperties($path, $width = null, $height = null)
    {
        if ( ! is_null($path) && $this->filesystem->exists($path)) {
            
            // set file info
            $info = pathinfo($path);
            $this->dirname = $info['dirname'];
            $this->basename = $info['basename'];
            $this->extension = $info['extension'];
            $this->filename = $info['filename'];

            // set image info
            list($this->width, $this->height, $this->type) = @getimagesize($path);

            // set resource
            switch ($this->type) {
                case IMG_PNG:
                case 3:
                    $this->resource = @imagecreatefrompng($path);
                    break;
                
                case IMG_JPG:
                    $this->resource = @imagecreatefromjpeg($path);
                    break;

                case IMG_GIF:
                    $this->resource = @imagecreatefromgif($path);
                    break;

                default:
                    throw new Exception("Wrong image type ({$this->type}) only use JPG, PNG or GIF images.");
                    break;
            }

        } else {
            
            $this->width = is_numeric($width) ? intval($width) : 1;
            $this->height = is_numeric($height) ? intval($height) : 1;

            // create empty image
            $this->resource = @imagecreatetruecolor($this->width, $this->height);

            // fill with transparent background instead of black
            $transparent = imagecolorallocatealpha($this->resource, 0, 0, 0, 127);
            imagefill($this->resource, 0, 0, $transparent);
        }
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
    private function modify($dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h)
    {
        // create new image
        $image = @imagecreatetruecolor($dst_w, $dst_h);

        // copy content from resource        
        @imagecopyresampled($image, $this->resource, $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);

        // set new content as recource
        $this->resource = $image;

        // set new dimensions
        $this->width = $dst_w;
        $this->height = $dst_h;

        return $this;
    }

    /**
     * Resize current image based on given width/height
     * 
     * @param  mixed width|height  width and height are optional, the not given 
     *                             parameter is calculated based on the given
     * @return Image
     */
    public function resize()
    {
        $args = func_get_args();
        
        if (array_key_exists(0, $args) && is_array($args[0])) {
            
            // extract 'width' and 'height'            
            extract(array_only($args[0], array('width', 'height')));
            $width = isset($width) ? intval($width) : null;
            $height = isset($height) ? intval($height) : null;
            
            if ( ! is_null($width) OR ! is_null($height)) {
                // if width or height are not set, define values automatically
                $width = is_null($width) ? intval($height / $this->height * $this->width) : $width;
                $height = is_null($height) ? intval($width / $this->width * $this->height) : $height;
            } else {
                // width or height not defined (resume with original values)
                throw new Exception('Width or Height needs to be defined as keys in paramater array');
            }

        } elseif (array_key_exists(0, $args) && array_key_exists(1, $args) && is_numeric($args[0]) && is_numeric($args[1])) {
            $width = intval($args[0]);
            $height = intval($args[1]);
        }

        if (is_null($width) OR is_null($height)) {
            throw new Exception('width or height needs to be defined');
        }

        // create new image in new dimensions
        return $this->modify(0, 0, 0, 0, $width, $height, $this->width, $this->height);
    }

    /**
     * Cut out a detail of the image in given ratio and resize to output size
     * 
     * @param mixed width|height    width and height are optional, the not given
     *                              parameter is calculated based on the given
     * @return Image
     */
    public function grab()
    {
        $args = func_get_args();

        if (array_key_exists(0, $args) && is_array($args[0])) {
            // extract 'width' and 'height'            
            extract(array_only($args[0], array('width', 'height')));
            $width = isset($width) ? intval($width) : null;
            $height = isset($height) ? intval($height) : null;
            
            if ( ! is_null($width) OR ! is_null($height)) {
                // if width or height are not set, define values automatically
                $width = is_null($width) ? $height : $width;
                $height = is_null($height) ? $width : $height;
            } else {
                // width or height not defined (resume with original values)
                throw new Exception('Width or Height needs to be defined as keys in paramater array');
            }

        } elseif (array_key_exists(0, $args) && array_key_exists(1, $args) && is_numeric($args[0]) && is_numeric($args[1])) {
            
            $width = intval($args[0]);
            $height = intval($args[1]);

        } elseif (array_key_exists(0, $args) && is_numeric($args[0])) {
            
            $width = intval($args[0]);
            $height = intval($args[0]);

        }

        if (is_null($width) OR is_null($height)) {
            throw new Exception('width or height needs to be defined');
        }

        // ausschnitt berechnen
        $grab_width = $this->width;
        $ratio = $grab_width / $width;

        if($height * $ratio <= $this->height) {
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
     * Insert another image on top of the current image
     * 
     * @param  string  $file
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @return Image
     */
    public function insert($file, $pos_x = 0, $pos_y = 0)
    {
        $obj = is_a($file, 'Intervention\Image\Image') ? $file : (new Image($file));
        imagecopy($this->resource, $obj->resource, $pos_x, $pos_y, 0, 0, $obj->width, $obj->height);

        return $this;
    }

    /**
     * Fill image with given hexadecimal color at position x,y
     * 
     * @param  string  $color
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @return Image
     */
    public function fill($color, $pos_x = 0, $pos_y = 0)
    {
        imagefill($this->resource, $pos_x, $pos_y, $this->parseColor($color));
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
     * @param  integer $x      
     * @param  integer $y      
     * @param  integer $width  
     * @param  integer $height 
     * @return Image
     */
    public function ellipse($color, $x = 0, $y = 0, $width = 10, $height = 10, $filled = true)
    {
        $callback = $filled ? 'imagefilledellipse' : 'imageellipse';
        call_user_func($callback, $this->resource, $x, $y, $width, $height, $this->parseColor($color));
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
        return $this->ellipse($color, $x, $y, $radius, $radius, $filled);
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
     * Reset to original image resource
     * 
     * @return void
     */
    public function reset()
    {   
        $this->setProperties($this->dirname .'/'. $this->basename);
        return $this;
    }

    /**
     * Returns image type stream
     * 
     * @param string $type gif|png|jpg|jpeg
     * @param integer quality
     * @return string
     */
    private function data($type = null, $quality = 90)
    {
        ob_start();

        switch (strtolower($type)) {
            case 'gif':
                @imagegif($this->resource);
            break;

            case 'png':
                @imagealphablending($this->resource, false);
                @imagesavealpha($this->resource, true);
                @imagepng($this->resource);
            break;

            default:
            case 'jpg':
            case 'jpeg':
                @imagejpeg($this->resource, null, $quality);
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

            case 'hex':
                $color = imagecolorsforindex($this->resource, $color);
                $color = sprintf('#%02x%02x%02x', $color['red'], $color['green'], $color['blue']);
                break;
            
            case 'array':
                $color = imagecolorsforindex($this->resource, $color);
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
        if (is_array($value)) {

            // parse color array like: array(155, 155, 155)
            list($r, $g, $b) = $value;

        } elseif(is_string($value)) {

            // parse color string in hexidecimal format like #cccccc or cccccc or ccc
            if (preg_match('/^#?([a-f0-9]{1,2})([a-f0-9]{1,2})([a-f0-9]{1,2})$/i', $value, $matches)) {

                $r = strlen($matches[1]) == '1' ? $matches[1].$matches[1] : $matches[1];
                $g = strlen($matches[2]) == '1' ? $matches[2].$matches[2] : $matches[2];
                $b = strlen($matches[3]) == '1' ? $matches[3].$matches[3] : $matches[3];

            // parse color string in format rgb(140, 140, 140)
            } elseif (preg_match('/^rgb ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3})\)$/i', $value, $matches)) {
                
                $r = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
                $g = ($matches[1] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
                $b = ($matches[1] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;

            }
        }

        if (isset($r) && isset($g) && isset($b)) {

            return imagecolorallocate($this->resource, '0x'.$r, '0x'.$g, '0x'.$b);
            
        } else {
            
            throw new Exception("Error parsing color [{$value}]");
        }
    }

    /**
     * Save image in filesystem
     * 
     * @param  string $path
     * @return Image 
     */
    public function save($path = null, $quality = 90)
    {
        $path = is_null($path) ? ($this->dirname .'/'. $this->basename) : $path;
        file_put_contents($path, $this->data($this->filesystem->extension($path), $quality));
        return $this;
    }

    /*
    public function render($type = 'jpg')
    {
        return Response::make($this->data($type), 200, 
                array('content-type' => $this->filesystem->mime($type)));
    }
    */

    /**
     * Return filesystem object
     * 
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Returns image stream
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->data();
    }
}