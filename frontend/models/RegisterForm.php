<?php
namespace frontend\models;

use common\models\entity\Employee;
use Yii;
use yii\base\Model;
use common\models\entity\User;
use common\models\entity\Village;
use common\models\entity\District;
use common\models\entity\Subdistrict;
use common\models\entity\Province;

/**
 * Signup form
 */
class RegisterForm extends Model
{
    // user
    public $username;
    public $email;
    public $password;

    // employee    
    public $user_id;
    public $identity_number;
    public $registration_number;
    public $name;
    public $date_of_birth;
    public $place_of_birth;
    public $sex;
    public $religion;
    public $address_street;
    public $address_neighborhood;
    public $address_village_id;
    public $address_subdistrict_id;
    public $address_district_id;
    public $address_province_id;
    public $domicile_street;
    public $domicile_neighborhood;
    public $domicile_village_id;
    public $domicile_subdistrict_id;
    public $domicile_district_id;
    public $domicile_province_id;
    public $phone;
    public $education_level;
    public $family_number;
    public $mother_name;
    public $nationality;
    public $height;
    public $weight;
    public $marital_status;
    public $blood_type;
    
    public $file_photo;
    public $file_identity_card;
    public $file_family_certificate;
    public $file_transcript;
    public $file_certificate;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            // ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['user_id', 'sex', 'marital_status'], 'integer'],
            [['identity_number', 'phone', 'name', 'date_of_birth', 'place_of_birth', 'sex', 'religion', 'address_street', 'address_village_id', 'address_subdistrict_id', 'address_district_id', 'address_province_id', 'domicile_street', 'domicile_village_id', 'domicile_subdistrict_id', 'domicile_district_id', 'domicile_province_id', 'education_level', 'family_number', 'mother_name', 'nationality', 'height', 'weight', 'marital_status'], 'required'],
            [['date_of_birth'], 'safe'],
            [['height', 'weight'], 'number'],
            [['identity_number', 'registration_number', 'phone'], 'string', 'max' => 191],
            [['name', 'place_of_birth', 'religion', 'address_street', 'address_neighborhood', 'domicile_street', 'domicile_neighborhood', 'education_level', 'family_number', 'mother_name', 'nationality', 'blood_type'], 'string', 'max' => 255],
            [['address_village_id', 'domicile_village_id'], 'string', 'max' => 10],
            [['address_subdistrict_id', 'domicile_subdistrict_id'], 'string', 'max' => 6],
            [['address_district_id', 'domicile_district_id'], 'string', 'max' => 4],
            [['address_province_id', 'domicile_province_id'], 'string', 'max' => 2],
            [['domicile_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['domicile_district_id' => 'id']],
            [['domicile_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Province::className(), 'targetAttribute' => ['domicile_province_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['address_village_id'], 'exist', 'skipOnError' => true, 'targetClass' => Village::className(), 'targetAttribute' => ['address_village_id' => 'id']],
            [['address_subdistrict_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subdistrict::className(), 'targetAttribute' => ['address_subdistrict_id' => 'id']],
            [['address_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['address_district_id' => 'id']],
            [['address_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Province::className(), 'targetAttribute' => ['address_province_id' => 'id']],
            [['domicile_village_id'], 'exist', 'skipOnError' => true, 'targetClass' => Village::className(), 'targetAttribute' => ['domicile_village_id' => 'id']],
            [['domicile_subdistrict_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subdistrict::className(), 'targetAttribute' => ['domicile_subdistrict_id' => 'id']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($this->errors));
            return null;
        }
        
        $user           = new User();
        $user->username = time() . '_' . Yii::$app->security->generateRandomString();
        $user->email    = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        
        if ($user->save()) {
            $this->sendEmail($user);

            $auth     = \Yii::$app->authManager;
            $userRole = $auth->getRole('karyawan');
            $auth->assign($userRole, $user->id);

            $employee = new Employee();
            
            $employee->user_id                 = $user->id;
            $employee->identity_number         = $this->identity_number;
            $employee->registration_number     = $this->registration_number;
            $employee->name                    = $this->name;
            $employee->date_of_birth           = $this->date_of_birth;
            $employee->place_of_birth          = $this->place_of_birth;
            $employee->sex                     = $this->sex;
            $employee->religion                = $this->religion;
            $employee->address_street          = $this->address_street;
            $employee->address_neighborhood    = $this->address_neighborhood;
            $employee->address_village_id      = $this->address_village_id;
            $employee->address_subdistrict_id  = $this->address_subdistrict_id;
            $employee->address_district_id     = $this->address_district_id;
            $employee->address_province_id     = $this->address_province_id;
            $employee->domicile_street         = $this->domicile_street;
            $employee->domicile_neighborhood   = $this->domicile_neighborhood;
            $employee->domicile_village_id     = $this->domicile_village_id;
            $employee->domicile_subdistrict_id = $this->domicile_subdistrict_id;
            $employee->domicile_district_id    = $this->domicile_district_id;
            $employee->domicile_province_id    = $this->domicile_province_id;
            $employee->phone                   = $this->phone;
            $employee->education_level         = $this->education_level;
            $employee->family_number           = $this->family_number;
            $employee->mother_name             = $this->mother_name;
            $employee->nationality             = $this->nationality;
            $employee->height                  = $this->height;
            $employee->weight                  = $this->weight;
            $employee->marital_status          = $this->marital_status;
            $employee->blood_type              = $this->blood_type;

            if (!$employee->save()) Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($employee->errors));
            return $user;
            
        } else {
            Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($user->errors));
        }
        return false;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                      => 'ID',
            'user_id'                 => 'User ID',
            'identity_number'         => 'No. KTP',
            'registration_number'     => 'NRP',
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

            'file_photo'              => 'Pas Photo',
            'file_identity_card'      => 'KTP',
            'file_family_certificate' => 'KK',
            'file_transcript'         => 'Transkrip Nilai',
            'file_certificate'        => 'Ijazah',
        ];
    }
}
