<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Package */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
           'sampletype.type',
            'name',
            [
                'label'=>'Rate',
                'format'=>'raw',
                'value' => function($model) {
                    return number_format($model->rate, 2);
                    },
                'valueColOptions'=>['style'=>'width:30%'], 
                'displayOnly'=>true
            ],
            // [
            //     'label'=>'Tests',
            //     'format'=>'raw',
            //     'value' => function($model) {
            //         $tet = $model->tests;  
            //         $sql = "SELECT GROUP_CONCAT(testName) FROM tbl_testname WHERE testname_id IN ($tet)";     
         
            //         $Connection = Yii::$app->labdb;
            //         $command = $Connection->createCommand($sql);
            //         $row = $command->queryOne();    
            //         $tests = $row['GROUP_CONCAT(testName)'];  

            //         $space = explode(',', $tests);
            //         $d = '';
            //         $newline = ", ";
            //         foreach ($space as $s){
            //             $d.= $s.$newline;
            //         }                   
            //         return $d;
            //         },
            //     'valueColOptions'=>['style'=>'width:30%'], 
            //     'displayOnly'=>true
            // ],
        ],
    ]) ?>

</div>
