<?php

namespace app\models;

use http\Exception\UnexpectedValueException;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageModel extends Model
{
    private static $imageExtension = '.png';
    public $imageQuality = 9; // png quality is in range 0..9, not like jpeg which is in range 0..100
    public $basePath = 'uploads/';
    public $imageName;

    public function rules()
    {
        return [
            ['imagePath', function ($attribute, $params) {
                if (!file_exists($this->basePath . $this->$attribute)) {
                    $this->addError($attribute, 'Image does not exist!');
                }
            }],
        ];
    }

    public function convertToPNG()
    {
        $imageFilePath = $this->basePath . $this->imageName;
        $newTempImageFilePath = $this->basePath . md5($this->imageName . rand());
        $image = NULL;

        //TODO: read image header and identify correct image type
        try {
            $image = imagecreatefrompng($imageFilePath);
        } catch (\Exception $exception) {}

        if (!$image) {
            try {
                $image = imagecreatefromjpeg($imageFilePath);
            } catch (\Exception $exception) {}
        }

        if (!$image) {
            try {
                $image = imagecreatefromwbmp($imageFilePath);
            } catch (\Exception $exception) {}
        }

        if (!$image) {
            try {
                $image = imagecreatefrombmp($imageFilePath);
            } catch (\Exception $exception) {}
        }

        if (!$image) {
            throw new UnexpectedValueException('Bad image type');
        }

        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, true);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        imagepng($bg, $newTempImageFilePath . static::$imageExtension, $this->imageQuality);
        imagedestroy($bg);

        $md5Sum = md5_file($newTempImageFilePath . static::$imageExtension);
        $newImageName = $md5Sum . static::$imageExtension;
        rename($newTempImageFilePath . static::$imageExtension, $this->basePath . $newImageName);
        unlink($imageFilePath); // delete old temp image file
        $this->imageName = $newImageName;
        $this->createThumbnail();
    }

    public function createThumbnail()
    {
        $new_width = 150;
        $new_height = 150;
        list($old_width, $old_height) = getimagesize($this->basePath . $this->imageName);

        $new_image = imagecreatetruecolor($new_width, $new_height);
        $old_image = imagecreatefrompng($this->basePath . $this->imageName);

        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

        imagepng($new_image, $this->basePath . md5($this->imageName) . '_thumbnail' . static::$imageExtension, $this->imageQuality);
    }
}