<?php

namespace api\controllers;

use Yii;
use \yii\rest\Controller;
 
class AuthController extends Controller
{
    public function behaviors()
    {
        // remove rateLimiter which requires an authenticated user to work
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    public function actionLogin()
    {
        $post       = Yii::$app->request->post();
        $email      = !empty($post['email']) ? $post['email'] : '';
        $password   = !empty($post['password']) ? $post['password'] : '';
        $response   = [];
        
        if (empty($email) || empty($password)) {
            $response = [
                'status'  => 'error',
                'message' => 'email & password can not be empty',
                'data'    => '',
            ];
        } else {
            $user = \common\models\User::findByEmail($email);
            if (!empty($user)) {
                if ($user->validatePassword($password)) {
                    // $user->generateAuthKey();
                    $user->save();
                    $response = [
                        'status'  => 'success',
                        'message' => 'logged in',
                        'data'    => [
                            'id'        => $user->id,
                            'email'     => $user->email,
                            'auth_key'  => $user->auth_key,
                        ],
                    ];
                } else {
                    $response = [
                        'status'  => 'error',
                        'message' => 'wrong password',
                        'data'    => '',
                    ];
                }
            } else {
                $response = [
                    'status'  => 'error',
                    'message' => 'user doesn\'t exists',
                    'data'    => '',
                ];
            }
        }
        return $response;
    }
}
