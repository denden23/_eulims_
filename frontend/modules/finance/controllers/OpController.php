<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\Op;
use common\models\finance\Paymentitem;
use common\models\finance\Collection;
use common\models\finance\OpSearch;
use common\models\lab\Request;
use common\models\finance\Collectiontype;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use common\components\Functions;
use kartik\editable\Editable;
use yii\helpers\Json;
/**
 * OrderofpaymentController implements the CRUD actions for Op model.
 */
class OpController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Op models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Op();
        $searchModel = new OpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Op model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    { 
         $paymentitem_Query = Paymentitem::find()->where(['orderofpayment_id' => $id]);
         $paymentitemDataProvider = new ActiveDataProvider([
                'query' => $paymentitem_Query,
                'pagination' => [
                    'pageSize' => 10,
                ],
        ]);
         
         return $this->render('view', [
            'model' => $this->findModel($id),
            'paymentitemDataProvider' => $paymentitemDataProvider,
        ]);

    }

    /**
     * Creates a new Op model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Op();
        $paymentitem = new Paymentitem();
        $collection= new Collection();
        $searchModel = new OpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=5;
        
        //echo "<pre>";
        //print_r($this->Gettransactionnum());
        //echo "</pre>";
        
         if ($model->load(Yii::$app->request->post())) {
             $transaction = Yii::$app->financedb->beginTransaction();
             $session = Yii::$app->session;
             try  {
                 
                        $request_ids=$model->RequestIds;
                        $str_request = explode(',', $request_ids);
                        $model->rstl_id=$GLOBALS['rstl_id'];
                        $model->transactionnum= $this->Gettransactionnum();

                        $model->save();
                       //Saving for Paymentitem
                        $arr_length = count($str_request); 
                       // $total_amount=0;
                        for($i=0;$i<$arr_length;$i++){
                             $request =$this->findRequest($str_request[$i]);
                             $paymentitem = new Paymentitem();
                             $paymentitem->rstl_id =$GLOBALS['rstl_id'];
                             $paymentitem->request_id = $str_request[$i];
                             $paymentitem->orderofpayment_id = $model->orderofpayment_id;
                             $paymentitem->details =$request->request_ref_num;
                             $paymentitem->amount = $request->total;
                            // $total_amount+=$request->total;
                             $paymentitem->save(); 
                        }
                        //----------------------//
                        //---Saving for Collection-------

                        $collection_name= $this->getCollectionname($model->collectiontype_id);
                        $collection->nature=$collection_name['natureofcollection'];
                        $collection->rstl_id=$GLOBALS['rstl_id'];
                        $collection->orderofpayment_id=$model->orderofpayment_id;
                        $collection->referral_id=0;
                        $collection->save(false);
                        //
                        $transaction->commit();
                        $this->postRequest($request_ids);
                        $session->set('savepopup',"executed");
                         return $this->redirect(['/finance/op']);
                    
                } catch (Exception $e) {
                    $transaction->rollBack();
                   $session->set('errorpopup',"executed");
                   return $this->redirect(['/finance/op']);
                }
                
              //  var_dump($total_amount);
              //  exit;
                //-------------------------------------------------------------//
               
          
        } 
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
    }

    /**
     * Updates an existing Orderofpayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->orderofpayment_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

     public function actionGetlistrequest($id)
    {
         $model= new Request();
        $query = Request::find()->where(['customer_id' => $id,'posted' => 0]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       // $dataProvider->pagination->pageSize=3;
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_request', ['dataProvider'=>$dataProvider,'model'=>$model]);
        }
        else{
            return $this->render('_request', ['dataProvider'=>$dataProvider,'model'=>$model]);
        }

    }
    /**
     * Deletes an existing Op model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Op model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Op the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Op::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findRequest($requestId)
    {
        if (($model = Request::findOne($requestId)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     public function Gettransactionnum(){
        /*$year=date('Y');
        $terminal_id=1;
        $rstl_id=11;
        $result = \Yii::$app->financedb->createCommand("CALL spGenerateTransactionNumber(:mYear, :mTerminalID, :mRSTLID)",[
            ':mYear'=>$year,
            ':mTerminalID'=>$terminal_id,
            ':mRSTLID'=>$rstl_id
            ])
            ->queryAll();
        $transnumber=$result[0]['transaction_number'];
        return $transnumber;*/
          $year_month = date('Y-m');
          $last_trans_num=(new Query)
            ->select(['count(transactionnum)+ 1 AS lastnumber'])
            ->from('eulims_finance.tbl_orderofpayment')
            ->one();
          $str_trans_num=0;
          if($last_trans_num != ''){
              $str_trans_num=str_pad($last_trans_num["lastnumber"], 4, "0", STR_PAD_LEFT);
          }
          else{
               $str_trans_num='0000';
          }
         
         $next_transnumber=$year_month."-".$str_trans_num;
         return $next_transnumber;
        
     }
     
     public function actionCalculateTotal($id) {
        $post = Yii::$app->request->post();
        $total = 0;
        if($id == '' ){
            echo $total;
        }
        else{
            $str_total = explode(',', $id);
            $arr_length = count($str_total); 
            for($i=0;$i<$arr_length;$i++){
                 $request =$this->findRequest($str_total[$i]);
                 $total+=$request->total;
            }
            echo $total;
        }
     }
     
     public function getCollectionname($collectionid) {
         $collection_name=(new Query)
            ->select('natureofcollection')
            ->from('eulims_finance.tbl_collectiontype')
            ->where(['collectiontype_id' => $collectionid])
            ->one();
         return $collection_name;
     }
     
       // updating request as posted upon saving order of payment
     public function postRequest($reqID){
         $str_total = explode(',', $reqID);
          $arr_length = count($str_total); 
            for($i=0;$i<$arr_length;$i++){
               Yii::$app->labdb->createCommand()
             ->update('tbl_request', ['posted' => 1], 'request_id= '.$str_total[$i])
             ->execute(); 
            }
     }
}