<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\ImageModel;

class ImageUploadForm extends Model
{
    public $image;

    public function rules()
    {
        return [
            [
                'image', 'file',
                'skipOnEmpty'   => false,
                'extensions'    => 'bmp, png, jpg, jpeg',
                'maxSize'       => 2 * 1024 * 1024,
                'tooBig'        => 'File size limit is 2MB' // err msg for failed file size validation
            ],
        ];
    }

    public function load($data, $formName = null)
    {
        $parentLoad = parent::load($data, $formName);
        $this->image = UploadedFile::getInstance($this, 'image');

        return $parentLoad && $this->validate();
    }

    // bool(true) or exception
    public function upload()
    {
        $this->image = UploadedFile::getInstance($this, 'image');
        $newImageName = md5($this->image->baseName . time() . rand());
        $this->image->saveAs('uploads/' . $newImageName . '.' . $this->image->extension);

        $imageModel = new ImageModel(['imageName' => $newImageName . '.' . $this->image->extension]);
        $imageModel->convertToPNG();
        return true;
    }
}