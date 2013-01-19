# Intervention Image Class

Image handling and manipulation. Made to work with **Laravel 4** but runs also standalone.

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

        'Image' => 'Intervention\Image\Facades\Image',

    ),
