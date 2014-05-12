<?php

namespace Intervention\Image;

class Response
{
    public $image;
    public $format;
    public $quality;

    public function __construct(Image $image, $format = null, $quality = null) 
    {
        $this->image = $image;
        $this->format = $format ? $format : $image->mime;
        $this->quality = $quality ? $quality : 90;
    }

    public function make()
    {
        $this->image->encode($this->format, $this->quality);
        $data = $this->image->getEncoded();
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);

        if (function_exists('app') && is_a($app = app(), 'Illuminate\Foundation\Application')) {
            
            $response = \Response::make($data);
            $response->header('Content-Type', $mime);

        } else {
            
            header('Content-Type: ' . $mime);
            $response = $data;
        }

        return $response;
    }
}
