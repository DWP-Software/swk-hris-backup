<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "subdistrict".
 *
 * @property string $id
 * @property string $district_id
 * @property string $name
 */
class Subdistrict extends \yii\db\ActiveRecord
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
        return 'subdistrict';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'district_id', 'name'], 'required'],
            [['name'], 'string'],
            [['id'], 'string', 'max' => 6],
            [['district_id'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'district_id' => 'District',
            'name' => 'Name',
        ];
    }
}
