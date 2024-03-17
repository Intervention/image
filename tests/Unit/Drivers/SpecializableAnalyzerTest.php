<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\SpecializableAnalyzer;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

final class SpecializableAnalyzerTest extends BaseTestCase
{
    public function testAnalyzer(): void
    {
        $analyzer = Mockery::mock(SpecializableAnalyzer::class)->makePartial();
        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('analyze')->andReturn('test');

        $result = $analyzer->analyze($image);
        $this->assertEquals('test', $result);
    }
}
