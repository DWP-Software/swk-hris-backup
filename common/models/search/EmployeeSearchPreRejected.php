<?php

namespace common\models\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entity\Employee;
use common\models\entity\LatestPlacement;
use common\models\entity\Placement;

/**
 * EmployeeSearch represents the model behind the search form about `common\models\entity\Employee`.
 */
class EmployeeSearchPreRejected extends Employee
{
    public $placementProcessStatus;
    public $placementClientName;
    public $addressText;
    public $domicileText;
    public $placeAndDateOfBirth;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'sex', 'marital_status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['identity_number', 'registration_number', 'phone', 'name', 'date_of_birth', 'place_of_birth', 'religion', 'address_street', 'address_neighborhood', 'address_village_id', 'address_subdistrict_id', 'address_district_id', 'address_province_id', 'domicile_street', 'domicile_neighborhood', 'domicile_village_id', 'domicile_subdistrict_id', 'domicile_district_id', 'domicile_province_id', 'education_level', 'family_number', 'mother_name', 'nationality', 'blood_type'], 'safe'],
            [['height', 'weight'], 'number'],

            [['placementProcessStatus', 'placementClientName'], 'safe'],
            [['addressText', 'domicileText', 'placeAndDateOfBirth'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Employee::find();
        
        $contracted_employees       = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['placements.placementContracts'])->where(['is not', 'placement_contract.id', null])->asArray()->all(), 'id');
        $uncontracted_employees     = ArrayHelper::getColumn(Employee::find()->select('employee.id')->where(['not in', 'id', $contracted_employees])->asArray()->all(), 'id');
        $latest_placements_unplaced = ArrayHelper::getColumn(LatestPlacement::find()->select('employee_id')->where(['response_type' => Placement::RESPONSE_REJECTED])->asArray()->all(), 'employee_id');
        $query->where(['in', 'employee.id', $uncontracted_employees]);
        $query->andWhere(['in', 'employee.id', $latest_placements_unplaced]);
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'date_of_birth' => $this->date_of_birth,
            'sex' => $this->sex,
            'height' => $this->height,
            'weight' => $this->weight,
            'marital_status' => $this->marital_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'identity_number', $this->identity_number])
            ->andFilterWhere(['like', 'registration_number', $this->registration_number])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'place_of_birth', $this->place_of_birth])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'address_street', $this->address_street])
            ->andFilterWhere(['like', 'address_neighborhood', $this->address_neighborhood])
            ->andFilterWhere(['like', 'address_village_id', $this->address_village_id])
            ->andFilterWhere(['like', 'address_subdistrict_id', $this->address_subdistrict_id])
            ->andFilterWhere(['like', 'address_district_id', $this->address_district_id])
            ->andFilterWhere(['like', 'address_province_id', $this->address_province_id])
            ->andFilterWhere(['like', 'domicile_street', $this->domicile_street])
            ->andFilterWhere(['like', 'domicile_neighborhood', $this->domicile_neighborhood])
            ->andFilterWhere(['like', 'domicile_village_id', $this->domicile_village_id])
            ->andFilterWhere(['like', 'domicile_subdistrict_id', $this->domicile_subdistrict_id])
            ->andFilterWhere(['like', 'domicile_district_id', $this->domicile_district_id])
            ->andFilterWhere(['like', 'domicile_province_id', $this->domicile_province_id])
            ->andFilterWhere(['like', 'education_level', $this->education_level])
            ->andFilterWhere(['like', 'family_number', $this->family_number])
            ->andFilterWhere(['like', 'mother_name', $this->mother_name])
            ->andFilterWhere(['like', 'nationality', $this->nationality])
            ->andFilterWhere(['like', 'blood_type', $this->blood_type]);

        return $dataProvider;
    }
}
