<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Feature;

use Generator;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;

class AutoOrientationTest extends BaseTestCase
{
    private const array LANDSCAPE_TOP_LEFT = [2, 2];
    private const array LANDSCAPE_TOP_RIGHT = [50, 2];
    private const array LANDSCAPE_BOTTOM_LEFT = [2, 20];
    private const array LANDSCAPE_BOTTOM_RIGHT = [50, 20];
    private const array PORTRAIT_TOP_LEFT = [2, 2];
    private const array PORTRAIT_TOP_RIGHT = [20, 2];
    private const array PORTRAIT_BOTTOM_LEFT = [2, 50];
    private const array PORTRAIT_BOTTOM_RIGHT = [20, 50];

    /**
     * @param array<array{'position': array<int>, 'colors': array<int>}> $colors
     */
    #[DataProvider('autoOrientationDisabledProvider')]
    public function testAutoOrientationDisabled(
        DriverInterface $driver,
        string $path,
        int $width,
        int $height,
        array $colors,
    ): void {
        $image = ImageManager::usingDriver($driver, autoOrientation: false)->decodePath($path);
        $this->assertImageMatchesSpecs($width, $height, $colors, $image);
    }

    /**
     * @param array<array{'position': array<int>, 'colors': array<int>}> $colors
     */
    #[DataProvider('autoOrientationEnabledProvider')]
    public function testAutoOrientationEnabled(
        DriverInterface $driver,
        string $path,
        int $width,
        int $height,
        array $colors,
    ): void {
        $image = ImageManager::usingDriver($driver, autoOrientation: true)->decodePath($path);
        $this->assertImageMatchesSpecs($width, $height, $colors, $image);
    }

    /**
     * @param array<array{'position': array<int>, 'colors': array<int>}> $colors
     */
    #[DataProvider('autoOrientationEnabledProvider')]
    public function testAutoOrientationDisabledFollowUp(
        DriverInterface $driver,
        string $path,
        int $width,
        int $height,
        array $colors,
    ): void {
        $image = ImageManager::usingDriver($driver, autoOrientation: false)->decodePath($path)->orient();
        $this->assertImageMatchesSpecs($width, $height, $colors, $image);
    }

    /**
     * @param array<array{'position': array<int>, 'colors': array<int>}> $colors
     */
    #[DataProvider('autoOrientationEnabledProvider')]
    public function testAutoOrientationEnabledFollowUp(
        DriverInterface $driver,
        string $path,
        int $width,
        int $height,
        array $colors,
    ): void {
        $image = ImageManager::usingDriver($driver, autoOrientation: true)->decodePath($path)->orient();
        $this->assertImageMatchesSpecs($width, $height, $colors, $image);
    }

    /**
     * @param array<array{'position': array<int>, 'colors': array<int>}> $colors
     */
    private function assertImageMatchesSpecs(int $width, int $height, array $colors, ImageInterface $image): void
    {
        $this->assertEquals($width, $image->width());
        $this->assertEquals($height, $image->height());
        foreach ($colors as $color) {
            $this->assertColor(
                $color['color'][0],
                $color['color'][1],
                $color['color'][2],
                $color['color'][3],
                $image->colorAt($color['position'][0], $color['position'][1]),
                3
            );
        }
    }

