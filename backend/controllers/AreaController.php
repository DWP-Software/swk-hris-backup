<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\entity\District;
use common\models\entity\Subdistrict;
use common\models\entity\Village;

/**
 * MaterialController implements the CRUD actions for Material model.
 */
class AreaController extends Controller
{

    public function actionDistrict() {
        $data = [
            'output'   => '',
            'selected' => '',
        ];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents) {
                $province_id = $parents[0];
                if ($province_id) {
                    $output = District::find()->where(['province_id' => $province_id])->asArray()->all();
                    $data = [
                        'output'   => $output,
                        'selected' => '',
                    ];
                    // the getSubCatList function will query the database based on the
                    // cat_id and return an array like below:
                    // [
                    //    ['id' => '<sub-cat-id-1>', 'name' => '<sub-cat-name1>'],
                    //    ['id' => '<sub-cat_id_2>', 'name' => '<sub-cat-name2>']
                    // ]
                    return Json::encode($data);
                }
            }
        }
        return Json::encode($data);
    }

    public function actionSubdistrict() {
        $data = [
            'output'   => '',
            'selected' => '',
        ];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = $ids[0];
            $district_id = $ids[1];
            if ($province_id && $district_id) {
                $output = Subdistrict::find()->where(['district_id' => $district_id])->asArray()->all();
                $data = [  
                    'output'   => $output,
                    'selected' => '',
                ];
                return Json::encode($data);
            }
        }
        return Json::encode($data);
    }

    public function actionVillage() {
        $data = [
            'output'   => '',
            'selected' => '',
        ];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id    = $ids[0];
            $district_id    = $ids[1];
            $subdistrict_id = $ids[2];
            if ($province_id && $district_id && $subdistrict_id) {
                $output = Village::find()->where(['subdistrict_id' => $subdistrict_id])->asArray()->all();
                $data = [
                    'output'   => $output,
                    'selected' => '',
                ];
                return Json::encode($data);
            }
        }
        return Json::encode($data);
    }

}