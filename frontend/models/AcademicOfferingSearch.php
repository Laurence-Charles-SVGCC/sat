<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\AcademicOffering;

/**
 * AcademicOfferingSearch represents the model behind the search form about `frontend\models\AcademicOffering`.
 */
class AcademicOfferingSearch extends AcademicOffering
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['academicofferingid', 'programmecatalogid', 'academicyearid', 'applicationperiodid', 'spaces'], 'integer'],
            [['interviewneeded', 'isactive', 'isdeleted'], 'boolean'],
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
        $query = AcademicOffering::find();

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
            'academicofferingid' => $this->academicofferingid,
            'programmecatalogid' => $this->programmecatalogid,
            'academicyearid' => $this->academicyearid,
            'applicationperiodid' => $this->applicationperiodid,
            'spaces' => $this->spaces,
            'interviewneeded' => $this->interviewneeded,
            'isactive' => $this->isactive,
            'isdeleted' => $this->isdeleted,
        ]);

        return $dataProvider;
    }
}
