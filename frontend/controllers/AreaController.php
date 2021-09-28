<?php

namespace frontend\controllers;

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
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $province_id = $parents[0];
                $out = District::find()->where(['province_id' => $province_id])->asArray()->all();
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return Json::encode(['output'=>$out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSubdistrict() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $district_id = empty($ids[1]) ? null : $ids[1];
            if ($province_id != null && $district_id != null) {
                $out = Subdistrict::find()->where(['district_id' => $district_id])->asArray()->all();
                $data = [  
                    'out' => $out,
                    'selected' => '',
                ];
                return Json::encode(['output'=>$data['out'], 'selected'=>$data['selected']]);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionVillage() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $district_id = empty($ids[1]) ? null : $ids[1];
            $subdistrict_id = empty($ids[2]) ? null : $ids[2];
            if ($province_id != null && $district_id != null && $subdistrict_id != null) {
                $out = Village::find()->where(['subdistrict_id' => $subdistrict_id])->asArray()->all();
                $data = [
                    'out' => $out,
                    'selected' => '',
                ];
                return Json::encode(['output'=>$data['out'], 'selected'=>$data['selected']]);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

}