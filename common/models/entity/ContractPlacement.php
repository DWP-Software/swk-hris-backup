<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "contract_placement".
 *
 * @property integer $id
 * @property integer $contract_id
 * @property integer $client_id
 * @property string $started_at
 * @property string $ended_at
 * @property integer $head_user_id
 * @property string $position
 * @property string $location
 * @property string $project
 * @property string $site
 * @property string $department
 * @property integer $placement_plan_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Contract $contract
 * @property Client $client
 * @property PlacementPlan $placementPlan
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $headUser
 */
class ContractPlacement extends \yii\db\ActiveRecord
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
        return 'contract_placement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'client_id', 'started_at'], 'required'],
            [['contract_id', 'client_id', 'head_user_id', 'placement_plan_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['started_at', 'ended_at'], 'safe'],
            [['position', 'location', 'project', 'site', 'department'], 'string', 'max' => 255],
            [['contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contract::className(), 'targetAttribute' => ['contract_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['placement_plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlacementPlan::className(), 'targetAttribute' => ['placement_plan_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            /* [['ended_at'], 'required', 'when' => function($model) {
                return $model->contract->employeeType->name != 'DW';
            }, 'enableClientValidation' => false], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'contract_id'       => 'Kontrak',
            'client_id'         => 'Mitra',
            'started_at'        => 'Mulai',
            'ended_at'          => 'Selesai',
            'head_user_id'      => 'PIC',
            'position'          => 'Jabatan',
            'location'          => 'Lokasi',
            'project'           => 'Project',
            'site'              => 'Site',
            'department'        => 'Department',
            'placement_plan_id' => 'Placement Plan',
            'created_at'        => 'Created At',
            'updated_at'        => 'Updated At',
            'created_by'        => 'Created By',
            'updated_by'        => 'Updated By',
        ];
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
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPlan()
    {
        return $this->hasOne(PlacementPlan::className(), ['id' => 'placement_plan_id']);
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
   public function getHeadUser() 
   { 
       return $this->hasOne(User::className(), ['id' => 'head_user_id']); 
   }
}
