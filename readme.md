# Intervention Image
## PHP Image Manipulation

[![Latest Version](https://img.shields.io/packagist/v/intervention/image.svg)](https://packagist.org/packages/intervention/image)
[![Build Status](https://github.com/Intervention/image/actions/workflows/run-tests.yml/badge.svg)](https://github.com/Intervention/image/actions)
[![Monthly Downloads](https://img.shields.io/packagist/dm/intervention/image.svg)](https://packagist.org/packages/intervention/image/stats)

Intervention Image is a **image handling and manipulation library written in PHP** providing an easier and expressive way to create, edit, and compose images. GD library or Imagick can be selected as the base layer for all operations.

- Simple interface for common tasks
- Interchangable driver architecture
- Support for animated images
- Framework-agnostic
- PSR-12 compliant

## Code Examples

```php
// create image manager with desired driver
$manager = new ImageManager('gd')

// open an image file
$image = $manager->make('images/example.gif');

// resize image instance
$image->resize(320, 240);

// insert a watermark
$image->place('images/watermark.png');

// encode edited image
$encoded = $image->toJpg();

// save encoded image
$encoded->save('images/example.jpg');
```

## Requirements

- PHP >=8.0

## Supported Image Libraries

- GD Library
- Imagick PHP extension

## Installation

```bash
composer require intervention/image
```

## Getting started

Learn the [basics](https://image.intervention.io/v3/basics/instantiation/) on how to use Intervention Image and more with the [official documentation](https://image.intervention.io/v3/).

## License

Intervention Image is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2022 Oliver Vogel
