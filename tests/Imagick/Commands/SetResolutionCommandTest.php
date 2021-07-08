<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\ImageManager;
use Intervention\Image\Resolution;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Intervention\Image\Imagick\Commands\SetResolutionCommand
 */
class SetResolutionCommandTest extends TestCase
{
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        // PPI
        $image    = (new ImageManager(['driver' => 'imagick']))->canvas(32, 32);
        $expected = new Resolution(128, 128, Resolution::UNITS_PPI);

        $image->setResolution($expected);

        $this->assertEquals($expected, $image->getResolution());

        // PPCM
        $ppcm     = new Resolution(128, 128, Resolution::UNITS_PPCM);
        $expected = clone $ppcm;

        $image->setResolution($ppcm);

        $this->assertEquals($expected, $image->getResolution());
    }
}
