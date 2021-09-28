<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "audit_data".
 *
 * @property integer $id
 * @property integer $entry_id
 * @property string $type
 * @property resource $data
 * @property string $created
 */
class AuditData extends \yii\db\ActiveRecord
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
        return 'audit_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry_id', 'type'], 'required'],
            [['entry_id'], 'integer'],
            [['data'], 'string'],
            [['created'], 'safe'],
            [['type'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'data' => 'Data',
            'created' => 'Created',
        ];
    }
}
