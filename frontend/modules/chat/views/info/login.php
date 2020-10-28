<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                     <button id="submit"> Send </button>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
  $("#submit").click(function(){   
  //alert("Hello");
	var email = $("#loginform-email").val(); 
	var password = $("#loginform-password").val(); 
	//alert(email);
	$.ajax({
		url: "https://eulims.onelab.dost.gov.ph/api/message/login", //API LINK FROM THE CENTRAL
		type: 'POST',
		dataType: "JSON",
		data: {
			email: email,
			password: password
		},
		success: function(response) {
			var token=response.token;
			var userid=response.userid;
			$.post({
            url: '/chat/info/settoken?token='+token+'&userid='+userid, // your controller action
      
				success: function(data) {
				   location.reload();
				}
            }); 
		},
		error: function(xhr, status, error) {
			alert(error);
		}
	}); 
	
   
});
</script>