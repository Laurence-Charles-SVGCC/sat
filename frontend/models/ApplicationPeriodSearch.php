<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ApplicationPeriod;

/**
 * ApplicationPeriodSearch represents the model behind the search form about `frontend\models\ApplicationPeriod`.
 */
class ApplicationPeriodSearch extends ApplicationPeriod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicationperiodid', 'divisionid', 'personid', 'academicyearid'], 'integer'],
            [['name', 'onsitestartdate', 'onsiteenddate', 'offsitestartdate', 'offsiteenddate'], 'safe'],
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
        $query = ApplicationPeriod::find();

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
            'applicationperiodid' => $this->applicationperiodid,
            'divisionid' => $this->divisionid,
            'personid' => $this->personid,
            'academicyearid' => $this->academicyearid,
            'onsitestartdate' => $this->onsitestartdate,
            'onsiteenddate' => $this->onsiteenddate,
            'offsitestartdate' => $this->offsitestartdate,
            'offsiteenddate' => $this->offsiteenddate,
            'isactive' => $this->isactive,
            'isdeleted' => $this->isdeleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
