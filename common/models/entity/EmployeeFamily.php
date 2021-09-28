<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "employee_family".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $type
 * @property string $position
 * @property integer $sequence
 * @property string $name
 * @property integer $sex
 * @property string $date_of_birth
 * @property string $place_of_birth
 * @property integer $education_level
 * @property string $occupation
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Employee $employee
 * @property User $createdBy
 * @property User $updatedBy
 */
class EmployeeFamily extends \yii\db\ActiveRecord
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
        return 'employee_family';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'type', 'position', 'name', 'sex', 'date_of_birth', 'place_of_birth', 'education_level'], 'required'],
            [['employee_id', 'type', 'sequence', 'sex', 'education_level', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['date_of_birth'], 'safe'],
            [['position', 'name', 'place_of_birth', 'occupation'], 'string', 'max' => 255],
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
            'position' => 'Posisi',
            'sequence' => 'Anak ke',
            'name' => 'Nama',
            'sex' => 'Jenis Kelamin',
            'date_of_birth' => 'Tanggal Lahir',
            'place_of_birth' => 'Tempat Lahir',
            'education_level' => 'Pendidikan Terakhir',
            'occupation' => 'Occupation',
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

    public static function positions($index = 'all') {
        $array = [
            '1' => 'Suami',
            '2' => 'Istri',
            '3' => 'Anak',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }
}
