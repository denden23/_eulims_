<?php
use yii\helpers\Html;
use common\modules\profile\ProfileAsset;
use common\models\lab\Visitedpage;
/* @var $this \yii\web\View */
/* @var $content string */

// if(Yii::$app->controller->module->id === 'track'){
//     //echo 'GG';
//     return Yii::$app->controller->redirect(['/track']);
//     //return Yii::$app->controller->redirect(['/site']);
// }
if (Yii::$app->controller->action->id === 'track') { 
    //echo Yii::$app->controller->redirect(['/track']);
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
}
elseif (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {
    $session = Yii::$app->session;
    $hideMenu= $session->get("hideMenu");
    if(!isset($hideMenu)){
        $hideMenu=false; 
    }
    if($hideMenu){
        $sidebarclass='sidebar-collapse';
    }else{
        $sidebarclass='';
    }
    frontend\assets\AppAsset::register($this);
    dmstr\web\AdminLteAsset::register($this);
    //Yii::$app->assetManager->forceCopy=true;
    ProfileAsset::register($this);
    //Yii::$app->assetManager->forceCopy=false;
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    
    ?>
    <?php 
      //for logging pages accessed by the user
      if(isset(Yii::$app->user->identity->profile->rstl_id)) {

            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

            $page_request = Yii::$app->request;
            $page_controller = Yii::$app->controller;
            $pstcId = !empty(Yii::$app->user->identity->profile->pstc_id) ? (int) Yii::$app->user->identity->profile->pstc_id : NULL;

            $logpage = new Visitedpage();            
            $logpage->absolute_url = $page_request->absoluteUrl;
            $logpage->home_url = $page_request->hostInfo;
            $logpage->module =  $page_controller->module->id;
            $logpage->controller = $page_controller->id;
            $logpage->action = $page_controller->action->id;
            $logpage->user_id = (int) Yii::$app->user->identity->profile->user_id;
            $logpage->params = $page_request->queryString;
            $logpage->rstl_id = $rstlId;
            $logpage->pstc_id = $pstcId;
            $logpage->date_visited = date('Y-m-d H:i:s');
            if($logpage->save()) {
              //echo 'save';
            } else {
              print_r($logpage->getErrors());
            }
      } 
      // else {
      //     //return 'Session time out!';
      //     return Yii::$app->controller->redirect(['/site/login']);
      // }

    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>EULIMS | <?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <?php $this->head() ?>
        <?php echo PHP_EOL; ?>
        <script type="text/javascript">
            function ToggleLeftMenu(){
                // $.post("/ajax/togglemenu", {}, function(result){
                //     if(result){
                //         //
                //     }
                // });
            }
            function PreviewReport(PDFUrl){
                /*
                * This Function will preview Generated PDF
                * With the given URL of PDF Action
                */
                //alert(PDFUrl);
                var url="/reports/preview?url="+PDFUrl;
                window.open(url, '_blank');
            }
                        function CurrencyFormat(number,decimalplaces){
               if (typeof decimalplaces === 'undefined'){ 
                   decimalplaces = 2; 
               }
               var decimalcharacter = ".";
               var thousandseparater = ",";
               number = parseFloat(number);
               var sign = number < 0 ? "-" : "";
               var formatted = new String(number.toFixed(decimalplaces));
               if( decimalcharacter.length && decimalcharacter != "." ) { formatted = formatted.replace(/\./,decimalcharacter); }
               var integer = "";
               var fraction = "";
               var strnumber = new String(formatted);
               var dotpos = decimalcharacter.length ? strnumber.indexOf(decimalcharacter) : -1;
               if( dotpos > -1 )
               {
                  if( dotpos ) { integer = strnumber.substr(0,dotpos); }
                  fraction = strnumber.substr(dotpos+1);
               }
               else { integer = strnumber; }
               if( integer ) { integer = String(Math.abs(integer)); }
               while( fraction.length < decimalplaces ) { fraction += "0"; }
               temparray = new Array();
               while( integer.length > 3 )
               {
                  temparray.unshift(integer.substr(-3));
                  integer = integer.substr(0,integer.length-3);
               }
               temparray.unshift(integer);
               integer = temparray.join(thousandseparater);
               return sign + integer + decimalcharacter + fraction;
            }
            function StringToFloat(str, decimalForm){
                    //This function will convert string value into Float valid values
                    if (typeof decimalForm === 'undefined'){ 
                            decimalForm = 2; 
                    }
                    var v=str.replace(',','').replace(' ','');
                    v=v.replace(',','').replace(' ','');
                    v=parseFloat(v);
                    //console.log(v);
                    var v=v.toFixed(decimalForm);
                    return v;
            }
            function ShowSystemProgress(df=true){
                if(df){
                    $("#eulims_progress").removeClass("progress-stop").addClass("progress-start");
                }else{
                     $("#eulims_progress").removeClass("progress-start").addClass("progress-stop");
                }
            }
        </script>
		<script type="text/javascript">
			function get_unresponded_notifications()
			{
				$.ajax({
					url: '/referrals/notification/count_unresponded_notification',
					dataType: 'json',
					method: 'GET',
					success: function (data) {
						if (data.num_notification > 0){
							$('#count_noti_sub_referral').html(data.num_notification);
							$('#count_noti_menu').html(data.all_notifications);
						} else if(data.num_notification == 0 && data.all_notifications == 0) {
							$('#count_noti_sub_referral').html('');
							$('#count_noti_menu').html('');
						} else if(data.num_notification == 0 && data.all_notifications > 0) {
							$('#count_noti_sub_referral').html('');
							$('#count_noti_menu').html(data.all_notifications);
						} else {
							console.log(data.num_notification);
						}
					},
					/*beforeSend: function (xhr) {
						$("#modalContent").html("<img src='/images/img-loader64.gif' alt='' style='display: block;margin-left: auto;margin-right: auto;'>");
					},*/
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('error occured!');
					}
				});
			}
			
			function get_unseen_bidnotifications()
			{
				$.ajax({
					url: '/referrals/bidnotification/count_unseen_bidnotification',
					dataType: 'json',
					method: 'GET',
					success: function (data) {
						if (data.bid_notification > 0){
							$('#count_noti_sub_bid').html(data.bid_notification);
							$('#count_noti_menu').html(data.all_notifications);
						} else if(data.bid_notification == 0 && data.all_notifications == 0) {
							$('#count_noti_sub_bid').html('');
							$('#count_noti_menu').html('');
						} else if(data.bid_notification == 0 && data.all_notifications > 0) {
							$('#count_noti_sub_bid').html('');
							$('#count_noti_menu').html(data.all_notifications);
						} else {
							console.log(data.bid_notification);
						}
					},
					/*beforeSend: function (xhr) {
						$("#modalContent").html("<img src='/images/img-loader64.gif' alt='' style='display: block;margin-left: auto;margin-right: auto;'>");
					},*/
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('error occured!');
					}
				});
			}
		</script>
    </head>
    <body class="hold-transition skin-blue <?= $sidebarclass ?>">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
