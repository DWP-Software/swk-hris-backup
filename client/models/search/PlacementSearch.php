<?php

namespace client\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entity\Placement;
use common\models\entity\PlacementContract;
use yii\helpers\ArrayHelper;

/**
 * PlacementSearch represents the model behind the search form about `common\models\entity\Placement`.
 */
class PlacementSearch extends Placement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'client_id', 'plan_employee_type', 'submitted_at', 'responded_at', 'response_type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['plan_started_at', 'plan_ended_at', 'remark'], 'safe'],
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
        $contracted_placements = ArrayHelper::getColumn(PlacementContract::find()->select('placement_id')->asArray()->all(), 'placement_id');
        $query = Placement::find();
        $query->where(['client_id' => Yii::$app->user->identity->clientUser->client_id]);
        $query->andWhere(['not in', 'placement.id', $contracted_placements]);

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
            'employee_id' => $this->employee_id,
            'client_id' => $this->client_id,
            'plan_employee_type' => $this->plan_employee_type,
            'plan_started_at' => $this->plan_started_at,
            'plan_ended_at' => $this->plan_ended_at,
            'submitted_at' => $this->submitted_at,
            'responded_at' => $this->responded_at,
            'response_type' => $this->response_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
