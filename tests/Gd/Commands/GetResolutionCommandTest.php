<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\ImageManager;
use Intervention\Image\Resolution;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Intervention\Image\Gd\Commands\GetResolutionCommand
 */
class GetResolutionCommandTest extends TestCase
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

        // Test (96 is the default GD resolution)
        $image    = (new ImageManager(['driver' => 'gd']))->canvas(32, 32);
        $expected = new Resolution(96, 96, Resolution::UNITS_PPI);

        $this->assertEquals($expected, $image->getResolution());
    }
}
