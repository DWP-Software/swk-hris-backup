<?php

namespace backend\controllers;

use Yii;
use common\models\entity\User;
use common\models\search\UserSearch;
use common\models\entity\AuthAssignment;
use common\models\entity\PicClient;
use common\models\search\UserSearchClient;
use common\models\search\UserSearchEmployee;
use common\models\search\UserSearchInternal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\helpers\ArrayHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserInternalController extends Controller
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
                    'impersonate' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearchInternal();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model           = new User();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->username = Yii::$app->security->generateRandomString() . '_' . time();
            if ($model->save()) {
                foreach ($model->role as $selectedRole) {
                    $auth       = Yii::$app->authManager;
                    $userRole   = $auth->getRole($selectedRole);
                    $auth->assign($userRole, $model->id);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }            
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (($authAssignment = AuthAssignment::findAll(['user_id' => $id])) !== null) {
            $model->role = ArrayHelper::map($authAssignment, 'item_name', 'item_name');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->password) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
            }
            if ($model->save()) {
                AuthAssignment::deleteAll(['user_id' => $id]);
                foreach ($model->role as $selectedRole) {
                    $auth       = Yii::$app->authManager;
                    $userRole   = $auth->getRole($selectedRole);
                    $auth->assign($userRole, $model->id);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        try {
            if ($model->delete()) AuthAssignment::deleteAll(['user_id' => $id]);
        } catch (IntegrityException $e) {
            $model->status = User::STATUS_DELETED;
            $model->save();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImpersonate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->login($model, 0)) {
            Yii::$app->session->addFlash('success', 'You are impersonating <b>'.$model->email.'</b>');
            return $this->goHome();
        }
        Yii::$app->session->addFlash('error', 'Failed to impersonate <b>'.$model->email.'</b>');
        return $this->redirect(['view', 'id' => $model->id]);
    }


    
    public function actionPicClientFormPresence($user_id, $id = null) 
    {
        $model          = new PicClient();
        $model->user_id = $user_id;
        $model->role    = 'Kehadiran';
        
        if ($id) $model = PicClient::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $user_id]);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-pic-client', [
                'model' => $model
            ]);
        }
        return d($model->errors);
    }
    public function actionPicClientFormFinance($user_id, $id = null) 
    {
        $model          = new PicClient();
        $model->user_id = $user_id;
        $model->role    = 'Keuangan';
        
        if ($id) $model = PicClient::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $user_id]);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-pic-client', [
                'model' => $model
            ]);
        }
        return d($model->errors);
    }
    
    public function actionPicClientDelete($id) 
    {
        $model = PicClient::findOne($id);

        try {
            $model->delete();
        } catch (IntegrityException $e) {
            Yii::$app->session->addFlash('error',"Integrity Constraint Violation. This data can not be deleted due to the relation.");
        }
        return $this->redirect(['view', 'id' => $model->user_id]);
    }
}
