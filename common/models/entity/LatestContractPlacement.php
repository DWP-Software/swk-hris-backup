<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "_latest_contract_placement".
 *
 * @property integer $employee_id
 * @property integer $contract_id
 * @property integer $employee_type_id
 * @property string $position
 * @property string $location
 * @property string $contract_started_at
 * @property string $contract_ended_at
 * @property string $file
 * @property integer $contract_placement_id
 * @property integer $client_id
 * @property string $contract_placement_started_at
 * @property string $contract_placement_ended_at
 */
class LatestContractPlacement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '_latest_contract_placement';
    }
    public static function primaryKey()
    {
        return ['contract_placement_id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'contract_id', 'employee_type_id', 'contract_placement_id', 'client_id'], 'integer'],
            [['contract_started_at', 'contract_ended_at', 'contract_placement_started_at', 'contract_placement_ended_at'], 'safe'],
            [['file'], 'string'],
            [['position', 'location', 'department'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee',
            'contract_id' => 'Contract',
            'employee_type_id' => 'Employee Type',
            'position' => 'Position',
            'location' => 'Location',
            'department' => 'Department',
            'contract_started_at' => 'Contract Started At',
            'contract_ended_at' => 'Contract Ended At',
            'file' => 'File',
            'contract_placement_id' => 'Contract Placement',
            'client_id' => 'Client',
            'contract_placement_started_at' => 'Contract Placement Started At',
            'contract_placement_ended_at' => 'Contract Placement Ended At',
        ];
    }
    
    public function getContractPlacement()
    {
        return $this->hasOne(ContractPlacement::className(), ['id' => 'contract_placement_id']);
    }
    
    public function getClient()
    {
        return $this->hasOne(ContractPlacement::className(), ['id' => 'client_id']);
    }
}
