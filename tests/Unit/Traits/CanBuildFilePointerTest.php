<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Traits;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Traits\CanBuildFilePointer;

final class CanBuildFilePointerTest extends BaseTestCase
{
    public function testBuildFilePointerFromNull(): void
    {
        $builder = $this->createBuilder();
        $result = $builder->buildFilePointerOrFail(null);
        $this->assertIsResource($result);
        fclose($result);
    }

    public function testBuildFilePointerFromString(): void
    {
        $builder = $this->createBuilder();
        $result = $builder->buildFilePointerOrFail('test data');
        $this->assertIsResource($result);
        $this->assertEquals('test data', stream_get_contents($result));
        fclose($result);
    }

    public function testBuildFilePointerFromResource(): void
    {
        $builder = $this->createBuilder();
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'resource data');
        $result = $builder->buildFilePointerOrFail($resource);
        $this->assertIsResource($result);
        $this->assertEquals('resource data', stream_get_contents($result));
        fclose($result);
    }

    public function testBuildFilePointerFailsWithInvalidType(): void
    {
        $builder = $this->createBuilder();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to create file pointer');
        $builder->buildFilePointerOrFail(12345);
    }

    /**
     * Create an anonymous class that exposes the trait method.
     */
    private function createBuilder(): object
    {
        return new class () {
            use CanBuildFilePointer;
        };
    }
}
