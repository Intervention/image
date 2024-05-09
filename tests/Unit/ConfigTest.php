<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Config;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Config::class)]
final class ConfigTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $this->assertInstanceOf(Config::class, new Config());
    }
}
