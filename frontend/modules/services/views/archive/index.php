<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;

$this->title = 'Archives';
$this->params['breadcrumbs'][] = $this->title;
$Button="{view}";
$js=<<<JS

   $("#dropdownYear").change(function(){   
        var year = $("#dropdownYear").val();

        $.ajax({
            url: "/services/archive/generate",
            type: 'POST',
            dataType: "JSON",
            data: {
                year: year
            },
            success: function(response) {
                $("#count").text(response.count);
                $("#total").text(response.total);
                $("#year").text(response.year);
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
       
    });

    $("#migrate").click(function(){   
        var year = $("#dropdownYear").val();

        $.ajax({
            url: "/services/archive/migrate",
            type: 'POST',
            dataType: "JSON",
            data: {
                year: year
            },
            beforeSend: function() {
                $("div.spanner").addClass("show");
                $("div.overlay").addClass("show");
            },
            success: function(response) {
                $("#count").text(response.count);
                $("#total").text(response.total);
                $('div.overlay').removeClass("show");
                $('div.spanner').removeClass("show");
                alert('Migrated Successfully.');
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
       
    });
JS;

$this->registerJs($js,\yii\web\View::POS_READY);
?>
<style>
.spanner{
  position:absolute;
  top: 50%;
  left: 0;
  background: #2a2a2a55;
  width: 100%;
  display:block;
  text-align:center;
  height: 100%;
  color: #FFF;
  transform: translateY(-50%);
  z-index: 1000;
  visibility: hidden;
}

.spanner p {
    margin: -230px 0 10px;
}
.overlay{
    height: 100%;
  width: 0;
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  background-color: rgb(0,0,0); /* Black fallback color */
  background-color: rgba(0,0,0, 0.5); /* Black w/opacity */
  overflow-x: hidden; /* Disable horizontal scroll */
  transition: 0.5s;
}

.loader,
.loader:before,
.loader:after {
  border-radius: 50%;
  width: 2.5em;
  height: 2.5em;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  -webkit-animation: load7 1.8s infinite ease-in-out;
  animation: load7 1.8s infinite ease-in-out;
}
.loader {
  color: #ffffff;
  font-size: 10px;
  margin: 280px auto;
  position: relative;
  text-indent: -9999em;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}
.loader:before,
.loader:after {
  content: '';
  position: absolute;
  top: 0;
}
.loader:before {
  left: -3.5em;
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}
.loader:after {
  left: 3.5em;
}
@-webkit-keyframes load7 {
  0%,
  80%,
  100% {
    box-shadow: 0 2.5em 0 -1.3em;
  }
  40% {
    box-shadow: 0 2.5em 0 0;
  }
}
@keyframes load7 {
  0%,
  80%,
  100% {
    box-shadow: 0 2.5em 0 -1.3em;
  }
  40% {
    box-shadow: 0 2.5em 0 0;
  }
}

.show{
  visibility: visible;
}

.spanner, .overlay{
	opacity: 0;
	-webkit-transition: all 0.3s;
	-moz-transition: all 0.3s;
	transition: all 0.3s;
}

.spanner.show, .overlay.show {
	opacity: 1
}
</style>
<div class="overlay"></div>
<div class="spanner">
    <div class="loader"></div>
    <p style="background-color: black;">Please wait, migration ongoing...</p>
</div>

<div class="archive-view">

    <section class="invoice">
        <div class="row">
            <div class="col-md-7" style="font-size: 25px;">
                <br><br><br>
                <?php echo '<span id="count" style="color: red;">'.$count.'</span> out of <span id="total" style="color: blue;">'.$total.'</span> are Available for migration for <span id="year">'.$year.'</span>' ?>
            </div>
            <div class="col-md-5">
            <br><br>
            <label class="control-label">Date </label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="year" class="form-control select2" id="dropdownYear" style="width: 100%;">
                                <?= 
                                    $year = date('Y')-5;
                                    for($x = 0; $x < 5; $x++){
                                        echo '<option value="'.$year.'">'.$year.'</option>';
                                        $year--;
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-block btn-info" id="migrate">Migrate</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="request-index">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'containerOptions' => ['style' => 'overflow-x: none!important','class'=>'kv-grid-container'], // only set when $responsive = false
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'rowOptions' => function($model){
                            if($model->status == 'Confirmed'){
                                return ['class'=>'success'];
                            }else{
                                return ['class'=>'danger'];
                            }
                        },
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                                'customer',
                                'request_no',
                                'status',
                                'type',
                                'requested_at',
                                [
                                    'class' => kartik\grid\ActionColumn::className(),
                                    'template' => $Button,
                                    'buttons' => [
                                        'view' => function ($url, $model){
                                            return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/services/archive/view?id=' . $model->id,'onclick'=>'window.open(this.value)','target'=>'_blank', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Archive")]);
                                        },
                                    ],
                                ],
                            ],
                        ]); 
                    ?>
                </div>
            </div>
        </div>
    
    </section>
</div>