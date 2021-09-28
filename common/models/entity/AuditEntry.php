<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "audit_entry".
 *
 * @property integer $id
 * @property string $created
 * @property integer $user_id
 * @property double $duration
 * @property string $ip
 * @property string $request_method
 * @property integer $ajax
 * @property string $route
 * @property integer $memory_max
 */
class AuditEntry extends \yii\db\ActiveRecord
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
        return 'audit_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created'], 'required'],
            [['created'], 'safe'],
            [['user_id', 'ajax', 'memory_max'], 'integer'],
            [['duration'], 'number'],
            [['ip'], 'string', 'max' => 45],
            [['request_method'], 'string', 'max' => 16],
            [['route'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
            'user_id' => 'User',
            'duration' => 'Duration',
            'ip' => 'Ip',
            'request_method' => 'Request Method',
            'ajax' => 'Ajax',
            'route' => 'Route',
            'memory_max' => 'Memory Max',
        ];
    }
}
