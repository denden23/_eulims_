<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\lab\Request */

$this->title = 'Create Referral Request';
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-create">
    <?= $this->render('_formReferral', [
        'model' => $model,
        'labreferral' => $labreferral,
        'discountreferral' => $discountreferral,
        'purposereferral' => $purposereferral,
        'modereleasereferral' => $modereleasereferral,
        'api_url'=>$api_url
    ]) ?>

</div>
