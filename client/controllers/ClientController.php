<?php

namespace client\controllers;

use Yii;
use common\models\entity\Client;
use common\models\entity\ClientUser;
use common\models\entity\User;
use common\models\search\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionDownload($id) {
        if (($client = Client::findOne($id)) !== null) {
            if ($client->file_logo) {
                $filepath  = Yii::getAlias('@uploads/' . $client->tableName() . '/' . $client->file_logo);
                $array     = explode('.', $client->file_logo);
                $extension = end($array);
                $filename  = $client->file_logo . '.' . $extension;
    
                if (file_exists($filepath)) return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
            }
        }
        throw new NotFoundHttpException('The requested file does not exist.');       
    }
}
