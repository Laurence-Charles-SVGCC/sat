<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\AcademicYear;

/**
 * AcademicYearSearch represents the model behind the search form about `frontend\models\AcademicYear`.
 */
class AcademicYearSearch extends AcademicYear
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['academicyearid'], 'integer'],
            [['title', 'startdate', 'enddate'], 'safe'],
            [['iscurrent', 'isactive', 'isdeleted'], 'boolean'],
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
        $query = AcademicYear::find();

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
            'academicyearid' => $this->academicyearid,
            'iscurrent' => $this->iscurrent,
            'startdate' => $this->startdate,
            'enddate' => $this->enddate,
            'isactive' => $this->isactive,
            'isdeleted' => $this->isdeleted,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
