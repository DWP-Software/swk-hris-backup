<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "client_agreement".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $document_number
 * @property string $started_at
 * @property string $ended_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Client $client
 * @property User $createdBy
 * @property User $updatedBy
 */
class ClientAgreement extends \yii\db\ActiveRecord
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
        return 'client_agreement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'document_number', 'started_at', 'ended_at'], 'required'],
            [['client_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['started_at', 'ended_at'], 'safe'],
            [['document_number'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
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
            'id'              => 'ID',
            'client_id'       => 'Client',
            'document_number' => 'Nomor MoU',
            'started_at'      => 'Mulai',
            'ended_at'        => 'Selesai',
            'created_at'      => 'Created At',
            'updated_at'      => 'Updated At',
            'created_by'      => 'Created By',
            'updated_by'      => 'Updated By',
        ];
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
}
