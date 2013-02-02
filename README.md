# Intervention Image Class

Image handling and manipulation based on PHP GD library. Made to work with **Laravel 4** but runs also standalone.

## Installation

You can install this Image class quick and easy with Composer.

Require the package via Composer in your `composer.json`.

    "intervention/image": "dev-master"

Run Composer to update the new requirement.

    $ composer update

The Image class are built to work with the Laravel 4 Framework. The integration is done in seconds.

Open your Laravel config file `config/app.php` and add the following lines.

In the `$providers` array add the service providers for this package.
    
    'providers' => array(

        ...

        'Intervention\Image\ImageServiceProvider'

    ),
    

Add the facade of this package to the `$aliases` array.

    'aliases' => array(

        ...

        'Image' => 'Intervention\Image\Facades\Image'

    ),

## Usage

* Image::__construct - Create new instance of Image class
* Image::make - Create new image resource from image file
* Image::resize - Resize image based on given width and/or height
* Image::grab - Cut out a detail of the image in given ratio and resize to output size
* Image::insert - Insert another image on top of the current image
* Image::pixelate - Pixelate current image
* Image::greyscale - Turn current image into a greyscale version
* Image::text - Write text in current image
* Image::fill - Fill image with given hexadecimal color at position x,y
* Image::rectangle - Draw rectangle in current image starting at point 1 and ending at point 2
* Image::line - Draw a line in current image starting at point 1 and ending at point 2
* Image::ellipse - Draw an ellipse centered at given coordinates
* Image::circle - Draw a circle centered at given coordinates
* Image::pixel - Set single pixel with given hexadecimal color at position x,y
* Image::reset - Reset to original image resource
* Image::save - Save image in filesystem

### Code example (Laravel)

```php
// create Image from file
$img = Image::make('public/foo.jpg');

// resize image to fixed size
$img->resize(300, 200);

// resize image to maximum width of 300px and keep ratio for height automatically
$img->resize(array('width' => '300'));

// resize image to maximum height of 200px and keep ratio for width automatically
$img->resize(array('height' => '200'));

// insert another image
$img->insert('public/bar.png');

// write some text in image
$img->text('Hello World', 10, 10);

// turn image into greyscale version
$img->greyscale();

// pixelate image with blocksize of 25x25 pixel
$img->pixelate(25);

// save image in desired format
$img->save('public/bar.jpg');

// save image in desired format and quality
$img->save('public/bar.jpg', 60);

// its also possible to chain methods
$img1 = Image::make('public/img1.png');
$img2 = Image::make('public/img2.png');
$img1->resize(300, 200)->insert($img2)->save('public/bar.jpg');

// create an empty (transparent background) image, without opening any file
$img = new Image(null, 300, 200);
```

```php
// use grab method to format images in a smart way combining cropping and resizing
$img = Image::make('public/foo.jpg');

// crop the best fitting 1:1 ratio (200x200) and resize to 200x200 pixel
$img->grab(200);

// crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
$img->grab(600, 360);

// crop the best fitting 1:1 (150x150) ratio and resize to 150x150 pixel
$img->grab(array('width' => '150'));
```
