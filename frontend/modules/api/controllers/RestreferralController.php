<?php

namespace frontend\modules\api\controllers;

use common\models\referral\Lab;
use common\models\referral\Discount;
use common\models\referral\Purpose;
use common\models\referral\Modeofrelease;
use common\models\referral\Sampletype;
use common\models\referral\Sampletypetestname;
use common\models\referral\Testnamemethod;
use common\models\referral\Testname;
use common\models\referral\Methodreference;
use common\models\referral\Packagelist;
use common\models\referral\Agency;
use yii\db\Query;

class RestreferralController extends \yii\rest\Controller
{
	public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
            'except' => ['*'],
            'user'=> \Yii::$app->referralaccount
        ];

        return $behaviors;
    }

    protected function verbs(){
        return [
            // 'login' => ['POST'],
            // 'user' => ['GET'],
        ];
    }

    public function actionIndex(){
        return "Index";
    }

    public function actionLabs(){
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Lab::find()->all();
        return $data;
    }

    public function actionDiscounts(){
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Discount::find()->all();
        return $data;
    }

    public function actionPurposes(){
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Purpose::find()->all();
        return $data;
    }

    public function actionModesrelease(){
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Modeofrelease::find()->all();
        return $data;
    }

    public function actionGetdiscount($discountid){
        $post= \Yii::$app->request->post();
        $discount= Discount::find()->where(['discount_id'=>$discountid])->one();
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        return $discount;
    }

    public function actionGetcustomer($customer_id){
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $customer = Customer::findOne($customer_id);
        return $customer;
    }

    public function actionListmatchagency($rstl_id,$lab_id,$sampletype_id,$testname_id,$methodref_id,$package_id){
        
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        //these code below is the exact code in the eulimsapi
        $params = [
                ':rstlId'=>$rstl_id,
                ':labId'=>$lab_id,
                ':sampletypeId'=>$sampletype_id,
                ':testnameId'=>$testname_id,
                ':methodrefId'=>$methodref_id,
                ':packageId'=>$package_id
            ];

        //Needs to extract this SP
        $query = \Yii::$app->referraldb->createCommand("
                CALL spGetMatchAgency(:rstlId,:labId,:sampletypeId,:testnameId,:methodrefId,:packageId)");
        $query->bindValues($params);
        $result = $query->queryAll();

        if($query->queryScalar() === false){
                return false;
            } else {
                return $result;
            }

    }

    public function actionListagency($agency_id){

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $agencyId = rtrim($_GET['agency_id']);
        $data = Agency::find()
                    ->where([
                        'agency_id' => explode(',', $agencyId),
                    ])
                    ->orderBy('agency_id')
                    ->asArray()
                    ->all();
                
        return $data;
    }

    public function actionShowupload($referral_id,$rstl_id,$type){
        return null;
    }

    public function actionReferred_agency($referral_id,$rstl_id){
        return null;
    }

    public function actionBidderagency($request_id,$rstl_id){
        return null;
    }

    public function actionBidnotice($request_id,$rstl_id){
        return null;
    }

    //gets the sampletype via lab id
    public function actionSampletypebylab($lab_id){
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Sampletype::find()
            ->joinWith('labsampletypes')
            ->where(['tbl_labsampletype.lab_id' => $lab_id])
            ->asArray()
            ->all();

        return $data;
    }

    /**
     * Lists data sampletype_testname by sampletype_id
     * @return mixed
     */

    public function actionTestnamebysampletypeids($sampletype_ids){

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $sampletypeId = rtrim($sampletype_ids);

        $data = Sampletypetestname::find()
            ->select(['tbl_sampletypetestname.testname_id','test_name'=>'tbl_testname.test_name'])
            ->joinWith('testname')
            ->where(['sampletype_id' => explode(',', $sampletypeId)])
            ->groupBy('tbl_testname.testname_id')
            ->orderBy('tbl_sampletypetestname.testname_id')
            ->asArray()
            ->all();

        return $data;
    }

    public function actionTestnamemethodref($testname_id){
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data= Testnamemethod::find()
            ->joinWith(['testname','methodreference'])
            ->where('tbl_testname_method.testname_id = :testnameId', [':testnameId' => $testname_id])
            ->asArray()
            ->all();
        return $data;
    }

    public function actionTestnameone($testname_id){

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Testname::find()
            ->where('testname_id =:testnameId', [':testnameId'=>$testname_id])
            ->asArray()
            ->one();
        return $data;
    }

    public function actionMethodreferenceone($methodref_id){

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Methodreference::find()
            ->where('methodreference_id =:method_ref_id', [':method_ref_id'=>$methodref_id])
            ->asArray()
            ->one();

    return $data;   
    }

    public function actionDiscountbyid($discount_id){

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Discount::find()->where(['discount_id'=>$discount_id,'status'=>1])->one();        
        return $data;
    }

    public function actionListpackage($lab_id,$sampletype_id){

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $data = Packagelist::find()
            ->where(['lab_id'=>$lab_id,'sampletype_id'=> explode(',', $sampletype_id) ])
            ->all();
        return $data;
    }

    public function actionPackage_detail($package_id){

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        $package = Packagelist::find()->where(['package_id'=>$package_id])->one();

        if(!empty($package->test_methods)){
            $testmethods = explode(",",$package->test_methods);
            $testmethod_data = [];
            foreach ($testmethods as $testmethodId){
                $testmethodrefs = Testnamemethod::findOne($testmethodId);
                $listTestmethods = [
                    'testname_method_id'=>$testmethodrefs->testname_method_id,
                    'testname_id'=>$testmethodrefs->testname_id,
                    'testname'=>$testmethodrefs->testname->test_name,
                    'methodreference_id'=>$testmethodrefs->methodreference_id,
                    'method'=>$testmethodrefs->methodreference->method,
                    'reference'=>$testmethodrefs->methodreference->reference,
                ];
                array_push($testmethod_data, $listTestmethods);
            }
            return ['testmethod_data'=>$testmethod_data,'package'=>$package];
        } else {
            return false;
        }
    }
}