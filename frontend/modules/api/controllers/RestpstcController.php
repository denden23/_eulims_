<?php

namespace frontend\modules\api\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\db\ActiveQuery;
use common\models\referral\Pstcrequest;
use common\models\referral\Pstcsample;
use common\models\referral\Lab;
use common\models\referral\Testname;
use common\models\referral\Testnamemethod;
use common\models\referral\Sampletype;
use common\models\referral\Pstcanalysis;
use common\models\referral\Methodreference;
use yii\helpers\ArrayHelper;

class RestpstcController extends \yii\rest\Controller
{

    // ------------------------------------
    // Function for Requests. (Create, Read, Update, Delete)
    // ------------------------------------

    public function actionRequest() //Get pstcrequest with customer relationship
    {
        $request = Yii::$app->request;
        $rstl_id = (int) $request->get('rstl_id');

        if($rstl_id) {
            $data = Pstcrequest::find()->joinWith('customer')->where(['tbl_pstcrequest.rstl_id'=>$rstl_id])
                ->orderBy('created_at DESC')
                ->asArray()
                ->all();
            
            return $data;
        }else{
            throw new \yii\web\HttpException(400, 'Please specify RSTL ID. :)');
        }
    }

    public function actionRequestcreate() //Create or Update pstc request
    {
        $data = ($request->isPut) ? Pstcrequest::find()->where(['id' => $id])->one() : new Pstcrequest;
        $data->rstl_id = 11;
        $data->pstc_id = 113;
        $data->customer_id = (int) Yii::$app->request->post('customer_id');
        $data->submitted_by=  ucwords(strtolower(Yii::$app->request->post('submitted')));
        $data->received_by = ucwords(strtolower(Yii::$app->request->post('received')));
        $data->user_id = (int) Yii::$app->request->post('user_id');
        $data->status_id = 1;
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save(false);

        return $data;
    }

    public function actionRequestview()  //View a specific request record.
    {
        $getrequest = Yii::$app->request;

        $rstl_id = (int) $getrequest->get('rstl_id');
        $request_id = (int) $getrequest->get('request_id');
        $pstc_id = (int) $getrequest->get('pstc_id');

        $request = []; $samples = []; $respond = []; $customer = []; $attachment = []; $pstc = [];
        $subtotal = 0; $discount = 0; $total = 0; $analysis = [];

        if(!empty($rstl_id) && !empty($request_id) && !empty($pstc_id)){
            
            $checkAgency = $this->checkAgency($request_id,$rstl_id);

            if($checkAgency > 0)
            {
                $request = Pstcrequest::findOne($request_id);

                //return $request->samples[0]->analysis;
                foreach($request->samples as $sample)
                {   
                    if(count($sample->analysis) > 0){
                        $analysis[] = $sample->analysis;
                        $subtotal =  $subtotal + $sample->analysis->fee;
                    }
                }
                $discount = $subtotal * ($request->discount_rate/100);
                $total = $subtotal - $discount;
            }
            
            return [
                'request_data'=>$request,
                'sample_data'=>$request->samples,
                'analysis_data'=>$analysis,
                'respond_data'=>$request->respond,
                'pstc_data'=>$request->pstc,
                'customer_data'=>$request->customer,
                'attachment_data' => ($request->attachment == null) ? $attachment = [] : $request->attachment,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
            ];
            

        }else{
            throw new \yii\web\HttpException(400, 'Please specify parameters correctly. :)');
        }
    }

    // ------------------------------------
    // Function for samples. (Create, Update, Delete)
    // ------------------------------------

    public function actionSample()
    {   
        (Yii::$app->request->post('pstc_request_id')) ? $id = Yii::$app->request->post('pstc_request_id') : '';

        $qnty = Yii::$app->request->post('qnty');
        
        for($x = 0; $x < $qnty; $x++)
        {
            $data = (Yii::$app->request->isPut) ? Psctsample::findOne($id) : new Pstcsample; 
            $data->sample_name = ucwords(strtolower(Yii::$app->request->post('sample_name')));
            $data->sample_description = ucfirst(Yii::$app->request->post('sample_description'));
            $data->rstl_id = 11;
            $data->pstc_id = 113;
            $data->pstc_request_id = (int) Yii::$app->request->post('pstc_request_id');
            $data->sampletype_id = 0;
            $data->processed_old = 0;
            $data->created_at = date('Y-m-d H:i:s');
            $data->updated_at = date('Y-m-d H:i:s');
            $data->save(false);
        }
        return $data;
    }

    public function actionSampledelete()
    {
        $data = Psctsample::find()->where(['id' => $id])->one();
        return $data->delete();
    }

