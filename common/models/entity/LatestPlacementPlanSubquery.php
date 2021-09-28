<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "_latest_placement_plan_subquery".
 *
 * @property integer $employee_id
 * @property integer $id
 */
class LatestPlacementPlanSubquery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '_latest_placement_plan_subquery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee',
            'id' => 'ID',
        ];
    }
}
