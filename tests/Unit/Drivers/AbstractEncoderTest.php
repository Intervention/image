<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Drivers\AbstractEncoder;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

/**
 *
 * @internal
 */
#[CoversClass(\Intervention\Image\Drivers\AbstractEncoder::class)]
final class AbstractEncoderTest extends BaseTestCase
{
    public function testGetBuffered(): void
    {
        $encoder = Mockery::mock(AbstractEncoder::class)->makePartial();
        $result = $encoder->getBuffered(function () {
            echo 'result';
        });
        $this->assertEquals('result', $result);
    }
}
