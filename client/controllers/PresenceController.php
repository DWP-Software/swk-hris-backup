<?php

namespace client\controllers;

use Yii;
use common\models\entity\Presence;
use client\models\search\PresenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\web\UploadedFile;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use common\models\entity\Employee;
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

    public function actionReport($to_pdf = false)
    {
        $params = Yii::$app->request->get('params');
        if (!is_array($params)) $params = [];
        $pre_params = [
            'employee_id' => null,
            'month'       => date('m'),
            'year'        => date('Y'),
            'view'        => 'report',
        ];
        $params = array_replace($pre_params, $params);

        $query = Employee::find()->joinWith(['presences.contract.contractPlacements'])->where(['contract_placement.client_id' => Yii::$app->user->identity->clientUser->client_id]);
        if ($params['employee_id']) $query->andWhere(['employee.id' => $params['employee_id']]);
        if ($params['month']) $query->andWhere(['month(presence.`date`)' => $params['month']]);
        if ($params['year']) $query->andWhere(['year(presence.`date`)' => $params['year']]);

        $models = $query->asArray()->all();
        return $this->render('report', [
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
            'month'       => date('m'),
            'year'        => date('Y'),
            'view'        => 'spreadsheet',
        ];
        $params = array_replace($pre_params, $params);

        $query = Employee::find()->joinWith(['presences.contract.contractPlacements'])->where(['contract_placement.client_id' => Yii::$app->user->identity->clientUser->client_id]);
        if ($params['employee_id']) $query->andWhere(['employee.id' => $params['employee_id']]);
        if ($params['month']) $query->andWhere(['month(presence.`date`)' => $params['month']]);
        if ($params['year']) $query->andWhere(['year(presence.`date`)' => $params['year']]);

        $models = $query->asArray()->all();
        return $this->renderPartial('spreadsheet', [
            'models' => $models,
            'params' => $params,
            'to_pdf' => $to_pdf,
        ]);
    }
}
