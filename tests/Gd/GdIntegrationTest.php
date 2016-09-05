<?php

class GdIntegrationTest extends AbstractIntegrationTestCase
{
    protected function manager()
    {
        return new \Intervention\Image\ImageManager(array(
            'driver' => 'gd'
        ));
    }

    protected function core()
    {
        return imagecreatefrompng('tests/images/circle.png');
    }

    protected function assertImage($img)
    {
        $this->assertInstanceOf('Intervention\Image\Image', $img);
        $this->assertImageCore($img->getCore());
    }

    protected function assertImageCore($core)
    {
        $this->assertInternalType('resource', $core);
    }
}
