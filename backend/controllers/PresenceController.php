<?php

namespace backend\controllers;

use Yii;
use common\models\entity\Presence;
use common\models\search\PresenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\web\UploadedFile;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use common\models\entity\Contract;
use common\models\entity\Employee;
use common\models\entity\PicClient;
use common\models\entity\PlacementContract;

/**
 * PresenceController implements the CRUD actions for Presence model.
 */
class PresenceController extends Controller
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
     * Lists all Presence models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PresenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReport($to_pdf = false)
    {
        $params = Yii::$app->request->get('params');
        if (!is_array($params)) $params = [];
        $pre_params = [
            'employee_id' => null,
            'client_id'   => null,
            'month'       => (int) date('m'),
            'year'        => date('Y'),
            'view'        => 'report',
        ];
        $params = array_replace($pre_params, $params);

        $query = Employee::find()->joinWith(['presences.contract.contractPlacements']);
        if ($params['employee_id']) $query->andWhere(['employee.id' => $params['employee_id']]);
        if ($params['client_id']) $query->andWhere(['contract_placement.client_id' => $params['client_id']]);
        if ($params['month']) $query->andWhere(['month(presence.`date`)' => $params['month']]);
        if ($params['year']) $query->andWhere(['year(presence.`date`)' => $params['year']]);

        $models = $query->asArray()->all();
        return $this->render('report', [
            'employee_id' => $params['employee_id'],
            'client_id'   => $params['client_id'],
            'models' => $models,
            'params' => $params,
            'to_pdf' => $to_pdf,
        ]);
    }

    public function actionSpreadsheet($to_pdf = false)
    {
        $params = Yii::$app->request->get('params');
        if (!is_array($params)) $params = [];
        $pre_params = [
            'employee_id' => null,
            'client_id'   => null,
            'month'       => (int) date('m'),
            'year'        => date('Y'),
            'view'        => 'spreadsheet',
        ];
        $params = array_replace($pre_params, $params);

        $query = Employee::find()->joinWith(['presences.contract.contractPlacements']);
        if ($params['employee_id']) $query->andWhere(['employee.id' => $params['employee_id']]);
        if ($params['client_id']) $query->andWhere(['contract_placement.client_id' => $params['client_id']]);
        if ($params['month']) $query->andWhere(['month(presence.`date`)' => $params['month']]);
        if ($params['year']) $query->andWhere(['year(presence.`date`)' => $params['year']]);

        $models = $query->asArray()->all();
        return $this->renderPartial('spreadsheet', [
            'employee_id' => $params['employee_id'],
            'client_id'   => $params['client_id'],
            'models' => $models,
            'params' => $params,
            'to_pdf' => $to_pdf,
        ]);
    }

    /**
     * Displays a single Presence model.
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
     * Creates a new Presence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Presence();

        if ($model->load(Yii::$app->request->post())) {
            $client = $model->employee->getCurrentClient($model->date);
            if ($client) {
                $assignedPic = PicClient::findOne(['user_id' => Yii::$app->user->id, 'client_id' => $client->id]);
                if ($assignedPic) {
                    if ($model->save()) return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->addFlash('error', 'Anda bukan PIC yg ditugaskan untuk '.$model->employee->name.' ('.$client->name.')');
                }
            } else {
                Yii::$app->session->addFlash('error', 'Tidak ditemukan penempatan untuk '.$model->employee->name.' pada tanggal '.$model->date);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreateDaily($client_id = null, $date = null)
    {
        if (!$date) $date = date('Y-m-d');

        if ($post = (Yii::$app->request->post())) {
            foreach ($post as $key => $value) {
                if (($contract = Contract::findOne($key)) !== null) {
                    if (!$contract->currentPlacement) dd($contract->placements);
                    $presence = Presence::findOne(['date' => $date, 'contract_id' => $contract->id]);
                    if (!$presence) $presence = new Presence();
                    $presence->contract_id = $contract->id;
                    $presence->employee_id = $contract->employee_id;
                    $presence->client_id   = $contract->currentPlacement->client_id;
                    $presence->date        = $date;
                    $presence->status      = $value;
                    if (!$presence->save()) Yii::$app->session->addFlash('error', \yii\helpers\Json::encode($presence->errors));
                }
            }
            return $this->redirect(['index']);
        } 
        return $this->render('create-daily', [
            'client_id' => $client_id,
            'date' => $date,
        ]);
    }

    /**
     * Updates an existing Presence model.
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
     * Deletes an existing Presence model.
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
     * Finds the Presence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Presence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Presence::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport()
    {
        if ($post = Yii::$app->request->post()) {

            $packageFile    = UploadedFile::getInstanceByName('package-file');
            $reader         = ReaderFactory::create(Type::XLSX);
            $reader->open($packageFile->tempName);

            $savedRowCount = 0;
            $savedRows = [];
            $savedCells = [];
            $unsavedCells = [];

            $range = range('A', 'Z');
            $extendedRange = [];
            foreach ($range as $element) {
                $extendedRange[] = 'A'.$element;
            }
            $columns = array_merge($range, $extendedRange);

            foreach ($reader->getSheetIterator() as $sheet) {
                $rowCount = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    $rowCount++;
                    $flag = 0;

                    $flagSavedRow = 0;

                    foreach ($row as $index) {
                        if ($index != "") $flag = 1;
                    }
                    if ($flag && $rowCount >= 3 && (string)$row[2]) {
                        foreach ($row as $key => $value) {

                            if ($key >= 3 && $value) {
                                $employee_id = null;
                                $contract_id = null;

                                if (($employee = Employee::findOne(['registration_number' => (string)$row[2]])) !== null) {
                                    $employee_id = $employee->id;
                                    $assignedPic = PicClient::findOne(['user_id' => Yii::$app->user->id, 'client_id' => $employee->activeContractPlacement->client_id]);
                                    if ($employee->activeContract && ($assignedPic || Yii::$app->user->can('Administrator'))) {
                                        $contract_id = $employee->activeContract->contract_id;
                                        $client_id   = $employee->activeContract->contract->contractPlacements[0]->client_id;

                                        $presenceCodes = ['1', 'A', 'OL', 'AL', 'AL', 'P', 'T'];

                                        $model              = new Presence();
                                        $model->employee_id = $employee_id;
                                        $model->contract_id = $contract_id;
                                        $model->client_id   = $client_id;
                                        $model->date        = $post['year'] . '-' . str_pad($post['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad(($key - 2), 2, '0', STR_PAD_LEFT);
                                        $model->status      = in_array(strtoupper($value), $presenceCodes) ? strtoupper($value) : null;
                                        if ($model->employee_id && validateDate($model->date, 'Y-m-d')) {
                                            if ($model->save()) {
                                                $flagSavedRow = 1;
                                                $savedCells[] = $columns[$key].$rowCount;
                                            } else {
                                                $unsavedCells[] = $columns[$key].$rowCount;
                                            }
                                        } else {
                                            // Yii::$app->session->addFlash('error', 'Baris '. $rowCount . ': Data tidak valid. Periksa lagi apakah NRK sudah dientrikan dengan benar. '
                                            // .'date validation: '.$model->date. ', id: '.$model->employee_id);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($flagSavedRow) {
                        $savedRowCount++;
                        $savedRows[] = $rowCount;
                    }
                }
            }
            $reader->close();
            if ($savedRowCount) {
                $savedRowString      = !$savedRows ? ''    :implode(', ', $savedRows);
                $savedCellString     = !$savedCells ? ''   :implode(', ', $savedCells);
                $unsavedCellString   = !$unsavedCells ? '' :implode(', ', $unsavedCells);
                $unsavedCellSentence = !$unsavedCells ? '' :'<br><span class="small text-danger">Cell tidak tersimpan: '. $unsavedCellString .'</span>';
                Yii::$app->session->addFlash('success', '<big><b>' . $savedRowCount . '</b> data tersimpan.</big>'.$unsavedCellSentence);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('import');
        }
    }
}
