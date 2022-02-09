<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Abstract\Encoders;

use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;

/**
 * @covers \Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder
 *
 * @internal
 */
class AbstractEncoderTest extends TestCase
{
    public function testGetBuffered(): void
    {
        $callback = function () { echo 'hello'; };

        static::assertSame('hello', $this->getAbstractEncoder()->getBuffered($callback));
    }

    public function testSetGetQuality(): void
    {
        $encoder = $this->getAbstractEncoder();
        $encoder->setQuality(55);

        static::assertSame(55, $encoder->getQuality());
    }

    private function getAbstractEncoder(): AbstractEncoder
    {
        return new class () extends AbstractEncoder implements EncoderInterface {
            public function getBuffered(callable $callback): string
            {
                return parent::getBuffered($callback);
            }

            public function encode(ImageInterface $image): EncodedImage
            {
            }
        };
    }
}
