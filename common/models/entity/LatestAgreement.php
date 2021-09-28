<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "_latest_agreement".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $started_at
 * @property string $ended_at
 */
class LatestAgreement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '_latest_agreement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id'], 'integer'],
            [['client_id', 'started_at', 'ended_at'], 'required'],
            [['started_at', 'ended_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client',
            'started_at' => 'Started At',
            'ended_at' => 'Ended At',
        ];
    }
}
