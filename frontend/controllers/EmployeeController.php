<?php

namespace frontend\controllers;

use Yii;
use common\models\entity\Employee;
use common\models\entity\EmployeeEducation;
use common\models\entity\EmployeeEmergency;
use common\models\entity\EmployeeFamily;
use common\models\entity\EmployeeFile;
use common\models\entity\ContractPlacement;
use common\models\entity\Contract;
use common\models\entity\EmployeeExperience;
use common\models\entity\Payroll;
use common\models\search\EmployeeSearch;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    public function actionIndex()
    {        
        return $this->redirect(['view']);
    }

    public function actionView()
    {
        if (Yii::$app->user->identity->employee) {
            $model = Yii::$app->user->identity->employee;
            
            return $this->render('view', [
                'model' => $model,
            ]);

        } else {
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }
    
    public function actionActiveContract()
    {
        if (Yii::$app->user->identity->employee) {
            $model = Yii::$app->user->identity->employee;

            if ($model->activeContract) {
                return $this->render('active-contract', [
                    'model' => $model->activeContract,
                ]);
            } 
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }
    
    public function actionUpdate()
    {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $files = [
                'file_photo',
                'file_identity_card',
                'file_family_certificate',
                'file_transcript',
                'file_certificate',
            ];
            foreach ($files as $file) {
                $uploadedFile = UploadedFile::getInstance($model, $file);
                if ($uploadedFile) {
                    if (($employeeFile = EmployeeFile::findOne(['employee_id' => $model->id, 'name' => $file])) === null) $employeeFile = new EmployeeFile();
                    $employeeFile->employee_id = $model->id;
                    $employeeFile->name        = $file;
                    if ($employeeFile->save()) {
                        $filename = $employeeFile->employee_id .'.'. $employeeFile->id .'.'. $employeeFile->name .'.'. $uploadedFile->extension;
                        $uploadedFile->saveAs(Yii::getAlias('@uploads/employee_file/' . $filename));
                        $employeeFile->file = $filename;
                        $employeeFile->save();
                    }
                }
            }
            return $this->redirect(['view']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
            'mode'         => Pdf::MODE_CORE,
            'format'       => 'A4',
            'orientation'  => $landscape ? 'L' : 'P',
            'marginTop'    => '20',
            'marginBottom' => '20',
            'marginLeft'   => '25',
            'marginRight'  => '20',
            'filename'     => $title. '.pdf',
            'options'      => ['title' => $title],
            'content'      => $this->renderPartial($view, $params),
            'methods'      => [
                // 'SetHeader' => \backend\helpers\ReportHelper::header($params),
                // 'SetFooter' => ['Print date: ' . date('d/m/Y') . '||Page {PAGENO} of {nbpg}'],
            ],
            'cssInline' => '
                .table-report-noborder td { border:none; padding:0px 0px; vertical-align:top }
            ',
        ]);
        return $pdf->render();
    }

    public function actionPrintContract() {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $lastOpenedContract = Contract::find()
        ->where(['employee_id' => $model->id, 'file' => null])
        ->orderBy('contract.id DESC')
        ->one();

        if ($lastOpenedContract) {
            $title      = 'KONTRAK';
            $view       = 'contract';
            $pre_params = [
                'model' => $lastOpenedContract,
                'title' => $title,
                'view'  => $view,
            ];
            $params = array_merge($pre_params, ['params' => $pre_params]);
            return $this->generatePdf($title, $view, $params, 0);
        } else {
            Yii::$app->session->addFlash('error', 'No contract available.');
            return $this->redirect(['view']);
        }
    }

    public function actionPrintStatement() {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $title      = 'SURAT PERNYATAAN';
        $view       = 'statement';
        $pre_params = [
            'model' => $model,
            'title' => $title,
            'view'  => $view,
        ];
        $params = array_merge($pre_params, ['params' => $pre_params]);
        return $this->generatePdf($title, $view, $params, 0);
    }

    public function actionFormEmergency($id = null) {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $modelEmergency              = new EmployeeEmergency();
        $modelEmergency->employee_id = $model->id;
        if ($id) $modelEmergency = EmployeeEmergency::findOne($id);

        if ($modelEmergency->load(Yii::$app->request->post()) && $modelEmergency->save()) {
            return $this->redirect(['view']);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-emergency', [
                'modelEmergency' => $modelEmergency
            ]);
        }
        return;
    }

    public function actionFormExperience($id = null) {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $modelExperience              = new EmployeeExperience();
        $modelExperience->employee_id = $model->id;
        if ($id) $modelExperience = EmployeeExperience::findOne($id);

        if ($modelExperience->load(Yii::$app->request->post()) && $modelExperience->save()) {
            return $this->redirect(['view']);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-experience', [
                'modelExperience' => $modelExperience
            ]);
        }
        return;
    }

    public function actionFormFamilySelf($id = null) {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $modelFamily              = new EmployeeFamily();
        $modelFamily->employee_id = $model->id;
        $modelFamily->type        = 1;
        if ($id) $modelFamily = EmployeeFamily::findOne($id);

        if ($modelFamily->load(Yii::$app->request->post()) && $modelFamily->save()) {
            return $this->redirect(['view']);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-family-self', [
                'modelFamily' => $modelFamily
            ]);
        }
        return;
    }

    public function actionFormFamilyParent($id = null) {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $modelFamily              = new EmployeeFamily();
        $modelFamily->employee_id = $model->id;
        $modelFamily->type        = 2;
        if ($id) $modelFamily = EmployeeFamily::findOne($id);

        if ($modelFamily->load(Yii::$app->request->post()) && $modelFamily->save()) {
            return $this->redirect(['view']);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-family-parent', [
                'modelFamily' => $modelFamily
            ]);
        }
        return;
    }

    public function actionFormEducationFormal($id = null) {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $modelEducation              = new EmployeeEducation();
        $modelEducation->employee_id = $model->id;
        $modelEducation->type        = 1;
        if ($id) $modelEducation = EmployeeEducation::findOne($id);

        if ($modelEducation->load(Yii::$app->request->post()) && $modelEducation->save()) {
            return $this->redirect(['view']);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-education-formal', [
                'modelEducation' => $modelEducation
            ]);
        }
        return;
    }

    public function actionFormEducationInformal($id = null) {
        $model = Employee::findOne(Yii::$app->user->identity->employee->id);

        $modelEducation              = new EmployeeEducation();
        $modelEducation->employee_id = $model->id;
        $modelEducation->type        = 2;
        if ($id) $modelEducation = EmployeeEducation::findOne($id);

        if ($modelEducation->load(Yii::$app->request->post()) && $modelEducation->save()) {
            return $this->redirect(['view']);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-education-informal', [
                'modelEducation' => $modelEducation
            ]);
        }
        return;
    }

    public function actionEducationDelete($id)
    {
        $model = EmployeeEducation::findOne($id);
        $model->delete();
        return $this->redirect(['view']);
    }

    public function actionFamilyDelete($id)
    {
        $model = EmployeeFamily::findOne($id);
        $model->delete();
        return $this->redirect(['view']);
    }

    public function actionEmergencyDelete($id)
    {
        $model = EmployeeEmergency::findOne($id);
        $model->delete();
        return $this->redirect(['view']);
    }

    public function actionExperienceDelete($id)
    {
        $model = EmployeeExperience::findOne($id);
        $model->delete();
        return $this->redirect(['view']);
    }



    public function actionContractDownload($contract_id) 
    {
        if (($contract = Contract::findOne($contract_id)) !== null) {
            if ($contract->file) {
                $filepath  = Yii::getAlias('@uploads/' . $contract->tableName() . '/' . $contract->file);
                $array     = explode('.', $contract->file);
                $extension = end($array);
                $filename  = $contract->employee->name . '.' . $extension;
    
                if (file_exists($filepath)) return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
            }
        }
        throw new NotFoundHttpException('The requested file does not exist.');       
    }









    public function generatePdfSalary($title, $view, $params = [], $landscape = false) 
    {
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => 'A4',
            'orientation' => $landscape ? 'L' : 'P',
            'marginTop' => '10',
            'marginBottom' => '10',
            'marginLeft' => '10',
            'marginRight' => '10',
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
    


    public function actionReceipt() 
    {
        $model = Payroll::find()->where(['contract_id' => Yii::$app->user->identity->employee->activeContract->contract->id])->orderBy('contract_id DESC')->one();

        if ($model) {
            $title      = 'Slip Gaji';
            $view       = 'print';
            $pre_params = [
                'model' => $model,
                'title' => $title,
                'view'  => $view,
            ];
            $params = array_merge($pre_params, ['params' => $pre_params]);
            return $this->generatePdfSalary($title, $view, $params, 1);
        } else {
            Yii::$app->session->addFlash('error', 'No data available.');
            return $this->redirect(['view']);
        }
    }
}
