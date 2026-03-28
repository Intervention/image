<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Traits;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Traits\CanBuildStream;

final class CanBuildStreamTest extends BaseTestCase
{
    public function testBuildStreamFromNull(): void
    {
        $builder = $this->createBuilder();
        $result = $builder::buildStreamOrFail(null);
        $this->assertIsResource($result);
        fclose($result);
    }

    public function testBuildStreamFromString(): void
    {
        $builder = $this->createBuilder();
        $result = $builder::buildStreamOrFail('test data');
        $this->assertIsResource($result);
        $this->assertEquals('test data', stream_get_contents($result));
        fclose($result);
    }

    public function testBuildStreamFromResource(): void
    {
        $builder = $this->createBuilder();
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'resource data');
        $result = $builder::buildStreamOrFail($resource);
        $this->assertIsResource($result);
        $this->assertEquals('resource data', stream_get_contents($result));
        fclose($result);
    }

    public function testBuildStreamFailsWithInvalidType(): void
    {
        $builder = $this->createBuilder();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to create stream');
        $builder::buildStreamOrFail(12345);
    }

    /**
     * Create an anonymous class that exposes the trait method.
     */
    private function createBuilder(): object
    {
        return new class () {
            use CanBuildStream;
        };
    }
}
