<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "payroll".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $contract_id
 * @property integer $client_id
 * @property integer $year
 * @property integer $month
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Client $client
 * @property Contract $contract
 * @property Employee $employee
 * @property PayrollDetail[] $payrollDetails
 */
class Payroll extends \yii\db\ActiveRecord
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
        return 'payroll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'contract_id', 'client_id', 'year', 'month', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['contract_id', 'year', 'month'], 'required'],
            [['contract_id', 'year', 'month'], 'unique', 'targetAttribute' => ['contract_id', 'year', 'month'], 'message' => 'The combination of Contract, Year and Month has already been taken.'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contract::className(), 'targetAttribute' => ['contract_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee',
            'contract_id' => 'Contract',
            'client_id' => 'Client',
            'year' => 'Year',
            'month' => 'Month',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollDetails()
    {
        return $this->hasMany(PayrollDetail::className(), ['payroll_id' => 'id']);
    }

    public function getGrossPayment()
    {
        return PayrollDetail::find()->where(['payroll_id' => $this->id])
        ->andWhere(['<', 'type', '3'])
        ->sum('amount');
    }

    public function getTotalCut()
    {
        return PayrollDetail::find()->where(['payroll_id' => $this->id])
        ->andWhere(['=', 'type', '3'])
        ->sum('amount');
    }

    public function getNetSalary()
    {
        return $this->grossPayment - $this->totalCut;
    }

    public function getBaseSalary()
    {
        return PayrollDetail::find()->where(['payroll_id' => $this->id])
        ->andWhere(['=', 'type', '1'])
        ->sum('amount');
    }
}