    // ------------------------------------
    // Function for Analysis. (Create, Update, Delete)
    // ------------------------------------

    public function actionAnalysis()
    {   
        $id = (int) Yii::$app->request->post('rstl_id');

        $data = (Yii::$app->request->isPut) ? Psctanalysis::findOne($id)  : new Pstcanalysis; 
        $data->rstl_id = Yii::$app->request->post('rstl_id');
        $data->pstc_id = Yii::$app->request->post('pstc_id');
        $data->pstc_sample_id = Yii::$app->request->post('pstc_sample_id');
        $data->testname_id = 0;
        $data->testname = Yii::$app->request->post('testname');
        $data->method_id = 0;
        $data->method = Yii::$app->request->post('method');
        $data->reference = Yii::$app->request->post('reference');
        $data->fee = Yii::$app->request->post('fee');
        $data->quantity = 1;
        $data->package_old =0;
        $data->deleted_old = 0;
        $data->taggingId_old = 0;
        $data->testId_old = 0;
        $data->user_id = 0;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->created_at = date('Y-m-d H:i:s');
        $data->save(false);

        return $data;
    }

    public function actionAnalysisdelete()
    {
        $data = Psctanalysis::find()->where(['id' => $id])->one();
        return $data->delete();
    }

    // ------------------------------------
    // Protected function use for filtering
    // ------------------------------------

    protected function checkAgency($request_id,$rstl_id) //Check if the agency is the owner of the Request.
	{
        $check = Pstcrequest::find()
            ->where('pstc_request_id =:requestId', [':requestId' => $request_id])
            ->andWhere('rstl_id =:rstl_id', [':rstl_id' => $rstl_id])
            ->count();

        return ($check > 0) ? $status = 1 : $status = 0;
    }
    
    protected function checkAccepted($request_id,$rstl_id,$pstc_id) // Check if request is already accepted.
	{
        $check = Pstcrequest::find()
            ->joinWith('respond',true,'INNER JOIN')
            ->where('tbl_pstcrespond.pstc_request_id =:requestId AND accepted =:accepted AND !ISNULL(request_ref_num)', [':requestId' => $request_id,':accepted'=>1])
            ->andWhere('tbl_pstcrequest.rstl_id =:rstlId', [':rstlId' => $rstl_id])
            ->andWhere('tbl_pstcrequest.pstc_id =:pstcId', [':pstcId' => $pstc_id])
            ->count();
            
        return ($check > 0) ? $status = 1 : $status = 0;
    }

    // ------------------------------------
    // Function for Syncing testmethod..
    // ------------------------------------
    
    public function actionCheckmethod($id) //CHECK IF TESTMETHOD IS ALREADY SYNC
    { 
        $data = Methodreference::find()->where(['sync_id' => $id])->one();
        $count = count($data);

        if($count == 0){
            return 'Not Synced';
        }else{
            return 'Synced';
        }
    }

    public function actionSyncmethod() // SYNC TESTMETHOD TO THE MAIN SERVER
    { 
        $data = new Methodreference; 
        $data->method = Yii::$app->request->post('method');
        $data->reference = Yii::$app->request->post('reference');
        $data->fee = Yii::$app->request->post('fee');
        $data->sync_id = Yii::$app->request->post('sync_id');
        $data->create_time = date('Y-m-d H:i:s');
        $data->update_time = date('Y-m-d H:i:s');

        if($data->save(false))
        {
            $test = new Testnamemethod; 
            $test->testname_id = Yii::$app->request->post('testname_id');
            $test->methodreference_id = $data->methodreference_id;
            $test->lab_id = Yii::$app->request->post('lab_id');
            $test->sampletype_id = Yii::$app->request->post('sampletype_id');
            $test->create_time = date('Y-m-d H:i:s');
            $test->update_time = date('Y-m-d H:i:s');
            $test->save(false);
        }
        return $test;
    }

    public function actionListlab() // GET LISTS OF LAB, SAMPLE TYPE AND TESTNAME IN THE MAIN SERVER
    {
        $labs = ArrayHelper::map(Lab::find()->all(), 'lab_id', 
            function($laboratory, $defaultValue) {
                return $laboratory->labname;
        });

        $sampletypes = ArrayHelper::map(Sampletype::find()->all(), 'sampletype_id', 
            function($sampletypes, $defaultValue) {
                return $sampletypes->type;
        });

         $testnamelist = ArrayHelper::map(Testname::find()->all(), 'testname_id', 
            function($testnamelist, $defaultValue) {
                return $testnamelist->test_name;
        });
        
        $lists = array(
            'labs' => $labs,
            'sampletypes' => $sampletypes,
            'testnamelist' => $testnamelist
        );

        return $lists;
    }

}
