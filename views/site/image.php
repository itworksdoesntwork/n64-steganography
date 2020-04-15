<?php

/* @var $this yii\web\View */
/* @var $imageHash String */

use yii\helpers\Html;

$this->title = 'n64 - image detail';
?>
<div class="row">
    <div class="col-md-2 col-md-offset-5 text-center">
        <?= Html::img("@web/uploads/{$imageHash}_thumbnail.png"); ?>
    </div>
</div>
