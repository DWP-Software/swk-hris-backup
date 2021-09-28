<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $identity_number
 * @property string $registration_number
 * @property string $phone
 * @property string $name
 * @property string $date_of_birth
 * @property string $place_of_birth
 * @property integer $sex
 * @property string $religion
 * @property string $address_street
 * @property string $address_neighborhood
 * @property string $address_village_id
 * @property string $address_subdistrict_id
 * @property string $address_district_id
 * @property string $address_province_id
 * @property string $domicile_street
 * @property string $domicile_neighborhood
 * @property string $domicile_village_id
 * @property string $domicile_subdistrict_id
 * @property string $domicile_district_id
 * @property string $domicile_province_id
 * @property string $education_level
 * @property string $family_number
 * @property string $mother_name
 * @property string $nationality
 * @property double $height
 * @property double $weight
 * @property integer $marital_status
 * @property string $blood_type
 * @property string $bank_name
 * @property string $bank_account
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Contract[] $contracts
 * @property User $user
 * @property User $createdBy
 * @property User $updatedBy
 * @property Village $addressVillage
 * @property Subdistrict $addressSubdistrict
 * @property District $addressDistrict
 * @property Province $addressProvince
 * @property Village $domicileVillage
 * @property Subdistrict $domicileSubdistrict
 * @property District $domicileDistrict
 * @property Province $domicileProvince
 * @property EmployeeEducation[] $employeeEducations
 * @property EmployeeEmergency[] $employeeEmergencies
 * @property EmployeeExperience[] $employeeExperiences
 * @property EmployeeFamily[] $employeeFamilies
 * @property EmployeeFile[] $employeeFiles
 * @property PlacementPlan[] $placementPlans
 * @property Presence[] $presences
 */
class Employee extends \yii\db\ActiveRecord
{
    CONST PLACEMENT_ALL       = 99;
    CONST PLACEMENT_NONE      = -1;
    CONST PLACEMENT_REQUESTED = 0;
    CONST PLACEMENT_ACCEPTED  = 1;
    CONST PLACEMENT_REJECTED  = 2;

    CONST CONTRACT_NULL    = 999;
    CONST CONTRACT_ALL     = 99;
    CONST CONTRACT_OPENED  = 0;
    CONST CONTRACT_CLOSED  = 1;
    CONST CONTRACT_EXPIRED = 2;

    CONST CONTRACT_WAITING = 10;
    CONST CONTRACT_ENDING  = 11;

