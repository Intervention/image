<?php

include 'vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

// create new manager instance with desired driver
$manager = new ImageManager(new Driver());

// read image from filesystem
$image = $manager->read('tests/resources/bees.gif');

// scale and save result
$image->scale(300)->save('converted.gif');
