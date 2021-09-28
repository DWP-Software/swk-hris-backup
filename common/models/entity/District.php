<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "district".
 *
 * @property string $id
 * @property string $province_id
 * @property string $name
 * @property integer $area_type_id
 */
class District extends \yii\db\ActiveRecord
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
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'province_id', 'name', 'area_type_id'], 'required'],
            [['name'], 'string'],
            [['area_type_id'], 'integer'],
            [['id'], 'string', 'max' => 4],
            [['province_id'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_id' => 'Province',
            'name' => 'Name',
            'area_type_id' => 'Area Type',
        ];
    }
}
