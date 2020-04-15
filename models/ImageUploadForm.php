<?php

namespace app\models;

use app\models\ImageModel;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUploadForm extends Model
{
    public $image;
    public $imageName;

    public function rules()
    {
        return [
            [
                'image', 'file',
                'skipOnEmpty'   => false,
                'extensions'    => 'bmp, png, jpg, jpeg',
                'maxSize'       => 2 * 1024 * 1024, // 2 MB - 1024 bytes * 1024 = 1MB * 2 = 2MB
                'tooBig'        => 'File size limit is 2MB' // err msg for failed file size validation
            ],
            ['imageName', 'safe']
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
        $newImageName = md5($this->image->baseName . time() . rand());
        $this->image->saveAs('uploads/' . $newImageName . '.' . $this->image->extension);

        $imageModel = new ImageModel(['imageName' => $newImageName . '.' . $this->image->extension]);
        $imageModel->convertToPNG();
        $this->imageName = $imageModel->imageName;
        return true;
    }
}