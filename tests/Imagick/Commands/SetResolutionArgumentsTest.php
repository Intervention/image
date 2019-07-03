<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Commands\AbstractCommand;
use Intervention\Image\Exception\InvalidArgumentException;
use Intervention\Image\Image;
use Intervention\Image\Resolution;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Intervention\Image\Imagick\Commands\SetResolutionArguments
 */
class SetResolutionArgumentsTest extends TestCase
{
    // <editor-fold desc="Tests">
    // =========================================================================
    /**
     * @covers ::getInputResolution()
     *
     * @dataProvider dataProviderGetInputResolution
     *
     * @param array|string $expected
     * @param array $args
     *
     * @return void
     */
    public function testGetInputResolution($expected, array $args): void
    {
        /** @var \Intervention\Image\Image|\Mockery\MockInterface $image */
        $resolution = new Resolution(100, 200, Resolution::UNITS_PPI);
        $image      = Mockery::mock(Image::class)->shouldAllowMockingProtectedMethods();
        $image->shouldReceive('getResolution')->andReturn($resolution);

        $trait = new class($args) extends AbstractCommand {
            use SetResolutionArguments;

            public function execute($image) {
                return $this->getInputResolution($image);
            }
        };

        if (is_string($expected)) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage($expected);

            $trait->execute($image);
        } else {
            $this->assertEquals(new Resolution(...$expected), $trait->execute($image));
        }
    }
    // </editor-fold>

    // <editor-fold desc="Providers">
    // =========================================================================
    public function dataProviderGetInputResolution(): array
    {
        // @formatter:off
        return [
            //                              expected                                                        args
            'setResolution()'            => ['setResolution() expects at least 1 parameters, 0 given', []],
            'setResolution(5+)'          => ['setResolution() expects at most 3 parameters, 5 given', [1, 2, 3, 4, 5]],
            'setResolution(Resolution)'  => [[5, 10, Resolution::UNITS_PPI], [new Resolution(5, 10, Resolution::UNITS_PPI)]],
            'setResolution(xy)'          => [[10, 10, Resolution::UNITS_PPI], [10]],
            'setResolution(x, y)'        => [[15, 10, Resolution::UNITS_PPI], [15, 10]],
            'setResolution(xy, units)'   => [[15, 15, Resolution::UNITS_PPCM], [15, Resolution::UNITS_PPCM]],
            'setResolution(x, y, units)' => [[25, 15, Resolution::UNITS_PPCM], [25, 15, Resolution::UNITS_PPCM]],
        ];
        // @formatter:on
    }
    //</editor-fold>
}
