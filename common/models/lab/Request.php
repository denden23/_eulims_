<?php

namespace common\models\lab;

use Yii;
use common\models\system\Rstl;
/**
 * This is the model class for table "tbl_request".
 *
 * @property int $request_id
 * @property string $request_ref_num
 * @property int $request_datetime
 * @property int $rstl_id
 * @property int $lab_id
 * @property int $customer_id
 * @property int $payment_type_id
 * @property int $modeofrelease_id
 * @property string $discount
 * @property int $discount_id
 * @property int $purpose_id
 * @property int $or_id
 * @property string $total
 * @property string $report_due
 * @property string $conforme
 * @property string $receivedBy
 * @property int $created_at
 * @property int $posted
 * @property int $status_id
 *
 * @property Analysis[] $analyses
 * @property Cancelledrequest[] $cancelledrequests
 * @property Generatedrequest[] $generatedrequests
 * @property Lab $lab
 * @property Customer $customer
 * @property Discount $discount0
 * @property Purpose $purpose
 * @property Status $status
 * @property Customer $customer0
 * @property Modeofrelease $modeofrelease
 * @property Paymenttype $paymentType
 * @property Sample[] $samples
 * @property Testreport[] $testreports
 * @property Rstl[] $rstl
 */
class Request extends \yii\db\ActiveRecord
{
    public $customer_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_request';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('labdb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_datetime', 'rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'modeofrelease_id', 'discount_id', 'purpose_id', 'or_id', 'report_due', 'conforme', 'receivedBy', 'created_at'], 'required'],
            [['request_datetime', 'rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'modeofrelease_id', 'discount_id', 'purpose_id', 'or_id', 'created_at', 'posted', 'status_id'], 'integer'],
            [['discount', 'total'], 'number'],
            [['report_due','customer_name'], 'safe'],
            [['customer_name'],'string','max'=>200],
            [['request_ref_num', 'conforme', 'receivedBy'], 'string', 'max' => 50],
            [['request_ref_num'], 'unique'],
            [['lab_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lab::className(), 'targetAttribute' => ['lab_id' => 'lab_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discount::className(), 'targetAttribute' => ['discount_id' => 'discount_id']],
            [['purpose_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purpose::className(), 'targetAttribute' => ['purpose_id' => 'purpose_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'status_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['modeofrelease_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modeofrelease::className(), 'targetAttribute' => ['modeofrelease_id' => 'modeofrelease_id']],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paymenttype::className(), 'targetAttribute' => ['payment_type_id' => 'payment_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'request_ref_num' => 'Request Ref Num',
            'request_datetime' => 'Request Datetime',
            'rstl_id' => 'Rstl ID',
            'lab_id' => 'Lab ID',
            'customer_id' => 'Customer ID',
            'payment_type_id' => 'Payment Type ID',
            'modeofrelease_id' => 'Modeofrelease ID',
            'discount' => 'Discount',
            'discount_id' => 'Discount ID',
            'purpose_id' => 'Purpose ID',
            'or_id' => 'Or ID',
            'total' => 'Total',
            'report_due' => 'Report Due',
            'conforme' => 'Conforme',
            'receivedBy' => 'Received By',
            'created_at' => 'Created At',
            'posted' => 'Posted',
            'status_id' => 'Status ID',
            'customer_name'=>'Customer Name'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalyses()
    {
        return $this->hasMany(Analysis::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelledrequests()
    {
        return $this->hasMany(Cancelledrequest::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneratedrequests()
    {
        return $this->hasMany(Generatedrequest::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLab()
    {
        return $this->hasOne(Lab::className(), ['lab_id' => 'lab_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount0()
    {
        return $this->hasOne(Discount::className(), ['discount_id' => 'discount_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurpose()
    {
        return $this->hasOne(Purpose::className(), ['purpose_id' => 'purpose_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer0()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModeofrelease()
    {
        return $this->hasOne(Modeofrelease::className(), ['modeofrelease_id' => 'modeofrelease_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentType()
    {
        return $this->hasOne(Paymenttype::className(), ['payment_type_id' => 'payment_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSamples()
    {
        return $this->hasMany(Sample::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestreports()
    {
        return $this->hasMany(Testreport::className(), ['request_id' => 'request_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRstl()
    {
        return $this->hasOne(Rstl::className(), ['rstl_id' => 'request_id']);
    }

    
}
