<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\AnimationFactory;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(AnimationFactory::class)]
class AnimationFactoryTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnimationProcess(DriverInterface $driver): void
    {
        $image = (new AnimationFactory(12, 4, function (AnimationFactory $animation): void {
            $animation->add(Resource::create('red.gif')->path(), .2);
            $animation->add(Resource::create('green.gif')->path(), .2);
            $animation->add(Resource::create('blue.gif')->path(), .2);
        }))->image($driver);

        $this->assertEquals(12, $image->width());
        $this->assertEquals(4, $image->height());
        $this->assertEquals(3, $image->count());
        $this->assertEquals(0, $image->loops());
        foreach ($image as $frame) {
            $this->assertEquals(.2, $frame->delay());
        }
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnimationEmptyCallback(DriverInterface $driver): void
    {
        $image = (new AnimationFactory(12, 4, function (): void {
            //
        }))->image($driver);

        $this->assertEquals(12, $image->width());
        $this->assertEquals(4, $image->height());
        $this->assertEquals(1, $image->count());
        $this->assertEquals(0, $image->loops());
        $this->assertColor(255, 255, 255, 0, $image->colorAt(0, 0));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnimationEmptyFactory(DriverInterface $driver): void
    {
        $image = (new AnimationFactory(12, 4))->image($driver);

        $this->assertEquals(12, $image->width());
        $this->assertEquals(4, $image->height());
        $this->assertEquals(1, $image->count());
        $this->assertEquals(0, $image->loops());
        $this->assertColor(255, 255, 255, 0, $image->colorAt(0, 0));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testBuild(DriverInterface $driver): void
    {
        $image = AnimationFactory::build(12, 4, fn($animation) => $animation, $driver);

        $this->assertEquals(12, $image->width());
        $this->assertEquals(4, $image->height());
        $this->assertEquals(1, $image->count());
        $this->assertEquals(0, $image->loops());
        $this->assertColor(255, 255, 255, 0, $image->colorAt(0, 0));
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testCallMagicMethodOnFrame(DriverInterface $driver): void
    {
        $image = (new AnimationFactory(12, 4, function (AnimationFactory $animation): void {
            $animation->add(Resource::create('red.gif')->path(), .2)->grayscale();
        }))->image($driver);

        $this->assertEquals(12, $image->width());
        $this->assertEquals(4, $image->height());
        $this->assertEquals(1, $image->count());
    }

    public function testCallMagicMethodWithInvalidMethod(): void
    {
        $this->expectException(\Error::class);
        $factory = new AnimationFactory(12, 4);
        $factory->add('test', .2);
        $factory->nonExistentMethod();
    }

    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testBuildFrameWithColorSource(DriverInterface $driver): void
    {
        // Use a color string as source — triggers DecoderException catch path in buildFrame
        $image = (new AnimationFactory(12, 4, function (AnimationFactory $animation): void {
            $animation->add('ff0000', .5);
        }))->image($driver);

        $this->assertEquals(12, $image->width());
        $this->assertEquals(4, $image->height());
        $this->assertEquals(1, $image->count());
    }
}
