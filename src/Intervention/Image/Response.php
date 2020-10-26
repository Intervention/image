<?php

namespace Intervention\Image;

use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Response as IlluminateResponseFacade;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
     * @var int
     */
    public $quality;

    /**
     * Creates a new instance of response
     *
     * @param Image   $image
     * @param string  $format
     * @param int     $quality
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
        $length = strlen($data);

        if (function_exists('app') && app() instanceof \Illuminate\Container\Container) {

            $response = IlluminateResponseFacade::getFacadeApplication()
                ? IlluminateResponseFacade::make($data)
                : new IlluminateResponse($data);
            $response->header('Content-Type', $mime);
            $response->header('Content-Length', $length);

        } elseif (class_exists('\Symfony\Component\HttpFoundation\Response')) {

            $response = SymfonyResponse::create($data);
            $response->headers->set('Content-Type', $mime);
            $response->headers->set('Content-Length', $length);

        } else {

            header('Content-Type: ' . $mime);
            header('Content-Length: ' . $length);
            $response = $data;
        }

        return $response;
    }
}
