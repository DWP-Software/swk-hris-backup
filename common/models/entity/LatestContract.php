<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "_latest_contract".
 *
 * @property integer $employee_id
 * @property integer $contract_id
 * @property integer $employee_type_id
 * @property string $position
 * @property string $location
 * @property string $contract_started_at
 * @property string $contract_ended_at
 * @property string $file
 */
class LatestContract extends \yii\db\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '_latest_contract';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'contract_id', 'employee_type_id'], 'integer'],
            [['contract_started_at', 'contract_ended_at'], 'safe'],
            [['file'], 'string'],
            [['position', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id'         => 'Employee',
            'contract_id'         => 'Contract',
            'employee_type_id'    => 'Employee Type',
            'position'            => 'Position',
            'location'            => 'Location',
            'contract_started_at' => 'Contract Started At',
            'contract_ended_at'   => 'Contract Ended At',
            'file'                => 'File',
        ];
    }
    
    public function getContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }
}
