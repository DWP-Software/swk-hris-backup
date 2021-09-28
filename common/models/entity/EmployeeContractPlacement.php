<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "_employee_contract_placement".
 *
 * @property integer $employee_id
 * @property integer $user_id
 * @property string $identity_number
 * @property string $registration_number
 * @property string $phone
 * @property string $name
 * @property string $date_of_birth
 * @property string $bank_name
 * @property string $bank_account
 * @property integer $employee_created_at
 * @property integer $contract_id
 * @property integer $employee_type_id
 * @property string $contract_started_at
 * @property string $contract_ended_at
 * @property string $resigned_at
 * @property string $file
 * @property integer $contract_created_at
 * @property integer $contract_placement_id
 * @property integer $client_id
 * @property string $contract_placement_started_at
 * @property string $contract_placement_ended_at
 * @property string $position
 * @property string $location
 * @property integer $contract_placement_created_at
 */
class EmployeeContractPlacement extends \yii\db\ActiveRecord
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
        return '_employee_contract_placement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'user_id', 'employee_created_at', 'contract_id', 'employee_type_id', 'contract_created_at', 'contract_placement_id', 'client_id', 'contract_placement_created_at'], 'integer'],
            [['identity_number', 'phone', 'name', 'date_of_birth'], 'required'],
            [['date_of_birth', 'contract_started_at', 'contract_ended_at', 'resigned_at', 'contract_placement_started_at', 'contract_placement_ended_at'], 'safe'],
            [['file'], 'string'],
            [['identity_number', 'registration_number', 'phone'], 'string', 'max' => 191],
            [['name', 'bank_name', 'bank_account', 'position', 'location', 'department'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id'                   => 'Employee',
            'user_id'                       => 'User',
            'identity_number'               => 'Identity Number',
            'registration_number'           => 'Registration Number',
            'phone'                         => 'Phone',
            'name'                          => 'Name',
            'date_of_birth'                 => 'Date Of Birth',
            'bank_name'                     => 'Bank Name',
            'bank_account'                  => 'Bank Account',
            'employee_created_at'           => 'Employee Created At',
            'contract_id'                   => 'Contract',
            'employee_type_id'              => 'Employee Type',
            'contract_started_at'           => 'Contract Started At',
            'contract_ended_at'             => 'Contract Ended At',
            'resigned_at'                   => 'Resigned At',
            'file'                          => 'File',
            'contract_created_at'           => 'Contract Created At',
            'contract_placement_id'         => 'Contract Placement',
            'client_id'                     => 'Client',
            'contract_placement_started_at' => 'Contract Placement Started At',
            'contract_placement_ended_at'   => 'Contract Placement Ended At',
            'position'                      => 'Position',
            'location'                      => 'Location',
            'department'                    => 'Department',
            'contract_placement_created_at' => 'Contract Placement Created At',
        ];
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
    }
    
    public function getContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
    }
    
    public function getContractPlacement()
    {
        return $this->hasOne(ContractPlacement::className(), ['id' => 'contract_id']);
    }
}
