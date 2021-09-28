<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "audit_javascript".
 *
 * @property integer $id
 * @property integer $entry_id
 * @property string $created
 * @property string $type
 * @property string $message
 * @property string $origin
 * @property resource $data
 */
class AuditJavascript extends \yii\db\ActiveRecord
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
        return 'audit_javascript';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry_id', 'created', 'type', 'message'], 'required'],
            [['entry_id'], 'integer'],
            [['created'], 'safe'],
            [['message', 'data'], 'string'],
            [['type'], 'string', 'max' => 20],
            [['origin'], 'string', 'max' => 512],
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
            'created' => 'Created',
            'type' => 'Type',
            'message' => 'Message',
            'origin' => 'Origin',
            'data' => 'Data',
        ];
    }
}
