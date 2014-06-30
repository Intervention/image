<?php

namespace Intervention\Image;

class Response
{
    /**
     * Image that should be displayed by response
     *
     * @var Image
     */
    public $image;

    /**
     * Format of displayed image
     *
     * @var string
     */
    public $format;

    /**
     * Quality of displayed image
     *
     * @var integer
     */
    public $quality;

    /**
     * Creates a new instance of response
     *
     * @param Image   $image
     * @param string  $format
     * @param integer $quality
     */
    public function __construct(Image $image, $format = null, $quality = null)
    {
        $this->image = $image;
        $this->format = $format ? $format : $image->mime;
        $this->quality = $quality ? $quality : 90;
    }

    /**
     * Builds response according to settings
     *
     * @return mixed
     */
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
