# Intervention Image
## PHP Image Processing

[![Latest Version](https://img.shields.io/packagist/v/intervention/image.svg)](https://packagist.org/packages/intervention/image)
[![Build Status](https://github.com/Intervention/image/actions/workflows/run-tests.yml/badge.svg)](https://github.com/Intervention/image/actions)
[![Monthly Downloads](https://img.shields.io/packagist/dm/intervention/image.svg)](https://packagist.org/packages/intervention/image/stats)

Intervention Image is a **PHP image processing library** that provides a simple
and expressive way to create, edit, and compose images. It features a unified
API for the two most popular image manipulation extensions. You can choose
between the GD library or Imagick as the base layer for all operations.

- Simple interface for common image editing tasks
- Interchangable driver architecture
- Support for animated images
- Framework-agnostic
- PSR-12 compliant

## Installation

You can easily install this library using [Composer](https://getcomposer.org).
Just request the package with the following command:

```bash
composer require intervention/image
```

## Getting started

Learn the [basics](https://image.intervention.io/v3/basics/instantiation/) on
how to use Intervention Image and more with the [official
documentation](https://image.intervention.io/v3/).

## Code Examples

```php
use Intervention\Image\ImageManager;

// create image manager with desired driver
$manager = new ImageManager(
    new Intervention\Image\Drivers\Gd\Driver()
);

// open an image file
$image = $manager->read('images/example.gif');

// resize image instance
$image->resize(height: 300);

// insert a watermark
$image->place('images/watermark.png');

// encode edited image
$encoded = $image->toJpg();

// save encoded image
$encoded->save('images/example.jpg');
```

## Requirements

- PHP >= 8.1

## Supported Image Libraries

- GD Library
- Imagick PHP extension

## Development & Testing

This package contains a Docker image for building a test suite and an analysis
container. You must have Docker installed on your system to run all tests using
the following command.

```bash
docker-compose run --rm --build tests
```

Run the static analyzer on the code base.

```bash
docker-compose run --rm --build analysis
```

## Security

If you discover any security related issues, please email oliver@intervention.io directly.

## License

Intervention Image is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2023 [Oliver Vogel](http://intervention.io/)
