<?php

/* @var $this yii\web\View */
/* @var $imageModel \app\models\ImageModel */
/* @var $imageEncodeForm \app\models\ImageEncodeForm */

use yii\widgets\DetailView;

$this->title = 'n64 - image detail';
?>
<div class="col-md-4 col-md-offset-4 text-center">
    <?= DetailView::widget([
        'model' => $imageModel,
        'attributes' => [
            'thumbnail:image',
            'imageName',
            [
                'attribute' => 'Width',
                'value'     => $imageModel->width . 'px',
            ],
            [
                'attribute' => 'Height',
                'value'     => $imageModel->height . 'px',
            ],
            'uploadTime:dateTime',
            'fileSize:shortSize',
            'hasHiddenText:boolean',
            [
                'label' => 'hide',
                'value' => $imageModel->decode
            ]
        ]
    ]) ?>

    <?=  $this->render($imageModel->hasHiddenText ? 'decodeView' : 'encodeForm', [
            'imageModel'        => $imageModel,
            'imageEncodeForm'   => $imageEncodeForm,
    ]); ?>
</div>
