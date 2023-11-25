<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers;

use Intervention\Image\Drivers\DriverAnalyzer;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\DriverAnalyzer
 *
 * @internal
 */
class DriverAnalyzerTest extends TestCase
{
    public function testDriver(): void
    {
        $analyzer = Mockery::mock(DriverAnalyzer::class, [
            Mockery::mock(AnalyzerInterface::class),
            Mockery::mock(DriverInterface::class)
        ])->makePartial();

        $this->assertInstanceOf(DriverInterface::class, $analyzer->driver());
    }
}
