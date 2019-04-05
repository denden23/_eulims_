<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\lab\Sampletype;
use common\models\lab\Services;
use common\models\lab\Lab;
use common\models\lab\Testname;
use common\models\lab\Methodreference;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;

?>

<div class="row">
        <div class="col-sm-6">
        <?= GridView::widget([
        'dataProvider' => $testnamedataprovider,
        'pjax' => true,    
        'condensed' => true,
        'responsive'=>false,
        'id'=>'testname-grid',
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'containerOptions'=>[
            'style'=>'overflow:auto; height:380px',
        ],
        'columns' => [
            [
                'label' => 'Add',
                'format' => 'raw', 
                'contentOptions' => ['style' => 'width: 5%;word-wrap: break-word;white-space:pre-line;'],  
                'value' => function($data) {     
                    return "<span class='btn btn-primary glyphicon glyphicon-plus' id='offer' onclick=deleteworkflow(".$data->method_id.")></span>";
                 }          
            ],
            [     
                'label' => 'Method',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 30%;word-wrap: break-word;white-space:pre-line;'],  
                'value' => function($data) {
                    $method_query = Methodreference::find()->where(['method_reference_id'=>$data->method_id])->one();
        
                    if ($method_query){
                        return $method_query->method;
                    }else{
                        return "";
                    }
                 }                        
            ],
            [     
                'label' => 'Reference',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 60%;word-wrap: break-word;white-space:pre-line;'],  
                'value' => function($data) {

                    $method_query = Methodreference::find()->where(['method_reference_id'=>$data->method_id])->one();
                    if ($method_query){
                        return $method_query->reference;
                    }else{
                        return "";
                    }
                            
                 }                        
            ],
            [    
                'label' => 'Fee',
                'format' => 'raw',
                'width'=> '150px',
                'contentOptions' => ['style' => 'width: 10%;word-wrap: break-word;white-space:pre-line;'],  
                'value' => function($data) {
                    $method_query = Methodreference::find()->where(['method_reference_id'=>$data->method_id])->one();
                    if ($method_query){
                        return $method_query->fee;
                    }else{
                        return "";
                    }
                 }                
            ]
       ],
    ]); ?>
        </div>
            <div class="col-sm-6">
            
      
        </div>
</div>