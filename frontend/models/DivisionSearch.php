<?php

namespace app\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Division;

/**
 * DivisionSearch represents the model behind the search form about `frontend\models\Division`.
 */
class DivisionSearch extends Division
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['divisionid'], 'integer'],
            [['name', 'abbreviation', 'phone', 'website', 'email', 'country', 'constituency', 'town', 'addressline'], 'safe'],
            [['isactive', 'isdeleted'], 'boolean'],
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
        $query = Division::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'divisionid' => $this->divisionid,
            'isactive' => $this->isactive,
            'isdeleted' => $this->isdeleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'abbreviation', $this->abbreviation])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'constituency', $this->constituency])
            ->andFilterWhere(['like', 'town', $this->town])
            ->andFilterWhere(['like', 'addressline', $this->addressline]);

        return $dataProvider;
    }
}
