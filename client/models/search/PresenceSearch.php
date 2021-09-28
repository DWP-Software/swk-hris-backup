<?php

namespace client\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entity\Presence;

/**
 * PresenceSearch represents the model behind the search form about `common\models\entity\Presence`.
 */
class PresenceSearch extends Presence
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'status', 'started_at', 'ended_at', 'is_late', 'overtime_started_at', 'overtime_ended_at', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['date'], 'safe'],
            [['overtime_summary'], 'number'],
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
        $query = Presence::find();
        $query->joinWith(['contract.contractPlacements'])->where(['presence.client_id' => Yii::$app->user->identity->clientUser->client_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
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
            'presence.employee_id' => $this->employee_id,
            'date' => $this->date,
            'status' => $this->status,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'is_late' => $this->is_late,
            'overtime_started_at' => $this->overtime_started_at,
            'overtime_ended_at' => $this->overtime_ended_at,
            'overtime_summary' => $this->overtime_summary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
