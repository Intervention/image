<?php

namespace Intervention\Image\Gd;

class Encoder extends \Intervention\Image\AbstractEncoder
{
    protected function processJpeg()
    {
        ob_start();
        imagejpeg($this->image->getCore(), null, $this->quality);
        $this->image->mime = image_type_to_mime_type(IMAGETYPE_JPEG);
        $buffer = ob_get_contents();
        ob_end_clean();
        
        return $buffer;
    }

    protected function processPng()
    {
        ob_start();
        $resource = $this->image->getCore();
        imagealphablending($resource, false);
        imagesavealpha($resource, true);
        imagepng($resource, null, -1);
        $this->image->mime = image_type_to_mime_type(IMAGETYPE_PNG);
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    protected function processGif()
    {
        ob_start();
        imagegif($this->image->getCore());
        $this->image->mime = image_type_to_mime_type(IMAGETYPE_GIF);
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}
