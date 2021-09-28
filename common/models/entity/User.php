<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property string $verification_token
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Client[] $clients
 * @property Client[] $clients0
 * @property ClientAgreement[] $clientAgreements
 * @property ClientAgreement[] $clientAgreements0
 * @property ClientUser[] $clientUsers
 * @property ClientUser[] $clientUsers0
 * @property ClientUser[] $clientUsers1
 * @property Contract[] $contracts
 * @property Contract[] $contracts0
 * @property ContractPlacement[] $contractPlacements
 * @property ContractPlacement[] $contractPlacements0
 * @property ContractSalary[] $contractSalaries
 * @property ContractSalary[] $contractSalaries0
 * @property Employee[] $employees
 * @property Employee[] $employees0
 * @property Employee[] $employees1
 * @property EmployeeEducation[] $employeeEducations
 * @property EmployeeEducation[] $employeeEducations0
 * @property EmployeeEmergency[] $employeeEmergencies
 * @property EmployeeEmergency[] $employeeEmergencies0
 * @property EmployeeFamily[] $employeeFamilies
 * @property EmployeeFamily[] $employeeFamilies0
 * @property EmployeeFile[] $employeeFiles
 * @property EmployeeFile[] $employeeFiles0
 * @property EmployeeType[] $employeeTypes
 * @property EmployeeType[] $employeeTypes0
 * @property Payroll[] $payrolls
 * @property Payroll[] $payrolls0
 * @property PayrollDetail[] $payrollDetails
 * @property PayrollDetail[] $payrollDetails0
 * @property PlacementPlan[] $placementPlans
 * @property PlacementPlan[] $placementPlans0
 * @property Presence[] $presences
 * @property Presence[] $presences0
 */
class User extends \common\models\User
{
    public $password;
    public $role;

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
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],

            [['password'], 'safe',],
            [['password'], 'required', 'on' => 'create',],

            [['role'], 'safe',],

            // [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => 'ID',
            'name'                 => 'Name',
            'username'             => 'Username',
            'auth_key'             => 'Auth Key',
            'password_hash'        => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email'                => 'Email',
            'status'               => 'Status',
            'verification_token'   => 'Verification Token',
            'created_at'           => 'Created At',
            'updated_at'           => 'Updated At',
            'created_by'           => 'Created By',
            'updated_by'           => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }
    public function getActiveRole()
    {
        return $this->authAssignments ? $this->authAssignments[0]->item_name : '';
    }

    public static function statuses($index = null) {
        $array = [
            self::STATUS_ACTIVE   => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DELETED => 'Deleted',
        ];
        if ($index === null) return $array;
        if (isset($array[$index])) return $array[$index];
        return null;
    }

    public function getRoles()
    {
        $array = [];
        $authAssignments = AuthAssignment::findAll(['user_id' => $this->id]);
        foreach ($authAssignments as $authAssignment) {
            $array[] = $authAssignment['item_name'];
        }
        $return = implode(', ', $array);
        return $return;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients0()
    {
        return $this->hasMany(Client::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientAgreements()
    {
        return $this->hasMany(ClientAgreement::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientAgreements0()
    {
        return $this->hasMany(ClientAgreement::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientUsers()
    {
        return $this->hasMany(ClientUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientUsers0()
    {
        return $this->hasMany(ClientUser::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientUsers1()
    {
        return $this->hasMany(ClientUser::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts0()
    {
        return $this->hasMany(Contract::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractPlacements()
    {
        return $this->hasMany(ContractPlacement::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractPlacements0()
    {
        return $this->hasMany(ContractPlacement::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractSalaries()
    {
        return $this->hasMany(ContractSalary::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractSalaries0()
    {
        return $this->hasMany(ContractSalary::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees0()
    {
        return $this->hasMany(Employee::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees1()
    {
        return $this->hasMany(Employee::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeEducations()
    {
        return $this->hasMany(EmployeeEducation::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeEducations0()
    {
        return $this->hasMany(EmployeeEducation::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeEmergencies()
    {
        return $this->hasMany(EmployeeEmergency::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeEmergencies0()
    {
        return $this->hasMany(EmployeeEmergency::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFamilies()
    {
        return $this->hasMany(EmployeeFamily::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFamilies0()
    {
        return $this->hasMany(EmployeeFamily::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFiles()
    {
        return $this->hasMany(EmployeeFile::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeFiles0()
    {
        return $this->hasMany(EmployeeFile::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeTypes()
    {
        return $this->hasMany(EmployeeType::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeTypes0()
    {
        return $this->hasMany(EmployeeType::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrolls()
    {
        return $this->hasMany(Payroll::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrolls0()
    {
        return $this->hasMany(Payroll::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollDetails()
    {
        return $this->hasMany(PayrollDetail::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollDetails0()
    {
        return $this->hasMany(PayrollDetail::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPlans()
    {
        return $this->hasMany(PlacementPlan::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPlans0()
    {
        return $this->hasMany(PlacementPlan::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresences()
    {
        return $this->hasMany(Presence::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresences0()
    {
        return $this->hasMany(Presence::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicClients()
    {
        return $this->hasMany(PicClient::className(), ['user_id' => 'id']);
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['user_id' => 'id']);
    }

    public function getClientUser()
    {
        return $this->hasOne(ClientUser::className(), ['user_id' => 'id']);
    }
}
