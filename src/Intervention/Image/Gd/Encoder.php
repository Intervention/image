<?php

namespace Intervention\Image\Gd;

class Encoder extends \Intervention\Image\AbstractEncoder
{
    /**
     * Processes and returns encoded image as JPEG string
     *
     * @return string
     */
    protected function processJpeg()
    {
        ob_start();
        imagejpeg($this->image->getCore(), null, $this->quality);
        $this->image->mime = image_type_to_mime_type(IMAGETYPE_JPEG);
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    /**
     * Processes and returns encoded image as PNG string
     *
     * @return string
     */
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

    /**
     * Processes and returns encoded image as GIF string
     *
     * @return string
     */
    protected function processGif()
    {
        $image = $this->image;

        $encoder = new Gif\Encoder;
        $encoder->setCanvas($image->getWidth(), $image->getHeight());
        $encoder->setLoops($image->getContainer()->getLoops());

        // set frames
        foreach ($image as $frame) {

            // extract each frame
            ob_start();
            imagegif($frame->getCore());
            $frame_data = ob_get_contents();
            ob_end_clean();

            // decode frame
            $decoder = new Gif\Decoder;
            $decoder->initFromData($frame_data);
            $decoded = $decoder->decode();

            // add each frame
            $encoder->addFrame(
                $decoded->getFrame()
                    ->setLocalColorTable($decoded->getGlobalColorTable())
                    ->setDelay($frame->delay)
            );
        }
        
        return $encoder->encode();
    }

    /**
     * Processes and returns encoded image as TIFF string
     *
     * @return string
     */
    protected function processTiff()
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "TIFF format is not supported by Gd Driver."
        );
    }

    /**
     * Processes and returns encoded image as BMP string
     *
     * @return string
     */
    protected function processBmp()
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "BMP format is not supported by Gd Driver."
        );
    }

    /**
     * Processes and returns encoded image as ICO string
     *
     * @return string
     */
    protected function processIco()
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "ICO format is not supported by Gd Driver."
        );
    }

    /**
     * Processes and returns encoded image as PSD string
     *
     * @return string
     */
    protected function processPsd()
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "PSD format is not supported by Gd Driver."
        );
    }
}
