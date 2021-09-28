<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "village".
 *
 * @property string $id
 * @property string $subdistrict_id
 * @property string $name
 * @property integer $area_type_id
 */
class Village extends \yii\db\ActiveRecord
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
        return 'village';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_type_id'], 'required'],
            [['name'], 'string'],
            [['area_type_id'], 'integer'],
            [['id'], 'string', 'max' => 10],
            [['subdistrict_id'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subdistrict_id' => 'Subdistrict',
            'name' => 'Name',
            'area_type_id' => 'Area Type',
        ];
    }
}
