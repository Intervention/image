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
        // Add a webp exception as support (GD in particular) is pretty patchy at the
        // moment (201501). Once GD, fileinfo et al consistently recognise webp as a
        // legit format, this can be pulled back out.
        if ($image->mime == 'application/octet-stream' && Image::detectWebp($image->encoded)) {
            $image->mime = 'image/webp';
        }

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

        // WebP not recognised in finfo yet - without this exception, the
        // mime type comes back as application/octet-stream, so the browser
        // downloads rather than shows the image
        if ($this->format == 'webp' || $this->format == 'image/webp') {
            $mime = 'image/webp';
        } else {
            $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
        }

        $length = strlen($data);

        if (function_exists('app') && is_a($app = app(), 'Illuminate\Foundation\Application')) {

            $response = \Illuminate\Support\Facades\Response::make($data);
            $response->header('Content-Type', $mime);
            $response->header('Content-Length', $length);

        } else {

            header('Content-Type: ' . $mime);
            header('Content-Length: ' . $length);
            $response = $data;
        }

        return $response;
    }
}
