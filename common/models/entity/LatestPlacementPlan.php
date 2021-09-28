<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "_latest_placement_plan".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $client_id
 * @property integer $submitted_at
 * @property integer $responded_at
 * @property integer $response_type
 */
class LatestPlacementPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '_latest_placement_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'client_id', 'submitted_at', 'responded_at', 'response_type'], 'integer'],
            [['employee_id', 'client_id', 'submitted_at'], 'required'],
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
            'client_id' => 'Client',
            'submitted_at' => 'Submitted At',
            'responded_at' => 'Responded At',
            'response_type' => 'Response Type',
        ];
    }

    
    public function getPlacementPlan()
    {
        return $this->hasOne(PlacementPlan::className(), ['id' => 'id']);
    }
}
