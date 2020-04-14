<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class ImageUploadForm extends Model
{
    public $image;

    public function rules()
    {
        return [
            ['image', 'file', 'skipOnEmpty' => false, 'extensions' => 'bmp, png, jpg, jpeg'],
        ];
    }

    // bool(true) or exception
    public function upload()
    {
        $this->image = UploadedFile::getInstance($this, 'image');
        $newImageName = md5($this->image->baseName . time() . rand());
        $this->image->saveAs('uploads/' . $newImageName . '.' . $this->image->extension);
        return true;
    }
}