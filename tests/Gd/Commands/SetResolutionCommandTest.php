<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\ImageManager;
use Intervention\Image\Resolution;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Intervention\Image\Gd\Commands\SetResolutionCommand
 */
class SetResolutionCommandTest extends TestCase
{
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        // Supported?
        if (!function_exists('imageresolution')) {
            $this->markTestSkipped('Function `imageresolution()` is not defined.');
        }

        // Test
        $image    = (new ImageManager(['driver' => 'gd']))->canvas(32, 32);
        $expected = new Resolution(128, 128, Resolution::UNITS_PPI);

        $image->setResolution($expected);

        $this->assertEquals($expected, $image->getResolution());

        // GD always uses PPI
        $ppcm     = new Resolution(128, 128, Resolution::UNITS_PPCM);
        $expected = (clone $ppcm)->setUnits(Resolution::UNITS_PPI);

        $image->setResolution($ppcm);

        $this->assertEquals($expected, $image->getResolution());
    }
}
