<?php

namespace backend\controllers;

use Yii;
use common\models\entity\Client;
use common\models\entity\ClientAgreement;
use common\models\entity\ClientUser;
use common\models\entity\User;
use common\models\search\ClientSearch;
use common\models\search\ClientSearchAgreementEnding;
use common\models\search\ClientSearchAgreementExpired;
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

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'semua',
        ]);
    }
    public function actionIndexAgreementEnding()
    {
        $searchModel = new ClientSearchAgreementEnding();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'MoU segera berakhir',
        ]);
    }
    public function actionIndexAgreementExpired()
    {
        $searchModel = new ClientSearchAgreementExpired;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'MoU kadaluarsa',
        ]);
    }

    /**
     * Displays a single Client model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = 'uploaded_file_logo';
            $uploadedFile = UploadedFile::getInstance($model, $file);
            if ($uploadedFile) {
                $filename = $model->id .'.'. $uploadedFile->extension;
                $uploadedFile->saveAs(Yii::getAlias('@uploads/' . $model->tableName() . '/' . $filename));
                $model->file_logo = $filename;
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = 'uploaded_file_logo';
            $uploadedFile = UploadedFile::getInstance($model, $file);
            if ($uploadedFile) {
                $filename = $model->id .'.'. $uploadedFile->extension;
                $uploadedFile->saveAs(Yii::getAlias('@uploads/' . $model->tableName() . '/' . $filename));
                $model->file_logo = $filename;
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        } catch (IntegrityException $e) {
            throw new \yii\web\HttpException(500,"Integrity Constraint Violation. This data can not be deleted due to the relation.", 405);
        }
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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



    public function actionUserForm($client_id, $id = null) 
    {
        $model            = new ClientUser();
        $model->client_id = $client_id;

        if ($id) {
            $model = ClientUser::findOne($id);
            $model->email = $model->user->email;
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $user = new User();
            if ($id) $user  = User::findOne($model->user_id);
            $user->username = time() . '_' . Yii::$app->security->generateRandomString();
            $user->email    = $model->email;
            if ($model->password) {
                $user->setPassword($model->password);
                $user->generateAuthKey();
            }
            if ($user->isNewRecord) $user->generateEmailVerificationToken();
            
            if ($user->save()) {
                $auth     = \Yii::$app->authManager;
                $userRole = $auth->getRole('mitra');
                try {
                    $auth->assign($userRole, $user->id);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $model->user_id = $user->id;
                if (!$model->save()) Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($model->errors));
                return $this->redirect(['view', 'id' => $client_id]);
            } else {
                Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($user->errors));   
            }
            return $this->redirect(['view', 'id' => $client_id]);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-user', [
                'model' => $model
            ]);
        }
        return;
    }
    
    public function actionAgreementForm($client_id, $id = null) 
    {
        $model            = new ClientAgreement();
        $model->client_id = $client_id;
        
        if ($id) $model = ClientAgreement::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $client_id]);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-agreement', [
                'model' => $model
            ]);
        }
        return;
    }
    
    public function actionAgreementDelete($id) 
    {
        $model = ClientAgreement::findOne($id);

        try {
            $model->delete();
        } catch (IntegrityException $e) {
            Yii::$app->session->addFlash('error',"Integrity Constraint Violation. This data can not be deleted due to the relation.");
        }
        return $this->redirect(['view', 'id' => $model->client_id]);
    }
}
