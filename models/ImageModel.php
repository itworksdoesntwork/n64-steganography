<?php

namespace app\models;

use http\Exception\UnexpectedValueException;
use yii\base\Model;

class ImageModel extends Model
{
    public static $imageExtension = '.png';

    public $imageQuality = 9; // png quality is in range 0..9, not like jpeg which is in range 0..100
    public $basePath = 'uploads/';
    public $imageThumbnailSuffix = '_thumbnail';
    public $imageName;
    public $x = null;
    public $y = null;
    public $upload_time = null;
    public $file_size = null;

    public function rules()
    {
        return [
            ['imageName', function ($attribute, $params) {
                if (!file_exists($this->basePath . $this->$attribute . static::$imageExtension)) {
                    $this->addError($attribute, 'Image does not exist!');
                }
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'thumbnail'     => 'Image Preview',
            'imageName'     => 'MD5 Hash',
            'decode'        => 'Hidden text',
            'hasHiddenText' => 'Has Hidden Text ?'
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

        $newImageName = md5_file($newTempImageFilePath . static::$imageExtension);
        rename($newTempImageFilePath . static::$imageExtension, $this->basePath . $newImageName . static::$imageExtension);
        unlink($imageFilePath); // delete old temp image file
        $this->imageName = $newImageName;
        $this->createThumbnail();
    }

    public function createThumbnail()
    {
        $new_width = 150;
        $new_height = 150;
        list($old_width, $old_height) = getimagesize($this->basePath . $this->imageName . static::$imageExtension);

        $new_image = imagecreatetruecolor($new_width, $new_height);
        $old_image = imagecreatefrompng($this->basePath . $this->imageName . static::$imageExtension);

        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

        imagepng($new_image, $this->basePath . $this->imageName . $this->imageThumbnailSuffix . static::$imagExtension, $this->imageQuality);
    }

    public function getThumbnail()
    {
        $basePath = $this->basePath . $this->imageName;
        $imagePath = $basePath . static::$imageExtension;
        $thumbnailPath = $basePath . $this->imageThumbnailSuffix . static::$imageExtension;

        if (!file_exists($imagePath)) {
            $this->createThumbnail();
        }

        return $thumbnailPath;
    }

    public function getX()
    {
        if ($this->x === null) {
            list($x, $y) = getimagesize($this->basePath . $this->imageName . static::$imageExtension);
            $this->x = $x;
            $this->y = $y;
        }

        return $this->x;
    }

    public function getY()
    {
        if ($this->y === null) {
            list($x, $y) = getimagesize($this->basePath . $this->imageName . static::$imageExtension);
            $this->x = $x;
            $this->y = $y;
        }

        return $this->y;
    }

    public function getWidth()
    {
        return $this->getX();
    }

    public function getHeight()
    {
        return $this->getY();
    }

    public function getUploadTime()
    {
        if ($this->upload_time === null) {
            $this->upload_time = filemtime($this->basePath . $this->imageName . static::$imageExtension);
        }

        return $this->upload_time;
    }

    public function getFileSize()
    {
        if ($this->file_size === null) {
            $this->file_size = filesize($this->basePath . $this->imageName . static::$imageExtension);
        }

        return $this->file_size;
    }

    public function getDecode()
    {
        // TODO

        // mock
        return time() % 2 ? null : \Yii::$app->security->generateRandomString(rand(10, 500));
    }

    public function setEncode()
    {
        // TODO
        return null;
    }

    public function getMaxTextToHide()
    {
        // TODO

        return 500; // mock
    }

    public function getHasHiddenText()
    {
        return !!$this->getDecode();
    }
}