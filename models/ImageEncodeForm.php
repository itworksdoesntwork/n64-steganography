<?php

namespace app\models;

use app\models\ImageModel;
use yii\base\Model;

class ImageEncodeForm extends Model
{
    private $_imageModel = null;

    // max length of hidden text is image's (Width * Height * chanelNumbers * frame) - (header size)
    // chanelNumbers - 3 for RGB, 4 for RGBA (A stand for alpha e.g. opacity)
    // frame - at the moment we dont support gifs sooo... its 1
    public $textToEncode;
    public $imageName;

    public function init()
    {
        parent::init();
        $this->_imageModel = new ImageModel();
    }

    public function rules()
    {
        return [
            [['imageName', 'textToEncode'], 'required'],

            ['textToEncode', 'string', 'length' => [1, $this->_imageModel->maxTextToHide]],
            ['imageName', 'safe']
        ];
    }

    public function load($data, $formName = null)
    {
        $parentLoad = parent::load($data, $formName);
        $this->_imageModel = new ImageModel(['imageName' => $this->imageName]);

        return $parentLoad && $this->validate();
    }

    public function encode()
    {
        $this->_imageModel->encode = $this->textToEncode;
    }
}