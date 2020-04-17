<?php

/* @var $this yii\web\View */
/* @var $imageModel \app\models\ImageModel */

use yii\helpers\Html;

?>
<div class="col-md-4 col-md-offset-4 text-center">
    <?= Html::Encode($imageModel->decode) ?>
</div>
