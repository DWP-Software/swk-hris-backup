<?php

namespace api\controllers;

use yii\rest\Controller;

class CheckController extends Controller
{
    public function actionIndex()
    {
        return 1;
    }

    public function behaviors()
    {
        // remove rateLimiter which requires an authenticated user to work
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
} 
