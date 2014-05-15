# Intervention Image

Intervention Image is a **PHP image handling and manipulation** library providing an easier and expressive way of using PHP's GD or Imagick libraries. The package includes ServiceProviders and Facades for easy **Laravel 4** integration.

[![Build Status](https://travis-ci.org/Intervention/image.png?branch=master)](https://travis-ci.org/Intervention/image)

## Requirements

- PHP >=5.3
- Fileinfo Extension
- GD (>=2.0) or Imagick module

## Getting started

- [Installation Guide](http://image.intervention.io/installation)
- [Laravel Framework Integration](http://image.intervention.io/laravel)
- [Official Documentation](http://image.intervention.io/)

## Code Examples

```php
// open an image file
$img = Image::make('public/foo.jpg');

// resize image instance
$img->resize(320, 240);

// insert a watermark
$img->insert('public/watermark.png');

// save image in desired format
$img->save('public/bar.jpg');
```

Refer to the [documentation](http://image.intervention.io/) to learn more about Intervention Image.

## License

Intervention Image is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2014 [Oliver Vogel](http://olivervogel.net/)