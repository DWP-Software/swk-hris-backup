<?php

namespace backend\controllers;

use Yii;
use common\models\entity\Payroll;
use common\models\entity\PayrollDetail;
use common\models\entity\Contract;
use common\models\entity\ContractSalary;
use common\models\entity\Employee;
use common\models\search\PayrollSearch;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\helpers\ArrayHelper;

/**
 * PayrollController implements the CRUD actions for Payroll model.
 */
class PayrollController extends Controller
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
     * Lists all Payroll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayrollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payroll model.
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
     * Creates a new Payroll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payroll();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach ($model->contract->contractSalaries as $contractSalary) {
                $payrollDetail             = new PayrollDetail();
                $payrollDetail->payroll_id = $model->id;
                $payrollDetail->type       = $contractSalary->type;
                $payrollDetail->name       = $contractSalary->name;
                $payrollDetail->amount     = $contractSalary->amount;
                if (!$payrollDetail->save()) Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($payrollDetail->errors));
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionGenerate($year = null, $month = null, $client_id = null, $employee_id = null)
    {
        $model = new Payroll();
        $contracts = null;

        $paid_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['payrolls'])->where([
            'and',
            ['payroll.year' => $year],
            ['payroll.month' => $month],
        ])->asArray()->all(), 'id');
        
        if ($year && $month && $client_id) {
            $contracts = Contract::find()
            ->joinWith(['employee'])
            ->joinWith(['contractPlacements'])
            ->where(['contract_placement.client_id' => $client_id])
            ->andWhere(['not in', 'employee.id', $paid_employees])
            ->andWhere(['<=', 'contract.started_at', date('Y-m-t', strtotime($year.'-'.$month.'-01'))])
            ->andWhere(['>=', 'contract.ended_at', date('Y-m-t', strtotime($year.'-'.$month.'-01'))])
            ->all();
        }
        if ($post = Yii::$app->request->post()) {
            // dd($post);
            foreach ($post as $key => $value) {
                $contract = Contract::findOne($key);
                if ($contract) {
                    if (Payroll::findOne([
                        'contract_id' => $key,
                        'month'       => $month,
                        'year'        => $year,
                    ]) !== null) {
                        Yii::$app->session->addFlash('error', 'Gaji '.$contract->employee->name.' pada bulan '.$month.'-'.$year.' telah dibayarkan sebelumnya');
                    } else {
                        $payroll              = new Payroll();
                        $payroll->employee_id = $contract->employee_id;
                        $payroll->contract_id = $contract->id;
                        $payroll->client_id   = $client_id;
                        $payroll->year        = $year;
                        $payroll->month       = $month;
                        if ($payroll->save()) {
                            foreach ($contract->contractSalaries as $salary) {
                                $payrollDetail             = new PayrollDetail();
                                $payrollDetail->payroll_id = $payroll->id;
                                $payrollDetail->type       = $salary->type;
                                $payrollDetail->name       = $salary->name;
                                $payrollDetail->amount     = $salary->amount;
                                $payrollDetail->save();
                            }
                        } else {
                            Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($payroll->errors));
                        }
                    }
                }
            }
            return $this->redirect(['index']);
        }
        return $this->render('generate', [
            'model'       => $model,
            'employee_id' => $employee_id,
            'client_id'   => $client_id,
            'month'       => $month,
            'year'        => $year,
            'contracts'   => $contracts,
        ]);
    }

    public function actionPay($contract_id, $year, $month, $client_id) 
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            $post          = Yii::$app->request->post();
            $transaction   = Yii::$app->db->beginTransaction();
            $rollback_flag = false;
            $message       = '';

            try {
                $contract = Contract::findOne($contract_id);
                $model              = new Payroll();
                $model->employee_id = $contract->employee_id;
                $model->contract_id = $contract_id;
                $model->client_id   = $client_id;
                $model->year        = $year;
                $model->month       = $month;
                if ($model->save()) {
                    foreach($post[$contract_id] as $key_type => $value_type) {
                        foreach($value_type as $key_subType => $value_subType) {
                            foreach($value_subType as $key_name => $value_name) {
                                $name = '';
                                if ($key_subType == 'permanent')    $name = ContractSalary::permanentTypes()[$key_type][$key_name];
                                if ($key_subType == 'conditional')  $name = ContractSalary::conditionalTypes()[$key_type][$key_name];

                                $payrollDetail             = new PayrollDetail();
                                $payrollDetail->payroll_id = $model->id;
                                $payrollDetail->type       = $key_type;
                                $payrollDetail->name       = $name;
                                $payrollDetail->amount     = (int) $value_name;
                                // return $payrollDetail;
                                if (!$payrollDetail->save()) {
                                    $rollback_flag = true;
                                    $message.= \yii\helpers\Json::encode($payrollDetail->errors);
                                }
                            }
                        }
                     }
                }
                if ($rollback_flag) {
                    $transaction->rollback();
                } else {
                    $transaction->commit();
                    return [
                        'status'  => 'success',
                        'message' => 'data tersimpan',
                    ];
                }
                return [
                    'status'  => 'danger',
                    'message' => 'data gagal tersimpan: '.$message.' --- '.\yii\helpers\Json::encode($model->errors),
                ];
            } catch (\Exception $exception) {
                $transaction->rollBack();
                throw $exception;
            }
        }
        return;
    }

    /**
     * Updates an existing Payroll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Payroll model.
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
     * Finds the Payroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payroll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payroll::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    public function generatePdf($title, $view, $params = [], $landscape = false) 
    {
        $pdf = new Pdf([
            'mode'         => Pdf::MODE_CORE,
            'format'       => 'A4',
            'orientation'  => $landscape ? 'L' : 'P',
            'marginTop'    => '10',
            'marginBottom' => '10',
            'marginLeft'   => '10',
            'marginRight'  => '10',
            'filename'     => $title,
            'options'      => ['title' => $title],
            'content'      => $this->renderPartial($view, $params),
            'methods'      => [
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
    


    public function actionPrint($id) 
    {
        $model = $this->findModel($id);

        if ($model) {
            $title      = 'Slip Gaji';
            $view       = 'print';
            $pre_params = [
                'model' => $model,
                'title' => $title,
                'view'  => $view,
            ];
            $params = array_merge($pre_params, ['params' => $pre_params]);
            return $this->generatePdf($title, $view, $params, 1);
        } else {
            Yii::$app->session->addFlash('error', 'No data available.');
            return $this->redirect(['view']);
        }
    }

}
