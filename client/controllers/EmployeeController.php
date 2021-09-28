<?php

namespace client\controllers;

use Yii;
use common\models\entity\Employee;
use common\models\entity\EmployeeFile;
use client\models\search\EmployeeSearch;
use client\models\search\EmployeeSearchContractWaiting;
use client\models\search\EmployeeSearchContractOpened;
use client\models\search\EmployeeSearchContractClosed;
use client\models\search\EmployeeSearchContractEnding;
use client\models\search\EmployeeSearchContractExpired;
use common\models\entity\Contract;
use common\models\entity\ContractSalary;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\web\UploadedFile;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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
                    'delete'           => ['POST'],
                    'placement-delete' => ['POST'],
                    'contract-delete'  => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'semua',
        ]);
    }
    public function actionIndexContractWaiting()
    {
        $searchModel = new EmployeeSearchContractWaiting();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'menunggu pembuatan kontrak',
        ]);
    }
    public function actionIndexContractOpened()
    {
        $searchModel = new EmployeeSearchContractOpened();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'menunggu ttd kontrak',
        ]);
    }
    public function actionIndexContractClosed()
    {
        $searchModel = new EmployeeSearchContractClosed();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'kontrak efektif',
        ]);
    }
    public function actionIndexContractEnding()
    {
        $searchModel = new EmployeeSearchContractEnding();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'kontrak segera berakhir',
        ]);
    }
    public function actionIndexContractExpired()
    {
        $searchModel = new EmployeeSearchContractExpired();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'kontrak kadaluarsa',
        ]);
    }



    /**
     * Displays a single Employee model.
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
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }







    
    public function actionContractView($contract_id)
    {
        $model       = Contract::findOne($contract_id);
        $modelSalary = new ContractSalary();
        $modelSalary->contract_id = $contract_id;

        if ($modelSalary->load(Yii::$app->request->post())) {
            $modelSalary->save();
            return $this->redirect(['contract-view', 'contract_id' => $contract_id]);
        }

        return $this->render('contract-view', [
            'model'       => $model,
            'modelSalary' => $modelSalary,
        ]);
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


    public function generatePdf($title, $view, $params = [], $landscape = false) {
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => 'A4',
            'orientation' => $landscape ? 'L' : 'P',
            'marginTop' => '20',
            'marginBottom' => '20',
            'marginLeft' => '20',
            'marginRight' => '20',
            'filename' => $title,
            'options' => ['title' => $title],
            'content' => $this->renderPartial($view, $params),
            'methods' => [
                // 'SetHeader' => \backend\helpers\ReportHelper::header($params),
                // 'SetFooter' => ['Print date: ' . date('d/m/Y') . '||Page {PAGENO} of {nbpg}'],
            ],
            'cssInline' => '
                .table-report {vertical-align:top; font-family:"Quivira" }
                .table-report-noborder td { border:none; padding:0px 0px; vertical-align:top }
            ',
        ]);
        return $pdf->render();
    }

    public function actionPrintResume($id) {
        $model = Contract::findOne($id);

        $title      = 'DATA KARYAWAN BARU';
        $view       = 'resume';
        $pre_params = [
            'model' => $model,
            'title' => $title,
            'view'  => $view,
        ];
        $params = array_merge($pre_params, ['params' => $pre_params]);
        return $this->generatePdf($title, $view, $params, 0);
    }
}
