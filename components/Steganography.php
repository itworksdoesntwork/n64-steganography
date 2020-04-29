<?php

namespace app\components;

// only PNG support at the moment
// in feature version this need to support all major images formats
// maybe we will export this as standalone composer/yii2/laravel package
class Steganography
{
    private $imagePath = null;

    public function loadImage($imagePath)
    {

        return $this;
    }

    public function decode()
    {

        return ""; // string
    }

    public function encode()
    {

        return true; // bool
    }
}