<?php

namespace backend\controllers;

use Yii;
use common\models\entity\User;
use common\models\search\UserSearch;
use common\models\entity\AuthAssignment;
use common\models\search\UserSearchClient;
use common\models\search\UserSearchEmployee;
use common\models\search\UserSearchInternal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserClientController extends Controller
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
        $searchModel = new UserSearchClient();
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
                $auth       = Yii::$app->authManager;
                $userRole   = $auth->getRole($model->role);
                $auth->assign($userRole, $model->id);
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

        if (($authAssignment = AuthAssignment::findOne(['user_id' => $id])) !== null) {
            $model->role = $authAssignment->item_name;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->password) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
            }
            if ($model->save()) {
                AuthAssignment::deleteAll(['user_id' => $id]);
                $auth       = Yii::$app->authManager;
                $userRole   = $auth->getRole($model->role);
                $auth->assign($userRole, $model->id);
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
}
