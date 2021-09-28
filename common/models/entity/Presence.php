<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "presence".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $contract_id
 * @property integer $client_id
 * @property string $date
 * @property string $status
 * @property integer $started_at
 * @property integer $ended_at
 * @property integer $is_late
 * @property integer $overtime_started_at
 * @property integer $overtime_ended_at
 * @property double $overtime_summary
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Employee $employee
 * @property Contract $contract
 * @property Client $client
 * @property User $createdBy
 * @property User $updatedBy
 */
class Presence extends \yii\db\ActiveRecord
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
        return 'presence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'date', 'status'], 'required'],
            [['employee_id', 'contract_id', 'client_id', 'started_at', 'ended_at', 'is_late', 'overtime_started_at', 'overtime_ended_at', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['date'], 'safe'],
            [['overtime_summary'], 'number'],
            [['status'], 'string', 'max' => 255],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contract::className(), 'targetAttribute' => ['contract_id' => 'id']],
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
            'id'                  => 'ID',
            'employee_id'         => 'Karyawan',
            'contract_id'         => 'Kontrak',
            'client_id'           => 'Mitra',
            'date'                => 'Tanggal',
            'status'              => 'Status',
            'started_at'          => 'Started At',
            'ended_at'            => 'Ended At',
            'is_late'             => 'Is Late',
            'overtime_started_at' => 'Overtime Started At',
            'overtime_ended_at'   => 'Overtime Ended At',
            'overtime_summary'    => 'Overtime Summary',
            'created_at'          => 'Created At',
            'updated_at'          => 'Updated At',
            'created_by'          => 'Created By',
            'updated_by'          => 'Updated By',
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
    public function getContract()
    {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
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
    

    public static function statuses($index = 'all') {
        $array = [
            '1'  => '1: Hadir',
            'T'  => 'T: Terlambat',
            'A'  => 'A: Alpha',
            'OL' => 'OL: Cuti',
            'S'  => 'S: Sakit',
            'AL' => 'AL: Cuti',
            'SL' => 'SL: Cuti khusus',
            'P'  => 'P: Izin',
            'O'  => 'O: Off',
        ];
        if ($index === 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function presenceTypes($index = 'all') {
        return self::statuses($index);
    }
}
