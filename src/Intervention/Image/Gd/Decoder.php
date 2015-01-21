<?php

namespace Intervention\Image\Gd;

use \Intervention\Image\Image;
use \Intervention\Image\Size;
use \Intervention\Image\ContainerInterface;

class Decoder extends \Intervention\Image\AbstractDecoder
{
    /**
     * Initiates new image from path in filesystem
     *
     * @param  string $path
     * @return \Intervention\Image\Image
     */
    public function initFromPath($path)
    {
        $info = @getimagesize($path);

        if ($info === false) {
            throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to read image from file ({$path})."
            );
        }

        // try to decode animated gif
        if ($info['mime'] == 'image/gif') {
            
            return $this->initFromBinary(file_get_contents($path));

        } else {

            // define core
            switch ($info[2]) {
                case IMAGETYPE_PNG:
                $core = imagecreatefrompng($path);
                Helper::gdResourceToTruecolor($core);
                break;

                case IMAGETYPE_JPEG:
                $core = imagecreatefromjpeg($path);
                Helper::gdResourceToTruecolor($core);
                break;

                case IMAGETYPE_GIF:
                $core = imagecreatefromgif($path);
                Helper::gdResourceToTruecolor($core);
                break;

                default:
                throw new \Intervention\Image\Exception\NotReadableException(
                    "Unable to read image type. GD driver is only able to decode JPG, PNG or GIF files."
                    );
            }

            // build image
            $image = $this->initFromGdResource($core);
        }

        $image->mime = $info['mime'];
        $image->setFileInfoFromPath($path);

        return $image;
    }

    /**
     * Initiates new image from GD resource
     *
     * @param  Resource $resource
     * @return \Intervention\Image\Image
     */
    public function initFromGdResource($resource)
    {
        $driver = new Driver;
        
        return new Image($driver, $driver->newContainer($resource));
    }


    public function initFromContainer(Container $container)
    {
        $driver = new Driver;

        return new Image($driver, $container);
    }

    /**
     * Initiates new image from Imagick object
     *
     * @param  Imagick $object
     * @return \Intervention\Image\Image
     */
    public function initFromImagick(\Imagick $object)
    {
        throw new \Intervention\Image\Exception\NotSupportedException(
            "Gd driver is unable to init from Imagick object."
        );
    }

    /**
     * Initiates new image from binary data
     *
     * @param  string $data
     * @return \Intervention\Image\Image
     */
    public function initFromBinary($binary)
    {
        try {
            // try to custom decode gif
            $gifDecoder = new Gif\Decoder;
            $decoded = $gifDecoder->initFromData($binary)->decode();

            // create image
            $image = $this->initFromContainer($decoded->createContainer());
            $image->mime = 'image/gif';

        } catch (\Exception $e) {
            
            $resource = @imagecreatefromstring($binary);    

            if ($resource === false) {
                throw new \Intervention\Image\Exception\NotReadableException(
                    "Unable to init from given binary data."
                );
            }

            // create image
            $image = $this->initFromGdResource($resource);
            $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);

        }

        return $image;
    }

    /**
     * Initiates new image from container object
     *
     * @param  ContainerInterface $container
     * @return \Intervention\Image\Image
     */
    public function initFromInterventionContainer(ContainerInterface $container)
    {
        return new Image(new Driver, $container);
    }
}
