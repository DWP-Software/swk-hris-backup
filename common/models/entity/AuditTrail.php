<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "audit_trail".
 *
 * @property integer $id
 * @property integer $entry_id
 * @property integer $user_id
 * @property string $action
 * @property string $model
 * @property string $model_id
 * @property string $field
 * @property string $old_value
 * @property string $new_value
 * @property string $created
 */
class AuditTrail extends \yii\db\ActiveRecord
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
        return 'audit_trail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry_id', 'user_id'], 'integer'],
            [['action', 'model', 'model_id', 'created'], 'required'],
            [['old_value', 'new_value'], 'string'],
            [['created'], 'safe'],
            [['action', 'model', 'model_id', 'field'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entry_id' => 'Entry',
            'user_id' => 'User',
            'action' => 'Action',
            'model' => 'Model',
            'model_id' => 'Model',
            'field' => 'Field',
            'old_value' => 'Old Value',
            'new_value' => 'New Value',
            'created' => 'Created',
        ];
    }
}
