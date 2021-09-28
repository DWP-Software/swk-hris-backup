<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "audit_error".
 *
 * @property integer $id
 * @property integer $entry_id
 * @property string $created
 * @property string $message
 * @property integer $code
 * @property string $file
 * @property integer $line
 * @property resource $trace
 * @property string $hash
 * @property integer $emailed
 */
class AuditError extends \yii\db\ActiveRecord
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
        return 'audit_error';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry_id', 'created', 'message'], 'required'],
            [['entry_id', 'code', 'line'], 'integer'],
            [['created'], 'safe'],
            [['message', 'trace'], 'string'],
            [['file'], 'string', 'max' => 512],
            [['hash'], 'string', 'max' => 32],
            [['emailed'], 'string', 'max' => 1],
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
            'message' => 'Message',
            'code' => 'Code',
            'file' => 'File',
            'line' => 'Line',
            'trace' => 'Trace',
            'hash' => 'Hash',
            'emailed' => 'Emailed',
        ];
    }
}
