<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_testname_method".
 *
 * @property int $testname_method_id
 * @property int $testname_id
 * @property int $methodreference_id
 * @property string $added_by
 * @property string $create_time
 * @property string $update_time
 *
 * @property Testname $testname
 * @property Methodreference $methodreference
 */
class Testnamemethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_testname_method';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('referraldb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'testname_id', 'method_id', 'lab_id', 'sampletype_id', 'create_time'], 'required'],
            [['testname_id', 'id'], 'integer'],
            [['added_by' , 'create_time', 'update_time'], 'safe'],
            [['added_by'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Testname Method ID',
            'testname_id' => 'Testname ID',
            'lab_id' => 'Lab ID',
            'sampletype_id' => 'Sampletype ID',
            'method_id' => 'Methodreference ID',
            'added_by' => 'Added By',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestname()
    {
        return $this->hasOne(Testname::className(), ['testname_id' => 'testname_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMethodreference()
    {
        return $this->hasOne(Methodreference::className(), ['method_id' => 'methodreference_id']);
    }

    public function getLab()
    {
        return $this->hasOne(Lab::className(), ['lab_id' => 'lab_id']);
    }
}
