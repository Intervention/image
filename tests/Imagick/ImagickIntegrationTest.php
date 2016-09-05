<?php

class ImagickIntegrationTest extends AbstractIntegrationTestCase
{
    protected function manager()
    {
        return new \Intervention\Image\ImageManager(array(
            'driver' => 'imagick'
        ));
    }

    protected function core()
    {
        $imagick = new \Imagick;
        $imagick->readImage('tests/images/circle.png');

        return $imagick;
    }

    protected function assertImage($img)
    {
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertImageCore($img->getCore());
    }

    protected function assertImageCore($core)
    {
        $this->assertInstanceOf('Imagick', $core);
    }
}