    /**
     * Provide image test information for pending orientation adjustment.
     */
    public static function autoOrientationDisabledProvider(): Generator
    {
        $drivers = [GdDriver::class, ImagickDriver::class];

        foreach ($drivers as $driver) {
            yield [
                new $driver(),
                Resource::create('orientation/landscape_0.jpg')->path(),
                60,
                30,
                [
                    ['position' => self::LANDSCAPE_TOP_LEFT, 'color' => [255, 0, 0, 255]],
                    ['position' => self::LANDSCAPE_TOP_RIGHT, 'color' => [0, 255, 0, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_LEFT, 'color' => [0, 0, 255, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_RIGHT, 'color' => [51, 51, 51, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_1.jpg')->path(),
                60,
                30,
                [
                    ['position' => self::LANDSCAPE_TOP_LEFT, 'color' => [255, 0, 0, 255]],
                    ['position' => self::LANDSCAPE_TOP_RIGHT, 'color' => [0, 255, 0, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_LEFT, 'color' => [0, 0, 255, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_RIGHT, 'color' => [51, 51, 51, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_2.jpg')->path(),
                60,
                30,
                [
                    ['position' => self::LANDSCAPE_TOP_LEFT, 'color' => [0, 255, 0, 255]],
                    ['position' => self::LANDSCAPE_TOP_RIGHT, 'color' => [255, 0, 0, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_LEFT, 'color' => [51, 51, 51, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_RIGHT, 'color' => [0, 0, 255, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_3.jpg')->path(),
                60,
                30,
                [
                    ['position' => self::LANDSCAPE_TOP_LEFT, 'color' => [51, 51, 51, 255]],
                    ['position' => self::LANDSCAPE_TOP_RIGHT, 'color' => [0, 0, 255, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_LEFT, 'color' => [0, 255, 0, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_RIGHT, 'color' => [255, 0, 0, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_4.jpg')->path(),
                60,
                30,
                [
                    ['position' => self::LANDSCAPE_TOP_LEFT, 'color' => [0, 0, 255, 255]],
                    ['position' => self::LANDSCAPE_TOP_RIGHT, 'color' => [51, 51, 51, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_LEFT, 'color' => [255, 0, 0, 255]],
                    ['position' => self::LANDSCAPE_BOTTOM_RIGHT, 'color' => [0, 255, 0, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_5.jpg')->path(),
                30,
                60,
                [
                    ['position' => self::PORTRAIT_TOP_LEFT, 'color' => [255, 0, 0, 255]],
                    ['position' => self::PORTRAIT_TOP_RIGHT, 'color' => [0, 0, 255, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_LEFT, 'color' => [0, 255, 0, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_RIGHT, 'color' => [51, 51, 51, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_6.jpg')->path(),
                30,
                60,
                [
                    ['position' => self::PORTRAIT_TOP_LEFT, 'color' => [0, 255, 0, 255]],
                    ['position' => self::PORTRAIT_TOP_RIGHT, 'color' => [51, 51, 51, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_LEFT, 'color' => [255, 0, 0, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_RIGHT, 'color' => [0, 0, 255, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_7.jpg')->path(),
                30,
                60,
                [
                    ['position' => self::PORTRAIT_TOP_LEFT, 'color' => [51, 51, 51, 255]],
                    ['position' => self::PORTRAIT_TOP_RIGHT, 'color' => [0, 255, 0, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_LEFT, 'color' => [0, 0, 255, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_RIGHT, 'color' => [255, 0, 0, 255]],
                ]
            ];

            yield [
                new $driver(),
                Resource::create('orientation/landscape_8.jpg')->path(),
                30,
                60,
                [
                    ['position' => self::PORTRAIT_TOP_LEFT, 'color' => [0, 0, 255, 255]],
                    ['position' => self::PORTRAIT_TOP_RIGHT, 'color' => [255, 0, 0, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_LEFT, 'color' => [51, 51, 51, 255]],
                    ['position' => self::PORTRAIT_BOTTOM_RIGHT, 'color' => [0, 255, 0, 255]],
                ]
            ];
        }
    }

    /**
     * Provide image test information for correct orientation.
     */
    public static function autoOrientationEnabledProvider(): Generator
    {
        $drivers = [GdDriver::class, ImagickDriver::class];
        $width = 60;
        $height = 30;
        $colors = [
            ['position' => self::LANDSCAPE_TOP_LEFT, 'color' => [255, 0, 0, 255]],
            ['position' => self::LANDSCAPE_TOP_RIGHT, 'color' => [0, 255, 0, 255]],
            ['position' => self::LANDSCAPE_BOTTOM_LEFT, 'color' => [0, 0, 255, 255]],
            ['position' => self::LANDSCAPE_BOTTOM_RIGHT, 'color' => [51, 51, 51, 255]],
        ];

        foreach ($drivers as $driver) {
            yield [new $driver(), Resource::create('orientation/landscape_0.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_1.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_2.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_3.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_4.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_5.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_6.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_7.jpg')->path(), $width, $height, $colors];
            yield [new $driver(), Resource::create('orientation/landscape_8.jpg')->path(), $width, $height, $colors];
        }
    }
}
