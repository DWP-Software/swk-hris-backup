<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entity\PlacementContract;

/**
 * PlacementContractSearch represents the model behind the search form about `common\models\entity\PlacementContract`.
 */
class PlacementContractSearch extends PlacementContract
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'placement_id', 'employee_type_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['position', 'started_at', 'ended_at', 'file'], 'safe'],
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
        $query = PlacementContract::find();

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
            'placement_id' => $this->placement_id,
            'employee_type_id' => $this->employee_type_id,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['position', 'file', $this->file]);
        $query->andFilterWhere(['like', 'file', $this->file]);

        return $dataProvider;
    }
}
