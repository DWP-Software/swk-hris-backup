<?php

namespace client\controllers;

use Yii;
use client\models\search\PlacementPlanSearch;
use common\models\entity\Employee;
use common\models\entity\EmployeeFile;
use common\models\entity\PlacementPlan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;

/**
 * PlacementController implements the CRUD actions for Placement model.
 */
class PlacementPlanController extends Controller
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
                    'accept' => ['POST'],
                    'reject' => ['POST'],
                    'cancel' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Placement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlacementPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Placement model.
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
     * Creates a new Placement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlacementPlan();
        $model->responded_at = time();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Placement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->responded_at = time();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Placement model.
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
     * Finds the Placement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Placement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlacementPlan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionAccept($id)
    {
        $model = $this->findModel($id);
        $model->responded_at = time();
        $model->response_type = 1;
        $model->save();
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);
        $model->responded_at = time();
        $model->response_type = 2;
        $model->save();
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        $model->responded_at = null;
        $model->response_type = null;
        $model->save();
        return $this->redirect(['view', 'id' => $id]);
    }


    public function actionDownload($id) {
        if (($employeeFile = EmployeeFile::findOne($id)) !== null) {
            if ($employeeFile->file) {
                $filepath  = Yii::getAlias('@uploads/' . $employeeFile->tableName() . '/' . $employeeFile->file);
                $array     = explode('.', $employeeFile->file);
                $extension = end($array);
                // $filename  = $employeeFile->name . '.' . $extension;
                $filename  = $employeeFile->employee->name . ' - ' . Employee::fileNameLabels($employeeFile->name) . '.' . $extension;
    
                if (file_exists($filepath)) return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');       
    }
}
