<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "placement_plan".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $client_id
 * @property integer $plan_employee_type
 * @property string $plan_started_at
 * @property string $plan_ended_at
 * @property string $remark
 * @property integer $submitted_at
 * @property integer $responded_at
 * @property integer $response_type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property ContractPlacement[] $contractPlacements
 * @property Employee $employee
 * @property Client $client
 * @property EmployeeType $planEmployeeType
 * @property User $createdBy
 * @property User $updatedBy
 */
class PlacementPlan extends \yii\db\ActiveRecord
{
    CONST RESPONSE_WAITING  = 0;
    CONST RESPONSE_ACCEPTED = 1;
    CONST RESPONSE_REJECTED = 2;

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
        return 'placement_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'client_id', 'submitted_at'], 'required'],
            [['employee_id', 'client_id', 'plan_employee_type', 'submitted_at', 'responded_at', 'response_type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['plan_started_at', 'plan_ended_at'], 'safe'],
            [['remark'], 'string'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['plan_employee_type'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeType::className(), 'targetAttribute' => ['plan_employee_type' => 'id']],
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
            'id'                 => 'ID',
            'employee_id'        => 'Calon Karyawan',
            'client_id'          => 'Mitra',
            'plan_employee_type' => 'Rencana Jenis Kontrak',
            'plan_started_at'    => 'Rencana Mulai Kontrak',
            'plan_ended_at'      => 'Rencana Akhir Kontrak',
            'remark'             => 'Keterangan',
            'submitted_at'       => 'Diajukan pada',
            'responded_at'       => 'Direspon pada',
            'response_type'      => 'Hasil',
            'created_at'         => 'Created At',
            'updated_at'         => 'Updated At',
            'created_by'         => 'Created By',
            'updated_by'         => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractPlacements()
    {
        return $this->hasMany(ContractPlacement::className(), ['placement_plan_id' => 'id']);
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
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanEmployeeType()
    {
        return $this->hasOne(EmployeeType::className(), ['id' => 'plan_employee_type']);
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

    
    public static function responseTypes($index = 'all') {
        $array = [
            self::RESPONSE_WAITING  => 'Menunggu',
            self::RESPONSE_ACCEPTED => 'Diterima',
            self::RESPONSE_REJECTED => 'Ditolak',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public function getResponseTypeText() {
        return self::responseTypes(strval($this->response_type));
    }
}
