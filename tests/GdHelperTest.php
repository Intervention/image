<?php

use Intervention\Image\Gd\Helper;

class GdHelperTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGdResourceToTruecolor()
    {
        $resource = imagecreate(10, 10);
        $this->assertFalse(imageistruecolor($resource));
        Helper::gdResourceToTruecolor($resource);
        $this->assertTrue(imageistruecolor($resource));
    }

    public function testCloneResource()
    {
        $resource = imagecreate(10, 10);
        $clone = Helper::cloneResource($resource);
        $this->assertNotEquals($resource, $clone);
    }
}
