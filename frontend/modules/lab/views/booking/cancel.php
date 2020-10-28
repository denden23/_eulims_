<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\CancelledOr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cancelled-or-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reason')->textInput() ?>

    <div class="form-group">
	    <div class="form-group pull-right">
			<?= Html::submitButton('Cancel', ['class' => 'btn btn-danger']) ?>
		</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>