    public $file_photo;
    public $file_identity_card;
    public $file_family_certificate;
    public $file_transcript;
    public $file_certificate;

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
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sex', 'marital_status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['identity_number', 'phone', 'name', 'date_of_birth', 'place_of_birth', 'sex', 'religion', 'address_street', 'address_village_id', 'address_subdistrict_id', 'address_district_id', 'address_province_id', 'domicile_street', 'domicile_village_id', 'domicile_subdistrict_id', 'domicile_district_id', 'domicile_province_id', 'education_level', 'family_number', 'mother_name', 'nationality', 'height', 'weight', 'marital_status'], 'required'],
            [['identity_number'], 'unique'],
            [['registration_number'], 'unique'],
            [['date_of_birth'], 'safe'],
            [['height', 'weight'], 'number'],
            [['identity_number', 'registration_number', 'phone'], 'string', 'max' => 191],
            [['name', 'place_of_birth', 'religion', 'address_street', 'address_neighborhood', 'domicile_street', 'domicile_neighborhood', 'education_level', 'family_number', 'mother_name', 'nationality', 'blood_type', 'bank_name', 'bank_account'], 'string', 'max' => 255],
            [['address_village_id', 'domicile_village_id'], 'string', 'max' => 10],
            [['address_subdistrict_id', 'domicile_subdistrict_id'], 'string', 'max' => 6],
            [['address_district_id', 'domicile_district_id'], 'string', 'max' => 4],
            [['address_province_id', 'domicile_province_id'], 'string', 'max' => 2],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['address_village_id'], 'exist', 'skipOnError' => true, 'targetClass' => Village::className(), 'targetAttribute' => ['address_village_id' => 'id']],
            [['address_subdistrict_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subdistrict::className(), 'targetAttribute' => ['address_subdistrict_id' => 'id']],
            [['address_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['address_district_id' => 'id']],
            [['address_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Province::className(), 'targetAttribute' => ['address_province_id' => 'id']],
            [['domicile_village_id'], 'exist', 'skipOnError' => true, 'targetClass' => Village::className(), 'targetAttribute' => ['domicile_village_id' => 'id']],
            [['domicile_subdistrict_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subdistrict::className(), 'targetAttribute' => ['domicile_subdistrict_id' => 'id']],
            [['domicile_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['domicile_district_id' => 'id']],
            [['domicile_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Province::className(), 'targetAttribute' => ['domicile_province_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'identity_number'         => 'No. KTP',
            'registration_number'     => 'NRK',
            'phone'                   => 'Telp/HP',
            'name'                    => 'Nama Lengkap',
            'date_of_birth'           => 'Tanggal Lahir',
            'place_of_birth'          => 'Tempat Lahir',
            'sex'                     => 'Jenis Kelamin',
            'religion'                => 'Agama',
            'address_street'          => 'Jalan & No.Rumah',
            'address_neighborhood'    => 'RT/RW',
            'address_village_id'      => 'Desa/Kelurahan',
            'address_subdistrict_id'  => 'Kecamatan',
            'address_district_id'     => 'Kab/Kota',
            'address_province_id'     => 'Provinsi',
            'domicile_street'         => 'Jalan & No.Rumah',
            'domicile_neighborhood'   => 'RT/RW',
            'domicile_village_id'     => 'Desa/Kelurahan',
            'domicile_subdistrict_id' => 'Kecamatan',
            'domicile_district_id'    => 'Kab/Kota',
            'domicile_province_id'    => 'Provinsi',
            'education_level'         => 'Tingkat Pendidikan',
            'family_number'           => 'No. KK',
            'mother_name'             => 'Nama Ibu',
            'nationality'             => 'Kewarganegaraan',
            'height'                  => 'Tinggi Badan (cm)',
            'weight'                  => 'Berat Badan (kg)',
            'marital_status'          => 'Status Pernikahan',
            'blood_type'              => 'Golongan Darah',
            'bank_name'               => 'Nama Bank',
            'bank_account'            => 'No. Rekening',

            'file_photo'              => 'Pas Photo',
            'file_identity_card'      => 'KTP',
            'file_family_certificate' => 'KK',
            'file_transcript'         => 'Transkrip Nilai',
            'file_certificate'        => 'Ijazah',

            'addressText'  => 'Alamat KTP',
            'domicileText' => 'Alamat Domisili',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
    public function getAddressVillage()
    {
        return $this->hasOne(Village::className(), ['id' => 'address_village_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressSubdistrict()
    {
        return $this->hasOne(Subdistrict::className(), ['id' => 'address_subdistrict_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'address_district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'address_province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicileVillage()
    {
        return $this->hasOne(Village::className(), ['id' => 'domicile_village_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicileSubdistrict()
    {
        return $this->hasOne(Subdistrict::className(), ['id' => 'domicile_subdistrict_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicileDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'domicile_district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicileProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'domicile_province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeEducations()
    {
        return $this->hasMany(EmployeeEducation::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeEmergencies()
    {
        return $this->hasMany(EmployeeEmergency::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeExperiences() 
    { 
        return $this->hasMany(EmployeeExperience::className(), ['employee_id' => 'id']); 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFamilies()
    {
        return $this->hasMany(EmployeeFamily::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFiles()
    {
        return $this->hasMany(EmployeeFile::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrolls() 
    { 
        return $this->hasMany(Payroll::className(), ['employee_id' => 'id']); 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPlans()
    {
        return $this->hasMany(PlacementPlan::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresences()
    {
        return $this->hasMany(Presence::className(), ['employee_id' => 'id']);
    }

    public static function fileNameLabels($index = 'all') {
        $array = [
            'file_photo'              => 'Pas Photo',
            'file_identity_card'      => 'KTP',
            'file_family_certificate' => 'KK',
            'file_transcript'         => 'Transkrip Nilai',
            'file_certificate'        => 'Ijazah',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function sexes($index = 'all') {
        $array = [
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function religions($index = 'all') {
        $array = [
            '1' => 'Islam',
            '2' => 'Katolik',
            '3' => 'Kristen',
            '4' => 'Hindu',
            '5' => 'Buddha',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function nationalities($index = 'all') {
        $array = [
            '1' => 'WNI',
            '2' => 'WNA',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function educationLevels($index = 'all') {
        $array = [
            // '1' => 'Tidak Sekolah',
            // '2' => 'SD',
            '3' => 'SMP',
            '4' => 'SMA',
            '5' => 'D1',
            '6' => 'D2',
            '7' => 'D3',
            '8' => 'D4',
            '9' => 'S1',
            // '10' => 'S2',
            // '11' => 'S3',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function maritalStatuses($index = 'all') {
        $array = [
            '1' => 'Belum Menikah',
            '2' => 'Menikah',
            '3' => 'Cerai Mati',
            '4' => 'Cerai Hidup',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function bloodTypes($index = 'all') {
        $array = [
            '1' => 'A',
            '2' => 'B',
            '3' => 'AB',
            '4' => 'O',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public static function placementLabels($index = 'all') {
        $array = [
            self::PLACEMENT_ALL              => 'semua',
            self::PLACEMENT_NONE             => 'belum ditempatkan',
            self::PLACEMENT_REQUESTED        => 'menunggu',
            self::PLACEMENT_ACCEPTED         => 'diterima',
            self::PLACEMENT_REJECTED         => 'ditolak',

            self::CONTRACT_ALL     => 'semua',
            self::CONTRACT_OPENED  => 'menunggu ttd kontrak',
            self::CONTRACT_CLOSED  => 'dalam masa kontrak',
            self::CONTRACT_EXPIRED => 'kontrak habis',
        ];
        if ($index == 'all') return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public function getPlacementProgress() {
        $placement_status      = Employee::PLACEMENT_NONE;
        $placement_description = 'Terimakasih telah mengajukan diri untuk bergabung bersama SWK. Kami akan menghubungi anda jika ada posisi yang sesuai.';

        $placement = LatestContractPlacement::findOne(['employee_id' => $this->id]);
        if ($placement) {
            if (!$placement->file) {
                $placement_status = Employee::CONTRACT_OPENED;
                // if ($placement->client) $placement_description = 'Anda direncanakan untuk ditempatkan di ' . $placement->client->name . '. Silahkan lengkapi data dan print Kontrak untuk melanjutkan.';
                if ($placement->client) $placement_description = 'Anda direncanakan untuk ditempatkan. Silahkan lengkapi data dan print Kontrak untuk melanjutkan.';
            } elseif ($placement->contract_placement_ended_at >= date('Y-m-d')) {
                $placement_status = Employee::CONTRACT_CLOSED;
                if ($placement->contractPlacement && $placement->contractPlacement->client) $placement_description = 'Anda sedang ditempatkan di ' . $placement->contractPlacement->client->name;
            } else {
                $placement_status = Employee::CONTRACT_EXPIRED;
                $placement_description = 'Anda sedang tidak ditempatkan di manapun. Kami akan menghubungi anda jika ada posisi yang sesuai.';
            }
        }
        return [
            'status'      => $placement_status,
            'description' => $placement_description,
        ];
    }

    public function getAddressText()
    {
        $array = [];

        if ($this->address_street)          $array[] = $this->address_street;
        if ($this->address_neighborhood)    $array[] = 'RT'.$this->address_neighborhood;
        if ($this->addressVillage)          $array[] = $this->addressVillage->name;
        if ($this->addressSubdistrict)      $array[] = $this->addressSubdistrict->name;
        if ($this->addressDistrict)         $array[] = $this->addressDistrict->name;
        if ($this->addressProvince)         $array[] = $this->addressProvince->name;

        $imploded = implode(', ', $array);
        return $imploded;
    }

    public function getDomicileText()
    {
        $array = [];

        if ($this->domicile_street)         $array[] = $this->domicile_street;
        if ($this->domicile_neighborhood)   $array[] = 'RT'.$this->domicile_neighborhood;
        if ($this->domicileVillage)         $array[] = $this->domicileVillage->name;
        if ($this->domicileSubdistrict)     $array[] = $this->domicileSubdistrict->name;
        if ($this->domicileDistrict)        $array[] = $this->domicileDistrict->name;
        if ($this->domicileProvince)        $array[] = $this->domicileProvince->name;

        $imploded = implode(', ', $array);
        return $imploded;
    }

    public function getShortText() {
        return $this->name .' - ' . $this->registration_number;
    }

    public function getShortTextLatestContract() {
        return $this->name .' - ' . $this->registration_number . ' - ' . ($this->latestContractPlacement ? $this->latestContractPlacement->contractPlacement->client->name : '');
    }
    

    public function getLatestPlacementPlan()
    {
        return $this->hasOne(LatestPlacementPlan::className(), ['employee_id' => 'id']);
    }

    public function getLatestContract()
    {
        return $this->hasOne(LatestContract::className(), ['employee_id' => 'id']);
    }

    public function getLatestContractPlacement()
    {
        return $this->hasOne(LatestContractPlacement::className(), ['employee_id' => 'id']);
    }

    public function getActiveContract()
    {
        return $this->hasOne(ActiveContract::className(), ['employee_id' => 'id']);
    }

    public function getActiveContractPlacement()
    {
        return $this->hasOne(ActiveContractPlacement::className(), ['employee_id' => 'id']);
    }

    /* public function getActiveContract()
    {
        return $this->hasOne(LatestContract::className(), ['employee_id' => 'id'])->where(['>=', 'contract_ended_at', date('Y-m-d')])->andWhere(['is not', '_latest_contract.file', null]);
    } */



    public function getEmployeeEducationsFormal()
    {
        return $this->hasMany(EmployeeEducation::className(), ['employee_id' => 'id'])->where(['employee_education.type' => 1])->orderBy('year_start');
    }
    public function getEmployeeEducationsInformal()
    {
        return $this->hasMany(EmployeeEducation::className(), ['employee_id' => 'id'])->where(['employee_education.type' => 2])->orderBy('year_start');
    }
    
    public function getEmployeeFamiliesSelf()
    {
        return $this->hasMany(EmployeeFamily::className(), ['employee_id' => 'id'])->where(['employee_family.type' => 1])->orderBy('position, sequence');
    }
    public function getEmployeeFamiliesParent()
    {
        return $this->hasMany(EmployeeFamily::className(), ['employee_id' => 'id'])->where(['employee_family.type' => 2])->orderBy('position, sequence');
    }

    public function getCurrentClient($date = null)
    {
        if (!$date) $date = date('Y-m-d');
        $contract = Contract::find()
            ->where(['employee_id' => $this->id])
            ->andWhere(['<=', 'started_at', $date])
            ->andWhere(['>=', 'ended_at', $date])
            ->one();
        if ($contract) { 
            if (count($contract->contractPlacements) == 1) return $contract->contractPlacements[0]->client;
            if (count($contract->contractPlacements) > 1) {
                foreach ($contract->contractPlacements as $contractPlacement) {
                    if ($contractPlacement->started_at <= $date || $contractPlacement->ended_at >= $date) return $contractPlacement->client;
                }
            }
        }
        return null;
    }
}
