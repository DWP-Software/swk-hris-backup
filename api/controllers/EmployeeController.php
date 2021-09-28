<?php 

namespace api\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use mdm\admin\components\AccessControl;
use common\models\entity\Employee;
use common\models\entity\Payroll;
use common\models\entity\PayrollDetail;
use common\models\entity\ContractSalary;
use common\models\entity\Presence;
use kartik\mpdf\Pdf;

class EmployeeController extends ActiveController
{
    public $modelClass = 'api\models\Employee';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            // 'except' => ['index'],
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];
        
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;

        $behaviors['contentNegotiator']['formats']['application/xml'] = Response::FORMAT_JSON;
        /* $behaviors['access'] = [
            'class' => AccessControl::className(),
        ]; */
        return $behaviors;
    }

    public function actionProfile()
    {
        $array                      = Employee::find()->where(['id' => Yii::$app->user->identity->employee->id])->asArray()->one();
        $array['addressText']       = Yii::$app->user->identity->employee->addressText;
        $array['domicileText']      = Yii::$app->user->identity->employee->domicileText;
        $array['sexText']           = Employee::sexes($array['sex']);
        $array['maritalStatusText'] = Employee::maritalStatuses($array['marital_status']);
        $array['bloodTypeText']     = Employee::bloodTypes($array['blood_type']);
        return $array;
    }

    public function actionPresence()
    {
        $array = [];
        $lastRecord = Presence::find()->where(['employee_id' => Yii::$app->user->identity->employee->id])->orderBy('date DESC')->asArray()->one();
        if ($lastRecord) {
            $array['data'] = Presence::find()->where([
                'employee_id' => Yii::$app->user->identity->employee->id,
                'month(date)' => date('m', strtotime($lastRecord['date'])),
                'year(date)' => date('Y', strtotime($lastRecord['date'])),
            ])->orderBy('date DESC')->asArray()->all();
        }
        return $array;
    }

    public function actionSalary()
    {
        // $detail = Yii::$app->user->identity->employee->latestContract->latestSalary->payrollDetails;
        $object = Yii::$app->user->identity->employee->latestContract->contract->latestSalary;
        $array = $object ? iterator_to_array($object) : iterator_to_array(new Payroll());
        foreach (ContractSalary::types() as $type_key => $type_value) {
            $array[$type_value] = [];
            foreach (ContractSalary::permanentTypes() as $permanentType_key => $permanentType_value) {
                if ($type_key == $permanentType_key) {
                    foreach ($permanentType_value as $subType) {
                        $payrollDetail = $object ? PayrollDetail::findOne(['payroll_id' => $array['id'], 'name' => $subType]) : null;
                        $array[$type_value][$subType ] = $payrollDetail ? $payrollDetail->amount : null;
                    }
                }
            }
            foreach (ContractSalary::conditionalTypes() as $conditionalType_key => $conditionalType_value) {
                if ($type_key == $conditionalType_key) {
                    foreach ($conditionalType_value as $subType) {
                        $payrollDetail = $object ? PayrollDetail::findOne(['payroll_id' => $array['id'], 'name' => $subType]) : null;
                        $array[$type_value][$subType ] = $payrollDetail ? $payrollDetail->amount : null;
                    }
                }
            }
        }
        return $array;
    }
    


    public function generatePdf($title, $view, $params = [], $landscape = false) 
    {
        $pdf = new Pdf([
            'mode'         => Pdf::MODE_CORE,
            'destination'  => Pdf::DEST_DOWNLOAD,
            'format'       => 'A4',
            'orientation'  => $landscape ? 'L' : 'P',
            'marginTop'    => '10',
            'marginBottom' => '10',
            'marginLeft'   => '10',
            'marginRight'  => '10',
            'filename'     => $title.'.pdf',
            'options'      => ['title' => $title],
            'content'      => $this->renderPartial($view, $params),
            'methods'      => [
                // 'SetHeader' => \backend\helpers\ReportHelper::header($params),
                // 'SetFooter' => ['Print date: ' . date('d/m/Y') . '||Page {PAGENO} of {nbpg}'],
            ],
            'cssInline' => '
                .table-report {vertical-align:top; font-family:"Quivira" }
                .table-report-noborder td { border:none; padding:0px 0px; vertical-align:top }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .table { width:100%; }
                th { text-align:left; }
            ',
        ]);
        return $pdf->render();
    }
    


    public function actionPrint() 
    {
        $model = Payroll::find()->joinWith(['contract'])->where(['employee_id' => Yii::$app->user->identity->employee->id])->orderBy('id DESC')->one();

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
            return [
                'status' => 'error',
                'message' => 'File not found',
            ];
        }
    }

}