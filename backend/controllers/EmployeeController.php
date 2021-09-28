<?php

namespace backend\controllers;

use common\models\entity\Config;
use common\models\entity\District;
use Yii;
use common\models\entity\User;
use common\models\entity\Employee;
use common\models\entity\EmployeeFile;
use common\models\entity\Payroll;
use common\models\entity\PlacementPlan;
use common\models\entity\Contract;
use common\models\entity\ContractSalary;
use common\models\entity\ContractPlacement;
use common\models\entity\EmployeeEducation;
use common\models\entity\EmployeeEmergency;
use common\models\entity\EmployeeExperience;
use common\models\entity\EmployeeFamily;
use common\models\entity\LatestContract;
use common\models\entity\Province;
use common\models\entity\Subdistrict;
use common\models\entity\Village;
use common\models\search\EmployeeSearch;
use common\models\search\EmployeeSearchContractWaiting;
use common\models\search\EmployeeSearchContractOpened;
use common\models\search\EmployeeSearchContractClosed;
use common\models\search\EmployeeSearchContractEnding;
use common\models\search\EmployeeSearchContractExpired;
use common\models\search\EmployeeSearchPre;
use common\models\search\EmployeeSearchPreUnplaced;
use common\models\search\EmployeeSearchPreWaiting;
use common\models\search\EmployeeSearchPreAccepted;
use common\models\search\EmployeeSearchPreRejected;
use common\models\search\PayrollSearch;
use kartik\mpdf\Pdf;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\web\UploadedFile;
use yii\helpers\Html;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\helpers\ArrayHelper;

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
                    'delete'                    => ['POST'],
                    'delete-pre'                => ['POST'],
                    'placement-plan-delete'     => ['POST'],
                    'contract-placement-delete' => ['POST'],
                    'contract-delete'           => ['POST'],
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
            'contract_status' => Employee::CONTRACT_ALL,
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
            'contract_status' => Employee::CONTRACT_WAITING,
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
            'contract_status' => Employee::CONTRACT_OPENED,
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
            'contract_status' => Employee::CONTRACT_CLOSED,
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
            'contract_status' => Employee::CONTRACT_ENDING,
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
            'contract_status' => Employee::CONTRACT_EXPIRED,
        ]);
    }


    public function actionIndexPre()
    {
        $searchModel = new EmployeeSearchPre();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-pre', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'semua',
        ]);
    }
    public function actionIndexPreUnplaced()
    {
        $searchModel = new EmployeeSearchPreUnplaced();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-pre', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'belum ditempatkan',
        ]);
    }
    public function actionIndexPreWaiting()
    {
        $searchModel = new EmployeeSearchPreWaiting();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-pre', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'menunggu',
        ]);
    }
    public function actionIndexPreAccepted()
    {
        $searchModel = new EmployeeSearchPreAccepted();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-pre', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'diterima',
        ]);
    }
    public function actionIndexPreRejected()
    {
        $searchModel = new EmployeeSearchPreRejected();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-pre', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subtitle' => 'ditolak',
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
    public function actionViewPre($id)
    {
        return $this->render('view-pre', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Employee model.
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
     * Deletes an existing Employee model.
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
    public function actionDeletePre($id)
    {
        try {
            $this->findModel($id)->delete();
            return $this->redirect(['index-pre']);
        } catch (IntegrityException $e) {
            throw new \yii\web\HttpException(500,"Integrity Constraint Violation. This data can not be deleted due to the relation.", 405);
        }
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




    public function actionPlacementPlanForm($id, $placement_plan_id = null)
    {
        $model               = new PlacementPlan();
        $model->employee_id  = $id;
        if ($placement_plan_id) $model = PlacementPlan::findOne($placement_plan_id);

        $employee = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->isNewRecord) $model->submitted_at = time();
            if ($model->response_type) $model->responded_at = $model->submitted_at;
            if ($model->save()) return $this->redirect(['view', 'id' => $id]);
        } else {
            return $this->renderAjax('_form-placement-plan', [
                'model'    => $model,
                'employee' => $employee,
            ]);
        }
    }

    public function actionPlacementPlanDelete($placement_plan_id)
    {
        $placementPlan = PlacementPlan::findOne($placement_plan_id);
        $placementPlan->delete();

        return $this->redirect(['view', 'id' => $placementPlan->employee_id]);
    }

    

    public function actionContractView($contract_id)
    {
        $model       = Contract::findOne($contract_id);
        $modelSalary = new ContractSalary();
        $modelSalary->contract_id = $contract_id;

        $searchModel = new PayrollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;

        if ($modelSalary->load(Yii::$app->request->post())) {
            $modelSalary->save();
            return $this->redirect(['contract-view', 'contract_id' => $contract_id]);
        }

        return $this->render('contract-view', [
            'model'       => $model,
            'modelSalary' => $modelSalary,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContractCreate($employee_id)
    {
        $model                  = new Contract();
        $model->employee_id     = $employee_id;
        $model->pasal_3_2       = 1;
        $model->pasal_3_3       = 30;
        $model->signer_name     = Config::findOne(['key' => 'contract_signer_name'])->value;
        $model->signer_position = Config::findOne(['key' => 'contract_signer_position'])->value;
        $model->signer_address  = Config::findOne(['key' => 'contract_signer_address'])->value;

        $modelPlacement = new ContractPlacement();
        $employee = Employee::findOne($employee_id);

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if ($model->save()) {
                foreach (ContractSalary::types() as $type_key => $type_value) {
                    foreach (ContractSalary::permanentTypes($type_key) as $subtype_key => $subtype_value) {
                        $parsed_subtype_value = str_replace(' ', '_', $subtype_value);
                        $parsed_subtype_value = str_replace(',', '_', $parsed_subtype_value);
                        if (isset($post[$parsed_subtype_value])) {
                            $salary              = new ContractSalary();
                            $salary->contract_id = $model->id;
                            $salary->type        = $type_key;
                            $salary->name        = $subtype_value;
                            $salary->amount      = $post[$parsed_subtype_value];
                            $salary->save();
                        }
                    }
                }
                
                if ($modelPlacement->load($post)) {
                    $modelPlacement->contract_id = $model->id;
                    $outstandingPlacementPlan = PlacementPlan::find()->where(['employee_id' => $employee_id, 'client_id' => $modelPlacement->client_id, 'response_type' => PlacementPlan::RESPONSE_ACCEPTED])->andWhere(['is not', 'responded_at', null])->orderBy('id DESC')->one();
                    if ($outstandingPlacementPlan) {
                        if ((ContractPlacement::findOne(['placement_plan_id' => $outstandingPlacementPlan->id])) === null) {
                            $modelPlacement->placement_plan_id = $outstandingPlacementPlan->id;
                        }
                    }
                    $modelPlacement->save();
                }

                return $this->redirect(['contract-view', 'contract_id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($model->errors));
            }
        }

        return $this->render('contract-create', [
            'model'          => $model,
            'modelPlacement' => $modelPlacement,
            'employee'       => $employee,
        ]);
    }

    public function actionContractUpdate($contract_id)
    {
        $model = Contract::findOne($contract_id);
        $post = Yii::$app->request->post();

        $model->signer_name     = $model->signer_name ?? Config::findOne(['key' => 'contract_signer_name'])->value;
        $model->signer_position = $model->signer_position ?? Config::findOne(['key' => 'contract_signer_position'])->value;
        $model->signer_address  = $model->signer_address ?? Config::findOne(['key' => 'contract_signer_address'])->value;

        if ($model->load($post) && $model->save()) {

            foreach (ContractSalary::types() as $type_key => $type_value) {
                foreach (ContractSalary::permanentTypes($type_key) as $subtype_key => $subtype_value) {
                    $parsed_subtype_value = str_replace(' ', '_', $subtype_value);
                    $parsed_subtype_value = str_replace(',', '_', $parsed_subtype_value);
                    if (isset($post[$parsed_subtype_value])) {
                        if (($salary = ContractSalary::findOne([
                            'contract_id' => $model->id,
                            'type'        => $type_key,
                            'name'        => $subtype_value,
                        ])) === null) {
                            $salary = new ContractSalary();
                        }
                        $salary->contract_id = $model->id;
                        $salary->type        = $type_key;
                        $salary->name        = $subtype_value;
                        $salary->amount      = $post[$parsed_subtype_value];
                        $salary->save();
                    }
                }
            }

            $file = 'uploaded_file';
            $uploadedFile = UploadedFile::getInstance($model, $file);
            if ($uploadedFile) {
                $filename = $model->id .'.'. $uploadedFile->extension;
                $uploadedFile->saveAs(Yii::getAlias('@uploads/' . $model->tableName() . '/' . $filename));
                $model->file = $filename;
                $model->save();
            }
            return $this->redirect(['contract-view', 'contract_id' => $model->id]);
        } else {
            return $this->render('contract-update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionContractDelete($contract_id)
    {
        $contract = Contract::findOne($contract_id);
        $contract->delete();

        return $this->redirect(['view', 'id' => $contract->employee_id]);
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

    public function actionContractPlacementForm($id, $contract_placement_id = null)
    {
        $model               = new ContractPlacement();
        $model->contract_id  = $id;
        if ($contract_placement_id) $model = ContractPlacement::findOne($contract_placement_id);

        $contract = Contract::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) return $this->redirect(['contract-view', 'contract_id' => $id]);
            else Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($model->errors));
        } else {
            return $this->renderAjax('_form-contract-placement', [
                'model'    => $model,
                'contract' => $contract,
            ]);
        }
    }

    public function actionContractPlacementDelete($contract_placement_id)
    {
        $contractPlacement = ContractPlacement::findOne($contract_placement_id);
        $contractPlacement->delete();

        return $this->redirect(['contract-view', 'contract_id' => $contractPlacement->contract_id]);
    }


    public function actionDownload($id) 
    {
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


    public function generatePdf($title, $view, $params = [], $landscape = false) 
    {
        $pdf = new Pdf([
            'mode'         => Pdf::MODE_CORE,
            'format'       => 'A4',
            'orientation'  => $landscape ? 'L' : 'P',
            'marginTop'    => '20',
            'marginBottom' => '20',
            'marginLeft'   => '20',
            'marginRight'  => '20',
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
            ',
        ]);
        return $pdf->render();
    }

    public function actionPrintResume($id) 
    {
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

    public function actionPrintContract($contract_id) 
    {
        $model = Contract::findOne($contract_id);

        if ($model) {
            $title      = 'KONTRAK';
            $view       = 'contract';
            $pre_params = [
                'model' => $model,
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

    public function actionPrintStatement($id) 
    {
        $model = Employee::findOne($id);

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



    public function actionFormSalary($placement_contract_id) 
    {
        $model                        = new ContractSalary();
        $model->placement_contract_id = $placement_contract_id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['contract-view', 'contract_id' => $placement_contract_id]);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-salary', [
                'model' => $model
            ]);
        }
        return;
    }

    public function actionFormPayroll($placement_contract_id) 
    {
        $model                        = new Payroll();
        $model->placement_contract_id = $placement_contract_id;

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            dd($post);
            foreach ($post as $key => $value) {
                # code...
            }
            return $this->redirect(['contract-view', 'contract_id' => $placement_contract_id]);
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-payroll', [
                'model' => $model,
            ]);
        }
        return;
    }


    public function actionUpdateNrk($id) 
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            if (!$model->save()) Yii::$app->session->addFlash('error', 'NRP sudah digunakan sebelumnya.');
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form-update-nrk', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionFaker()
    {
        $employees = [
            '2901190519'   => 'Sugianto',
            '2901190520'   => 'Hamim',
            '2901190521'   => 'Sutrisno',
            '2901190522'   => 'Hermansya Hidayat',
            '2901190523'   => 'Bahrun Mufid',
            '2901190524'   => 'Muhamad Akbar Fauzan',
            '2901190525'   => 'Susanto',
            '2901190526'   => 'Badrul Mutammam',
            '2901190527'   => 'Dedi Prasetyo',
            '2901190528'   => 'Rohadi',
            '2901190529'   => 'Dedy Kurniawan',
            '2901190532'   => 'Aris Martanto',
            '2901190534'   => 'Junaedi',
            '2901190536'   => 'Mukhtar',
            '2901190537'   => 'Fitrah Ramadhan',
            '2901190538'   => 'Doni Gunawan',
            '2901190539'   => 'Abdul Rojak',
            '2901190540'   => 'Agus',
            '2901190541'   => 'Kurniawan Ariansyah',
            '2901190543'   => 'Tomi Atmaja',
            '2901190544'   => 'Kevin Guci Anlersi',
            '2901190545'   => 'Aris Setiawan',
            '2901190546'   => 'Alfian Rianto',
            '2901190547'   => 'Kristian',
            '2901190548'   => 'Dwi Susanto',
            '2901190549'   => 'Zaki Mubarok',
            '2901190550'   => 'Marlan Rudiyansah',
            '2901190551'   => 'Muhammad Rizki Adhadi',
            '2901190552'   => 'Sofyan Sauri',
            '2901190553'   => 'Rohman',
            '2901190554'   => 'Muchamad Sukamto',
            '2901190030'   => 'Gugus Pramono',
            '2901190031'   => 'Wildiana Zuhdan',
            '2901190033'   => 'Syamsul Arifin',
            '2901190034'   => 'Mumu Mupti',
            '2901190035'   => 'Anasrullah',
            '2901190036'   => 'Ahmad Yusuf',
            '2901190037'   => 'Ichtad Hadad Mahdafiki',
            '2901190038'   => 'Simson Roni Halasan',
            '2901190039'   => 'Asep Ependi',
            '2901190040'   => 'Armen Faizal',
            '2901190041'   => 'Suryadi ',
            '2901190042'   => 'Saepul Akrobin',
            '2901190043'   => 'Ade Wahyudin',
            '2901190044'   => 'Reza Fahlevi',
            '2901190045'   => 'Rahmat Agus Prianto',
            '2901190048'   => 'Irawan',
            '2901190049'   => 'Abdul Azis Maulana',
            '2901190050'   => 'Timur Andoko',
            '2901190051'   => 'Rendi Mareta',
            '2901190052'   => 'Ramdhani ',
            '2901190053'   => 'Wahyu Anggara',
            '2901190054'   => 'Ahmad Muna Al Ali',
            '2901190055'   => 'Igip',
            '2901190056'   => 'Michael Zacharias',
            '2903190068'   => 'Alissa Nuzulia Rahmah',
            '2903190072'   => 'Jamaludin',
            '2908190102'   => 'Ahmad Bahrudin',
            'DW0111191260' => 'Warih Budi Mulyanto',
            'DW0111191261' => 'Andi Aprianto',
            'DW0112191263' => 'Valdi Apriansyah',
            'DW0112191275' => 'Ahmad Umam Mushaffah',
        ];

        foreach ($employees as $key => $value) {
            $user           = new User();
            $user->username = time() . '_' . Yii::$app->security->generateRandomString();
            $user->email    = strtolower(str_replace(' ', '', $value)).'@gmail.com';
            $user->setPassword('123456');
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            
            if ($user->save()) {
                // $this->sendEmail($user);

                $auth     = \Yii::$app->authManager;
                $userRole = $auth->getRole('user');
                $auth->assign($userRole, $user->id);

                $faker                             = \Faker\Factory::create('id_ID');
                $employee                          = new Employee();
                $employee->user_id                 = $user->id;
                $employee->identity_number         = $faker->randomNumber(7).$faker->randomNumber(7);
                $employee->registration_number     = (string) $key;
                $employee->name                    = $value;
                $employee->date_of_birth           = $faker->date;
                $employee->place_of_birth          = $faker->city;
                $employee->sex                     = '1';
                $employee->religion                = 'Islam';

                $employee->address_street          = $faker->streetAddress;
                $employee->address_neighborhood    = '';
                $employee->address_province_id     = Province::find()->orderBy(new Expression('rand()'))->one()->id;
                $employee->address_district_id     = District::find()->where(['province_id' => $employee->address_province_id])->orderBy(new Expression('rand()'))->one()->id;
                $employee->address_subdistrict_id  = Subdistrict::find()->where(['district_id' => $employee->address_district_id])->orderBy(new Expression('rand()'))->one()->id;
                $employee->address_village_id      = Village::find()->where(['subdistrict_id' => $employee->address_subdistrict_id])->orderBy(new Expression('rand()'))->one()->id;

                $employee->domicile_street         = $faker->streetAddress;
                $employee->domicile_neighborhood   = '';
                $employee->domicile_province_id    = Province::find()->orderBy(new Expression('rand()'))->one()->id;
                $employee->domicile_district_id    = District::find()->where(['province_id' => $employee->domicile_province_id])->orderBy(new Expression('rand()'))->one()->id;
                $employee->domicile_subdistrict_id = Subdistrict::find()->where(['district_id' => $employee->domicile_district_id])->orderBy(new Expression('rand()'))->one()->id;
                $employee->domicile_village_id     = Village::find()->where(['subdistrict_id' => $employee->domicile_subdistrict_id])->orderBy(new Expression('rand()'))->one()->id;

                $employee->phone                   = $faker->phoneNumber;
                $employee->education_level         = (string) array_rand(Employee::educationLevels());
                $employee->family_number           = $faker->randomNumber(7).$faker->randomNumber(7);
                $employee->mother_name             = $faker->name('female');
                $employee->nationality             = 'WNI';
                $employee->height                  = $faker->numberBetween(140, 180);
                $employee->weight                  = $faker->numberBetween(40, 80);
                $employee->marital_status          = array_rand(Employee::maritalStatuses());
                $employee->blood_type              = (string) array_rand(Employee::bloodTypes());

                // d($user);
                // dd($employee);
                if (!$employee->save()) Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($employee->errors));
            } else {
                Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($user->errors));
            }
        }
        return $this->redirect(['index']);
    }

    public function actionMassPlacement()
    {
        $employees = Employee::find()->all();
        foreach ($employees as $employee) {
            if (PlacementPlan::findOne(['employee_id' => $employee->id]) === null) {
                $placement                = new PlacementPlan();
                $placement->employee_id   = $employee->id;
                $placement->client_id     = 5;
                $placement->submitted_at  = time();
                $placement->responded_at  = $placement->submitted_at;
                $placement->response_type = 1;
                if (!$placement->save()) Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($placement->errors));
            }
        }
        return $this->redirect(['index']);
    }


    public function actionMassContract()
    {
        $faker      = \Faker\Factory::create('id_ID');
        $placements = PlacementPlan::find()->all();

        foreach ($placements as $placement) {
            if (Contract::findOne(['employee_id' => $placement->employee_id]) === null) {
                $contract                   = new Contract();
                $contract->employee_id      = $placement->employee_id;
                $contract->employee_type_id = substr($placement->employee->registration_number, 0, 2) == 'DW' ? 2 : 1;
                $contract->started_at       = '2019-11-01';
                $contract->ended_at         = '2020-10-31';
                $contract->file             = 'dummy.pdf';
                if ($contract->save()) {
                    $contractPlacement              = new ContractPlacement();
                    $contractPlacement->contract_id = $contract->id;
                    $contractPlacement->client_id   = '5';
                    $contractPlacement->position    = 'Admin';
                    $contractPlacement->location    = 'Jakarta';
                    $contractPlacement->started_at  = '2019-11-01';
                    $contractPlacement->ended_at    = '2020-10-31';
                    $contractPlacement->save();

                    foreach (ContractSalary::types() as $type_key => $type_value) {
                        foreach (ContractSalary::permanentTypes() as $permanentType_key => $permanentType_value) {
                            if ($type_key == $permanentType_key) {
                                foreach ($permanentType_value as $subType) {
                                    $contractSalary              = new ContractSalary();
                                    $contractSalary->contract_id = $contract->id;
                                    $contractSalary->type        = $type_key;
                                    $contractSalary->name        = $subType;
                                    $contractSalary->amount      = $faker->numberBetween(10000, 10000000);
                                    $contractSalary->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->redirect(['index']);
    }

    public function actionExport($contract_status = Employee::CONTRACT_ALL)
    {
        $i = 0;
        $query = Employee::find();

        if ($contract_status == Employee::CONTRACT_NULL) {
            $contracted_employees   = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
        
            $query->where(['not in', 'employee.id', $contracted_employees]);
        }

        if ($contract_status == Employee::CONTRACT_ALL) {
            $contracted_employees   = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
        
            $query->where(['in', 'employee.id', $contracted_employees]);
        }

        if ($contract_status == Employee::CONTRACT_WAITING) {
            $contracted_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
            $unexpired_contracts  = ArrayHelper::getColumn(LatestContract::find()->select('employee_id')->where(['>=', 'contract_ended_at', date('Y-m-d')])->asArray()->all(), 'employee_id');
            
            $query->where(['in', 'employee.id', $contracted_employees]);
            $query->andWhere(['not in', 'employee.id', $unexpired_contracts]);
        }

        if ($contract_status == Employee::CONTRACT_OPENED) {
            $contracted_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
            $opened_contracts     = ArrayHelper::getColumn(LatestContract::find()->select('employee_id')->where(['and', ['file' => null], ['>=', 'contract_ended_at', date('Y-m-d')]])->asArray()->all(), 'employee_id');
            
            $query->where(['in', 'employee.id', $contracted_employees]);
            $query->andWhere(['in', 'employee.id', $opened_contracts]);
        }

        if ($contract_status == Employee::CONTRACT_CLOSED) {
            $contracted_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
            $closed_contracts     = ArrayHelper::getColumn(LatestContract::find()->select('employee_id')->where(['and', ['is not', 'file', null], ['>=', 'contract_ended_at', date('Y-m-d')]])->asArray()->all(), 'employee_id');
            
            $query->where(['in', 'employee.id', $contracted_employees]);
            $query->andWhere(['in', 'employee.id', $closed_contracts]);
        }

        if ($contract_status == Employee::CONTRACT_ENDING) {
            $contracted_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
            $ending_contracts     = ArrayHelper::getColumn(LatestContract::find()->select('employee_id')->where([
                'and', 
                ['is not', 'file', null], 
                ['>=', 'contract_ended_at', date('Y-m-d')], 
                ['<=', 'DATEDIFF(contract_ended_at, \''.date('Y-m-d').'\')', 30]
            ])->asArray()->all(), 'employee_id');
            
            $query->where(['in', 'employee.id', $contracted_employees]);
            $query->andWhere(['in', 'employee.id', $ending_contracts]);
        }

        if ($contract_status == Employee::CONTRACT_EXPIRED) {
            $contracted_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
            $expired_contracts    = ArrayHelper::getColumn(LatestContract::find()->select('employee_id')->where(['and', ['is not', 'file', null], ['<', 'contract_ended_at', date('Y-m-d')]])->asArray()->all(), 'employee_id');
            
            $query->where(['in', 'employee.id', $contracted_employees]);
            $query->andWhere(['in', 'employee.id', $expired_contracts]);
        }

        $employees = $query->all();

        $styleHeader = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('B1', 'DATA KARYAWAN PT SALAM WADAH KARYA');
        $sheet->setCellValue('B2', date('d F Y'));

        $sheet->setCellValue('A3', 'No');
        
        $sheet->setCellValue('B3', 'DATA KARYAWAN');
        
        $sheet->setCellValue('B4', 'Nama');
        $sheet->setCellValue('C4', 'NRK');
        $sheet->setCellValue('D4', 'Jabatan');
        $sheet->setCellValue('E4', 'Mitra');
        $sheet->setCellValue('F4', 'Penempatan');
        $sheet->setCellValue('G4', 'Divisi');
        $sheet->setCellValue('H4', 'Alamat Mitra');
        $sheet->setCellValue('I4', 'Jenis Kelamin');
        $sheet->setCellValue('J4', 'Tempat Lahir');
        $sheet->setCellValue('K4', 'Tanggal Lahir');
        $sheet->setCellValue('L4', 'Alamat Sesuai KTP');
        $sheet->setCellValue('M4', 'Alamat Domisili');
        $sheet->setCellValue('N4', 'Telp/HP');
        $sheet->setCellValue('O4', 'Email');
        $sheet->setCellValue('P4', 'Agama');
        $sheet->setCellValue('Q4', 'Status Pernikahan');

        $sheet->setCellValue('R3', 'PENDIDIKAN TERAKHIR');

        $sheet->setCellValue('R4', 'Jenjang');
        $sheet->setCellValue('S4', 'Institusi');
        $sheet->setCellValue('T4', 'Jurusan');
        $sheet->setCellValue('U4', 'Tahun Lulus');

        $sheet->setCellValue('V3', 'Gaji');
        $columns_salary = ['V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI'];
        $j = 0;
        foreach (ContractSalary::types() as $type_key => $type_value) {
            foreach (ContractSalary::permanentTypes($type_key) as $subtype_key => $subtype_value) {
                $sheet->setCellValue($columns_salary[$j++].'4', $subtype_value);
            }
        }
        
        $sheet->setCellValue('AJ3', 'Bank');
        $sheet->setCellValue('AK3', 'No. Rekening');
        $sheet->setCellValue('AL3', 'NPWP');

        $sheet->setCellValue('AM3', 'Keluarga');
        $columns_family = ['AM', 'AN', 'AO'];
        $j = 0;
        foreach (EmployeeFamily::positions() as $position_key => $position_value) {
            $sheet->setCellValue($columns_family[$j++].'4', $position_value);
        }

        $sheet->setCellValue('AP3', 'Kontak Darurat');

        $sheet->setCellValue('AQ3', 'Kontrak');



        $sheet->mergeCells('A3:A4');
        
        $sheet->mergeCells('B3:Q3');
        $sheet->mergeCells('R3:U3');
        $sheet->mergeCells('V3:AH3');

        $sheet->mergeCells('AJ3:AJ4');
        $sheet->mergeCells('AK3:AK4');
        $sheet->mergeCells('AL3:AL4');

        $sheet->mergeCells('AM3:AO3');

        $sheet->mergeCells('AP3:AP4');

        $sheet->mergeCells('AQ3:AQ4');

        $sheet->getStyle('A3:AQ4')->applyFromArray($styleHeader);

        foreach ($employees as $employee) {
            $currentRow = $i+5;
            $sheet->setCellValue('A'.$currentRow, ++$i);

            $sheet->setCellValue('B'.$currentRow, $employee->name);
            $sheet->setCellValue('C'.$currentRow, " ".(string)$employee->registration_number);
            if ($employee->latestContractPlacement) {
                $sheet->setCellValue('D'.$currentRow, $employee->latestContractPlacement->position);
                $sheet->setCellValue('E'.$currentRow, $employee->latestContractPlacement->contractPlacement->client->name);
                $sheet->setCellValue('F'.$currentRow, $employee->latestContractPlacement->location);
                $sheet->setCellValue('G'.$currentRow, $employee->latestContractPlacement->department);
                $sheet->setCellValue('H'.$currentRow, $employee->latestContractPlacement->contractPlacement->client->address);
            }
            $sheet->setCellValue('I'.$currentRow, $employee->sexes($employee->sex));
            $sheet->setCellValue('J'.$currentRow, $employee->place_of_birth);
            $sheet->setCellValue('K'.$currentRow, $employee->date_of_birth);
            $sheet->setCellValue('L'.$currentRow, $employee->addressText);
            $sheet->setCellValue('M'.$currentRow, $employee->domicileText);
            $sheet->setCellValue('N'.$currentRow, $employee->phone);
            $sheet->setCellValue('O'.$currentRow, $employee->user->email);
            $sheet->setCellValue('P'.$currentRow, $employee->religions($employee->religion));
            $sheet->setCellValue('Q'.$currentRow, $employee->maritalStatuses($employee->marital_status));

            $latestEducation = EmployeeEducation::find()->where(['employee_id' => $employee->id, 'type' => 1])->orderBy('year_end DESC')->one();
            if ($latestEducation) {
                $sheet->setCellValue('R'.$currentRow, $latestEducation->level);
                $sheet->setCellValue('S'.$currentRow, $latestEducation->name);
                $sheet->setCellValue('T'.$currentRow, $latestEducation->major);
                $sheet->setCellValue('U'.$currentRow, $latestEducation->year_end);
            }

            $experiences = EmployeeExperience::find()->where(['employee_id' => $employee->id])->all();
            if ($experiences) {
                $array = [];
                foreach ($experiences as $experience) {
                    $array[] = $experience->name .', '. $experience->position .', '. $experience->year_start .'-'. $experience->year_end;
                }
                $experienceText = implode("\n", $array);
                $sheet->setCellValue('V'.$currentRow, $experienceText);
            }

            $latestContract = LatestContract::find()->where(['employee_id' => $employee->id])->one();
            if ($latestContract) {
                $j = 0;
                foreach (ContractSalary::types() as $type_key => $type_value) {
                    foreach (ContractSalary::permanentTypes($type_key) as $subtype_key => $subtype_value) {
                        $value = null;
                        if (($salary = ContractSalary::findOne([
                            'contract_id' => $latestContract->contract->id,
                            'type'        => $type_key,
                            'name'        => $subtype_value,
                        ])) !== null) {
                            $value = $salary->amount;
                        }
                        $sheet->setCellValue($columns_salary[$j++].$currentRow, $value);
                    }
                }
            }

            $sheet->setCellValue('AJ'.$currentRow, $employee->bank_name);
            $sheet->setCellValue('AK'.$currentRow, $employee->bank_account);
            $sheet->setCellValue('AL'.$currentRow, $employee->tax_number);

            $j = 0;
            foreach (EmployeeFamily::positions() as $position_key => $position_value) {
                $families = EmployeeFamily::find()->where(['employee_id' => $employee->id, 'position' => $position_key])->orderBy('position DESC')->all();
                $array = [];
                foreach ($families as $family) {
                    $array[] = $family->name .', '. $family->position .', '. $family->place_of_birth .'-'. $family->date_of_birth;
                }
                $familyText = implode("\n", $array);
                $sheet->setCellValue($columns_family[$j++].$currentRow, $familyText);
            }

            $emergencies = EmployeeEmergency::find()->where(['employee_id' => $employee->id])->all();
            if ($emergencies) {
                $array = [];
                foreach ($emergencies as $emergency) {
                    $array[] = $emergency->name .', '. $emergency->relationship .', '. $emergency->phone .'-'. $emergency->address;
                }
                $emergencyText = implode("\n", $array);
                $sheet->setCellValue('AP'.$currentRow, $emergencyText);
            }

            $contracts = Contract::find()->where(['employee_id' => $employee->id])->all();
            if ($contracts) {
                $array = [];
                foreach ($contracts as $contract) {
                    $array[] = $contract->employeeType->name .': '. $contract->started_at .'-'. $contract->ended_at;
                }
                $contractText = implode("\n", $array);
                $sheet->setCellValue('AQ'.$currentRow, $contractText);
            }
        }

        // $filename = 'Data Karyawan '.time().'.xlsx';
        $filename = 'Data Karyawan.xlsx';
        $filepath = Yii::getAlias('@webroot/'.$filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        
        if (file_exists($filepath)) return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]); 
    }
}
