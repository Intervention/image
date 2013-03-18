# Intervention Image Class

Intervention Image Class is an image handling and manipulation wrapper library using [PHP GD library](http://www.php.net/manual/book.image.php). The class is written to make PHP image manipulating more easier and expressive.

The library requires at least **PHP version 5.3** and comes with [Laravel 4](https://github.com/laravel/framework/) Facades and Service Providers to simplify the optional framework integration.

## Installation

You can install this Image class quickly and easily with Composer.

Require the package via Composer in your `composer.json`.

    "intervention/image": "dev-master"

Run Composer to install or update the new requirement.

    $ composer update

Now you are able to require the `vendor/autoload.php` file to PSR-0 autoload the library.

### Laravel 4 Integration

The Image class also has optional Laravel 4 support. The integration into the framework is done in seconds.

Open your Laravel config file `config/app.php` and add the following lines.

In the `$providers` array add the service providers for this package.
    
    'providers' => array(
        
        [...]

        'Intervention\Image\ImageServiceProvider'
    ),
    

Add the facade of this package to the `$aliases` array.

    'aliases' => array(
        
        [...]

        'Image' => 'Intervention\Image\Facades\Image'
    ),

## Usage

* Image::__construct - Create new instance of Image class
* Image::make - Open a new image resource from image file
* Image::canvas - Create a new empty image resource
* Image::raw - Create a new image resource with image data from string
* Image::open - Open a new image resource from image file
* Image::resize - Resize current image based on given width and/or height
* Image::crop - Crop the current image
* Image::grab - Cut out a detail of the image in given ratio and resize to output size
* Image::resizeCanvas - Resize image boundaries
* Image::opacity - Set opacity of current image
* Image::mask - Apply given image as alpha mask on current image
* Image::insert - Insert another image on top of the current image
* Image::brightness - Changes brightness of current image (-100 = min brightness, 0 = no change, +100 = max brightness)
* Image::contrast - Changes contrast of current image (-100 = min contrast, 0 = no change, +100 = max contrast)
* Image::pixelate - Pixelate current image
* Image::greyscale - Turn current image into a greyscale version
* Image::invert - Invert colors of current image
* Image::rotate - Rotate image by a given value
* Image::flip - Mirror image horizontally or vertically
* Image::text - Write text in current image
* Image::fill - Fill image with given color at position x,y
* Image::rectangle - Draw rectangle in current image starting at point 1 and ending at point 2
* Image::line - Draw a line in current image starting at point 1 and ending at point 2
* Image::ellipse - Draw an ellipse centered at given coordinates
* Image::circle - Draw a circle centered at given coordinates
* Image::pixel - Set single pixel with given color at position x,y
* Image::pickColor - Picks and formats color at position in current image
* Image::reset - Reset to original image resource
* Image::save - Save image in filesystem
* Image::__toString - Returns image data depending on current format

### Code examples (Laravel)

#### Resize images

```php
// create Image from file
$img = Image::make('public/foo.jpg');

// resize image to fixed size
$img->resize(300, 200);

// resize only the width of the image
$img->resize(300, null);

// resize only the height of the image
$img->resize(null, 200);

// resize the image to a width of 300 and constrain aspect ratio (auto height)
$img->resize(300, null, true);

// resize the image to a height of 200 and constrain aspect ratio (auto width)
$img->resize(null, 200, true);

// prevent possible upsizing with optional fourth parameter
$img->resize(null, 400, true, false);

// Reset image resource to original
$img->reset();

// save image in desired format and quality
$img->save('public/bar.jpg', 60);
```

#### Resize canvas

```php
// create Image from file
$img = Image::make('public/foo.jpg');

// resize image canvas
$img->resizeCanvas(300, 200);

// resize only the width of the canvas
$img->resizeCanvas(300, null);

// resize only the height of the canvas
$img->resizeCanvas(null, 200);

// resize the canvas by cutting out bottom right position
$img->resizeCanvas(300, 200, 'bottom-right');

// resize the canvas relative by setting the third parameter to true
$img->resizeCanvas(10, -10, 'center', true);

// set a background-color for the emerging area
$img->resizeCanvas(1280, 720, 'center', false, 'ff00ff');
```

#### Crop image

```php
// create Image from file
$img = Image::make('public/foo.jpg');

// crop 300x200 pixel cutout at position x:200, y:100
$img->crop(300, 200, 200, 100);

// crop 300x200 pixel cutout centered on current image
$img->crop(300, 200);

// save image
$img->save();
```

#### Smart resizing

```php
// use grab method to format images in a smart way combining cropping and resizing
$img = Image::make('public/foo.jpg');

// crop the best fitting 1:1 ratio (200x200) and resize to 200x200 pixel
$img->grab(200);

// crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
$img->grab(600, 360);

// save image
$img->save('public/bar.jpg');
```

#### Create empty images and add content

```php
// create an empty Image resource (background color transparent)
$img = Image::canvas(640, 480);

// insert another image on top of current resource
$img->insert('public/bar.png');

// write some text in image
$img->text('Hello World', 10, 10);

// save image in desired format
$img->save('public/foo.jpg');
```

#### Image filters

```php
// create Image from file
$img = Image::make('public/foo.jpg');

// turn image into greyscale version
$img->greyscale();

// modify brightness level
$img->brightness(80);

// modify contrast level
$img->contrast(-45);

// pixelate image with blocksize of 25x25 pixel
$img->pixelate(25);
```

#### Other examples

```php
// create empty canvas
$img = Image::canvas(800, 600);

// fill image with color
$img->fill('cccccc');

// draw a filled rectangle
$img->rectangle('006729', 100, 100, 200, 200, true);

// draw a outline circle
$img->circle('ae051f', 400, 300, 100, false);

// draw a red line from point 10,10 to point 300,300 pixel
$img->line('ae051f', 10, 10, 300, 300);

// Apply image as alpha mask on image
$img->mask('public/mask.png', true);

// rotate image 90° clockwise
$img->rotate(90);

// set 75% opacity
$img->opacity(75);

// flip image horizontally
$img->flip('h');

// flip image vertically
$img->flip('v');
```

#### Method chaining

```php
// it is possible to chain all methods
$img1 = Image::canvas(800, 600);
$img2 = Image::make('public/img2.png');
$img1->resize(300, 200)->insert($img2)->save('public/bar.jpg');
```

## Image Caching

The optional package [Intervention Image Cache](https://github.com/Intervention/imagecache/) extends the package to be capable of image caching. `Image::cache` will automatically detect if a cached file for the particular operations exists.

```php
// run the operations on the image or read a file
// for the particular operations from cache
$img = Image::cache(function($image) {
   return $image->make('public/foo.jpg')->resize(300, 200)->greyscale();
});
```

Read more about [Intervention Image Cache](https://github.com/Intervention/imagecache/).

## License

Intervention Image Class is licensed under the [MIT License](http://opensource.org/licenses/MIT).
