<?php

/* @var $this yii\web\View */
/* @var $ImageUploadForm app\models\ImageUploadForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'n64 - index';
?>

<div class="row">
    <div class="col-md-2 col-md-offset-5 text-center">
        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

        echo $form->field($ImageUploadForm, 'image')->fileInput()->label(false);
        echo Html::submitButton('Upload', ['class' => 'btn btn-primary']);

        ActiveForm::end();
        ?>
    </div>
</div>
