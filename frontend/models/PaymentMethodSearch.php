<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\PaymentMethod;

/**
 * PaymentMethodSearch represents the model behind the search form about `frontend\models\PaymentMethod`.
 */
class PaymentMethodSearch extends PaymentMethod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paymentmethodid'], 'integer'],
            [['name'], 'safe'],
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
        $query = PaymentMethod::find();

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
            'paymentmethodid' => $this->paymentmethodid,
            'isactive' => $this->isactive,
            'isdeleted' => $this->isdeleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
