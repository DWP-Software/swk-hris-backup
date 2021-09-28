<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "contract_salary".
 *
 * @property integer $id
 * @property integer $contract_id
 * @property integer $type
 * @property string $name
 * @property double $amount
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Contract $contract
 * @property User $createdBy
 * @property User $updatedBy
 */

class ContractSalary extends \yii\db\ActiveRecord
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
        return 'contract_salary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'type', 'name', 'amount'], 'required'],
            [['contract_id', 'type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contract::className(), 'targetAttribute' => ['contract_id' => 'id']],
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
            'id'          => 'ID',
            'contract_id' => 'Contract',
            'type'        => 'Type',
            'name'        => 'Name',
            'amount'      => 'Amount',
            'description' => 'Description',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
            'created_by'  => 'Created By',
            'updated_by'  => 'Updated By',
        ];
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


    public static function types($index = 'all')
    {
        $array = [
            '1' => 'Gaji Pokok',
            '2' => 'Tunjangan',
            '3' => 'Potongan',
        ];
        if ($index === 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function permanentTypes($index = 'all')
    {
        $array = [
            '1' => ['Gaji Pokok'],
            '2' => [
                'Tunjangan Jabatan',
                'Tunjangan Kendaraan',
                'Tunjangan HP', // /Pulsa',
                'Tunjangan Operasional',
                'Tunjangan Project',
                'Tunjangan Kehadiran',
            ],
            '3' => [
                'BPJS TK - JHT', // (GP X 2 %)',
                'BPJS TK - JP', // (GP X 1 %)',
                'BPJS KS', // (GP X 1 %)',
                'AKDHK', // (UMK X 0,24 %)',
                'PPh 21',
                'Simpanan Pokok',
                'Simpanan Wajib',
            ],
        ];
        if ($index === 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function conditionalTypes($index = 'all')
    {
        $array = [
            '1' => ['Gaji Pokok (Rapel)'],
            '2' => [
                'Lembur',
                'Tunjangan Shift Malam',
                'Tunjangan Makan',
                'Tunjangan Transport',
                'Bonus',
                'THR',
            ],
            '3' => [
                'Cash Deposit',
                'Adm Karyawan Baru',
                'Pinjaman Perusahaan',
                'Potongan NWNP',
            ],
        ];
        if ($index === 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }
}
