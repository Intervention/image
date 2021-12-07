# Intervention Image
## PHP Image Manipulation

[![Latest Version](https://img.shields.io/packagist/v/intervention/image.svg)](https://packagist.org/packages/intervention/image)
[![Build Status](https://travis-ci.org/Intervention/image.png?branch=master)](https://travis-ci.org/Intervention/image)
[![Monthly Downloads](https://img.shields.io/packagist/dm/intervention/image.svg)](https://packagist.org/packages/intervention/image/stats)

Intervention Image is a **PHP image handling and manipulation** library providing an easier and expressive way to create, edit, and compose images.

- Simple interface for 
- Driver agnostic 
- Support for animated images
- Framework-agnostic, will work with any project
- PSR-12 compliant

## Code Examples

```php
// create image manager with desired driver
$manager = new ImageManager('gd')

// open an image file
$image = $manager->make('images/example.jpg');

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

Learn the [basics](https://image.intervention.io/) on how to use Intervention Image and more with the [official documentation](https://image.intervention.io/).

## License

Intervention Image is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2021 Oliver Vogel
