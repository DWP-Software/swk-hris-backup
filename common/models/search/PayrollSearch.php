<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entity\Payroll;

/**
 * PayrollSearch represents the model behind the search form about `common\models\entity\Payroll`.
 */
class PayrollSearch extends Payroll
{
    public $employee_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'contract_id', 'year', 'month', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['employee_id'], 'safe'],
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
        $query = Payroll::find();

        // add conditions that should always apply here
        $query->joinWith(['contract.employee.latestContractPlacement.contractPlacement.client.picClients']);
        if (Yii::$app->user->can('Keuangan')) {
            $query->where(['pic_client.user_id' => Yii::$app->user->id, 'role' => 'Keuangan']);
        }

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
            'contract.employee_id' => $this->employee_id,
            'payroll.contract_id' => $this->contract_id,
            'year' => $this->year,
            'month' => $this->month,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
