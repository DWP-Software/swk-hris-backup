<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "contract".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $employee_type_id
 * @property string $started_at
 * @property string $ended_at
 * @property string $resigned_at
 * @property string $file
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Employee $employee
 * @property EmployeeType $employeeType
 * @property User $createdBy
 * @property User $updatedBy
 * @property ContractPlacement[] $contractPlacements
 * @property ContractSalary[] $contractSalaries
 * @property Payroll[] $payrolls
 * @property Presence[] $presences
 */
class Contract extends \yii\db\ActiveRecord
{
    CONST CONTRACT_OPENED  = 1;
    CONST CONTRACT_CLOSED  = 2;
    CONST CONTRACT_EXPIRED = 3;

    public $uploaded_file;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
            \bedezign\yii2\audit\AuditTrailBehavior::className(),
            /* 'autonumber' => [
                'class'     => 'mdm\autonumber\Behavior',
                'attribute' => 'contract_number',
                'group'     => date('Y'),
                'value'     => '?/SWK-HRD/'.monthsRoman(date('m')).'/'.date('Y'),
                'digit'     => 4,
            ], */
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'employee_type_id', 'started_at'], 'required'],
            [['employee_id', 'employee_type_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['started_at', 'ended_at', 'resigned_at'], 'safe'],
            [['file'], 'string'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['employee_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeType::className(), 'targetAttribute' => ['employee_type_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['ended_at'], 'required', 'when' => function($model) {
                return $model->employeeType->name != 'DW';
            }, 'enableClientValidation' => false],
            [['pasal_3_2', 'pasal_3_3', 'payment_date'], 'integer'],
            [['signer_name', 'signer_position', 'signer_address'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'contract_number'  => 'Nomor Kontrak',
            'employee_id'      => 'Karyawan',
            'employee_type_id' => 'Jenis Kontrak',
            'started_at'       => 'Mulai',
            'ended_at'         => 'Selesai',
            'resigned_at'      => 'Resign',
            'file'             => 'File',
            'created_at'       => 'Created At',
            'updated_at'       => 'Updated At',
            'created_by'       => 'Created By',
            'updated_by'       => 'Updated By',
            'pasal_3_2'        => 'Pasal 3 ayat 2',
            'pasal_3_3'        => 'Jml hari kerja per bulan',
            'payment_date'     => 'Tanggal penggajian',
            'signer_name'      => 'Nama Penandatangan',
            'signer_position'  => 'Jabatan Penandatangan',
            'signer_address'   => 'Alamat Penandatangan',
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
    public function getEmployeeType()
    {
        return $this->hasOne(EmployeeType::className(), ['id' => 'employee_type_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractPlacements()
    {
        return $this->hasMany(ContractPlacement::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractSalaries()
    {
        return $this->hasMany(ContractSalary::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrolls()
    {
        return $this->hasMany(Payroll::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresences()
    {
        return $this->hasMany(Presence::className(), ['contract_id' => 'id']);
    }

    public function getShortText()
    {
        return $this->id.' - '.$this->employee->name.' - '.$this->employee->registration_number;
    }

    public function getDuration()
    {
        // $datetime1 = new \DateTime($this->started_at);
        // $datetime2 = new \DateTime($this->ended_at);
        // $interval = $datetime1->diff($datetime2);
        // return round($interval->format('%a')/365) . ' tahun';
        // $duration = Yii::$app->formatter->asDuration($interval);
        $toBeReplaceds = [
            'second' => 'detik',
            'minute' => 'menit',
            'hour'   => 'jam',
            'day'    => 'hari',
            'month'  => 'bulan',
            'year'   => 'tahun',
        ];
        $duration = Yii::$app->formatter->asDuration($this->started_at.'T00:00:00Z/'.$this->ended_at.'T00:00:00Z');
        foreach ($toBeReplaceds as $key => $value) {
            $duration = str_replace($key.'s', $value, $duration);
            $duration = str_replace($key, $value, $duration);
        }
        return $duration;
    }

    public static function contractPhases($index = 'all') {
        $array = [
            Contract::CONTRACT_OPENED  => 'Menunggu Ttd',
            Contract::CONTRACT_CLOSED  => 'Kontrak Efektif',
            Contract::CONTRACT_EXPIRED => 'Kontrak Expire',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public function getContractPhaseText() {
        $phase = Contract::CONTRACT_OPENED;
        if ($this->file) {
            $phase = Contract::CONTRACT_CLOSED;
            if ($this->ended_at < date('Y-m-d')) $phase = Contract::CONTRACT_EXPIRED;
        }
        return self::contractPhases($phase);
    }

    public function getBaseSalary()
    {
        return ContractSalary::find()->where(['contract_id' => $this->id])->andWhere(['type' => '1'])->sum('amount');
    }

    public function getNetSalary()
    {
        return 
        ContractSalary::find()->where(['contract_id' => $this->id])->andWhere(['or', ['type' => '1'], ['type' => '2']])->sum('amount')
        - ContractSalary::find()->where(['contract_id' => $this->id])->andWhere(['type' => '3'])->sum('amount');
    }

    public function getLatestSalary()
    {
        return $this->hasOne(Payroll::className(), ['contract_id' => 'id'])->orderBy('payroll.id DESC');
    }

    public function getCurrentPlacement($date = null)
    {
        if (!$this->contractPlacements) return null;
        if (!$date) $date = date('Y-m-d');
        if (count($this->contractPlacements) == 1) return $this->contractPlacements[0];
        if (count($this->contractPlacements) > 1) {
            foreach ($this->contractPlacements as $contractPlacement) {
                if ($contractPlacement->started_at <= $date || $contractPlacement->ended_at >= $date) return $contractPlacement;
            }
        }
        return null;
    }
}
