<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Semester;

/**
 * SemesterSearch represents the model behind the search form about `frontend\models\Semester`.
 */
class SemesterSearch extends Semester
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semesterid', 'academicyearid'], 'integer'],
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
        $query = Semester::find();

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
            'semesterid' => $this->semesterid,
            'academicyearid' => $this->academicyearid,
            'startdate' => $this->startdate,
            'enddate' => $this->enddate,
            'iscurrent' => $this->iscurrent,
            'isactive' => $this->isactive,
            'isdeleted' => $this->isdeleted,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
