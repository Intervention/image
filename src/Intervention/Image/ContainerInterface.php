<?php

namespace Intervention\Image;

interface ContainerInterface
{
    public function setCore($core);
    public function getCore();
    public function countFrames();
}