<?php

/* @var $this yii\web\View */
/* @var $imageModel \app\models\ImageModel */
/* @var $imageEncodeForm \app\models\ImageEncodeForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="row">
    <?php
    $form = ActiveForm::begin(['method' => 'POST']);

    echo $form->field($imageEncodeForm, 'textToEncode')->textarea(['rows' => 14, 'cols' => 200]);
    echo Html::submitButton('Hide', ['class' => 'btn btn-primary']);

    ActiveForm::end();
    ?>
</div>
