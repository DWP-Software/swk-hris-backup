<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "employee_education".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $type
 * @property integer $level
 * @property string $name
 * @property string $place
 * @property integer $year_start
 * @property integer $year_end
 * @property string $major
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Employee $employee
 * @property User $createdBy
 * @property User $updatedBy
 */
class EmployeeEducation extends \yii\db\ActiveRecord
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
        return 'employee_education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'type', 'name', 'place', 'year_start'], 'required'],
            [['employee_id', 'type', 'level', 'year_start', 'year_end', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['remark'], 'string'],
            [['name', 'place', 'major'], 'string', 'max' => 255],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
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
            'id' => 'ID',
            'employee_id' => 'Employee',
            'type' => 'Type',
            'level' => 'Tingkat',
            'name' => 'Nama Instansi',
            'place' => 'Tempat',
            'year_start' => 'Tahun Masuk',
            'year_end' => 'Tahun Lulus',
            'major' => 'Jurusan',
            'remark' => 'Keterangan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
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
