<?php

namespace Intervention\Image\Gd;

class Helper
{
    /**
     * Transform GD resource into Truecolor version
     *
     * @param  resource $resource
     * @return bool
     */
    public static function gdResourceToTruecolor(&$resource)
    {
        $width = imagesx($resource);
        $height = imagesy($resource);

        // new canvas
        $canvas = imagecreatetruecolor($width, $height);

        // fill with transparent color
        imagealphablending($canvas, false);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
        imagecolortransparent($canvas, $transparent);
        imagealphablending($canvas, true);

        // copy original
        imagecopy($canvas, $resource, 0, 0, 0, 0, $width, $height);
        imagedestroy($resource);

        $resource = $canvas;

        return true;
    }

    /**
     * Clone GD resource and return clone
     *
     * @param  resource $resource
     * @return resource
     */
    public static function cloneResource($resource)
    {
        $width = imagesx($resource);
        $height = imagesy($resource);
        $clone = imagecreatetruecolor($width, $height);
        imagealphablending($clone, true);
        imagesavealpha($clone, true);
        
        imagecopy($clone, $resource, 0, 0, 0, 0, $width, $height);

        return $clone;
    }
}
