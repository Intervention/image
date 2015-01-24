<?php

use Intervention\Image\Gd\Commands\GammaCommand as GammaGd;
use Intervention\Image\Imagick\Commands\GammaCommand as GammaImagick;

class GammaCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');

        $command = new GammaGd(array(1.4));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('gammaimage')->times(3)->with(1.4)->andReturn(true);

        $command = new GammaImagick(array(1.4));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
