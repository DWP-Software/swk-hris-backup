<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "payroll_detail".
 *
 * @property integer $id
 * @property integer $payroll_id
 * @property integer $type
 * @property string $name
 * @property double $amount
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Payroll $payroll
 * @property User $createdBy
 * @property User $updatedBy
 */
class PayrollDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
            \bedezign\yii2\audit\AuditTrailBehavior::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payroll_id', 'type', 'name', 'amount'], 'required'],
            [['payroll_id', 'type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['payroll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payroll::className(), 'targetAttribute' => ['payroll_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payroll_id' => 'Payroll',
            'type' => 'Type',
            'name' => 'Name',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayroll()
    {
        return $this->hasOne(Payroll::className(), ['id' => 'payroll_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
