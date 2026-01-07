<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\AnimationFactory;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\DriverProvider;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class AnimationFactoryTest extends BaseTestCase
{
    #[DataProviderExternal(DriverProvider::class, 'drivers')]
    public function testAnimationProcess(DriverInterface $driver): void
    {
        $image = AnimationFactory::build($driver, 12, 4, function (AnimationFactory $animation): void {
            $animation->add(Resource::create('red.gif')->path(), .2);
            $animation->add(Resource::create('green.gif')->path(), .2);
            $animation->add(Resource::create('blue.gif')->path(), .2);
        });

        // $this->assertEquals(12, $image->width());
        // $this->assertEquals(4, $image->height());
        // $this->assertEquals(3, $image->count());
        // $this->assertEquals(0, $image->loops());
        foreach ($image as $frame) {
            $this->assertEquals(.2, $frame->delay());
        }
    }
}
