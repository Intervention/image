<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\SpecializableAnalyzer;
use Intervention\Image\Exceptions\LogicException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SpecializableAnalyzer::class)]
final class SpecializableAnalyzerTest extends BaseTestCase
{
    public function testAnalyze(): void
    {
        $analyzer = Mockery::mock(SpecializableAnalyzer::class)->makePartial();
        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('analyze')->andReturn('test');
        $result = $analyzer->analyze($image);
        $this->assertEquals('test', $result);
    }

    public function testAnalyzeThrowsWhenSpecializedWithoutOverride(): void
    {
        $analyzer = new class () extends SpecializableAnalyzer implements SpecializedInterface {
            protected function belongsToDriver(object $driver): bool
            {
                return true;
            }
        };

        $image = Mockery::mock(ImageInterface::class);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('must override analyze()');
        $analyzer->analyze($image);
    }
}
