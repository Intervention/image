<?php

namespace Intervention\Image\Imagick\Commands;

use Imagick;
use Intervention\Image\ImageManager;
use Intervention\Image\Resolution;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Intervention\Image\Imagick\Commands\GetResolutionCommand
 */
class GetResolutionCommandTest extends TestCase
{
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        /** @var \Imagick $imagick */
        $map      = [
            Imagick::RESOLUTION_UNDEFINED           => Resolution::UNITS_UNKNOWN,
            Imagick::RESOLUTION_PIXELSPERINCH       => Resolution::UNITS_PPI,
            Imagick::RESOLUTION_PIXELSPERCENTIMETER => Resolution::UNITS_PPCM,
        ];
        $image    = (new ImageManager(['driver' => 'imagick']))->canvas(32, 32);
        $imagick  = $image->getCore();
        $x        = $imagick->getImageResolution()['x'];
        $y        = $imagick->getImageResolution()['y'];
        $units    = $map[$imagick->getImageUnits()];
        $expected = new Resolution($x, $y, $units);

        $this->assertEquals($expected, $image->getResolution());
    }
}
