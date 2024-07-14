<?php

$imagick = new Imagick();
$imagick->readImage('./tests/resources/tile.png');

var_dump($imagick->identifyImage());
var_dump($imagick->getImageType());
