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
        $config = new Config();
        $this->assertInstanceOf(Config::class, $config);

        $this->assertTrue($config->autoOrientation);
        $this->assertTrue($config->decodeAnimation);
        $this->assertEquals('ffffff', $config->backgroundColor);

        $config = new Config(
            autoOrientation: false,
            decodeAnimation: false,
            backgroundColor: 'f00',
            strip: true,
        );
        $this->assertInstanceOf(Config::class, $config);

        $this->assertFalse($config->autoOrientation);
        $this->assertFalse($config->decodeAnimation);
        $this->assertTrue($config->strip);
        $this->assertEquals('f00', $config->backgroundColor);
    }

    public function testGetSetOptions(): void
    {
        $config = new Config();
        $this->assertTrue($config->autoOrientation);
        $this->assertTrue($config->decodeAnimation);
        $this->assertFalse($config->strip);
        $this->assertEquals('ffffff', $config->backgroundColor);

        $result = $config->setOptions(
            autoOrientation: false,
            decodeAnimation: false,
            backgroundColor: 'f00',
            strip: true,
        );

        $this->assertFalse($config->autoOrientation);
        $this->assertFalse($config->decodeAnimation);
        $this->assertEquals('f00', $config->backgroundColor);

        $this->assertFalse($result->autoOrientation);
        $this->assertFalse($result->decodeAnimation);
        $this->assertTrue($result->strip);
        $this->assertEquals('f00', $result->backgroundColor);

        $result = $config->setOptions(backgroundColor: '000');

        $this->assertFalse($config->autoOrientation);
        $this->assertFalse($config->decodeAnimation);
        $this->assertTrue($config->strip);
        $this->assertEquals('000', $config->backgroundColor);

        $this->assertFalse($result->autoOrientation);
        $this->assertFalse($result->decodeAnimation);
        $this->assertTrue($result->strip);
        $this->assertEquals('000', $result->backgroundColor);
    }

    public function testSetOptionsWithArray(): void
    {
        $config = new Config();
        $result = $config->setOptions([
            'autoOrientation' => false,
            'decodeAnimation' => false,
            'backgroundColor' => 'f00',
            'strip' => true,
        ]);

        $this->assertFalse($config->autoOrientation);
        $this->assertFalse($config->decodeAnimation);
        $this->assertTrue($config->strip);
        $this->assertEquals('f00', $config->backgroundColor);
        $this->assertFalse($result->autoOrientation);
        $this->assertFalse($result->decodeAnimation);
        $this->assertTrue($result->strip);
        $this->assertEquals('f00', $result->backgroundColor);
    }
}
