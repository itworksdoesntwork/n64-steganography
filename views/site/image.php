<?php

/* @var $this yii\web\View */
/* @var $imageModel \app\models\ImageModel */

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
                'value' => $imageModel->width . 'px',
            ],
            [
                'attribute' => 'Height',
                'value' => $imageModel->height . 'px',
            ],
            'uploadTime:dateTime',
            'fileSize:shortSize',
            'decode:text',
        ]
    ]) ?>
</div>
