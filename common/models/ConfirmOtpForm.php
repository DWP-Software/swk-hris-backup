<?php
namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\AuthAssignment;

/**
 * Login form
 */
class ConfirmOtpForm extends Model
{
    public $phone;
    public $otp;
    public $rememberMe = true;

    // private $_user = false;
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // phone and otp are both required
            [['phone', 'otp'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // otp is validated by validateOtp()
            ['otp', 'validateOtp'],
        ];
    }

    /**
     * Validates the otp.
     * This method serves as the inline validation for otp.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateOtp($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validateOtp($this->otp)) {
                $this->addError($attribute, 'Incorrect phone or OTP.');
            }
        }
    }

    /**
     * Logs in a user using the provided phone and otp.
     *
     * @return bool whether the user is logged in successfully
     */
    public function confirm()
    {
        if ($this->validate()){
            $user = $this->getUser();
            if ($user) {
                $user->otp = '';            //Remove otp before logging in.
                $user->otp_expire = '';
                if (!$user->phone_confirmed_at) $user->phone_confirmed_at = time();
                $user->save(false);
                return true;
                //  return Yii::$app->user->login($user ,$this->rememberMe ? 3600*24*30 : 0);
            }
        }
        return false;   
    }

    /**
     * Finds user by [[phone]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByPhone($this->phone);
        }

        return $this->_user;
    }
}
