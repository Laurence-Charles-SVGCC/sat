<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form about `frontend\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transactionid', 'transactiontypeid', 'personid', 'transactionpurposeid', 'recepientid', 'semesterid', 'paymentmethodid', 'transactionsummaryid', 'verifyingofficerid'], 'integer'],
            [['paydate', 'comments', 'receiptnumber'], 'safe'],
            [['paymentamount', 'totaldue'], 'number'],
            [['isverified', 'isactive', 'isdeleted'], 'boolean'],
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
        $query = Transaction::find();

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
            'transactionid' => $this->transactionid,
            'transactiontypeid' => $this->transactiontypeid,
            'personid' => $this->personid,
            'transactionpurposeid' => $this->transactionpurposeid,
            'recepientid' => $this->recepientid,
            'semesterid' => $this->semesterid,
            'paymentmethodid' => $this->paymentmethodid,
            'transactionsummaryid' => $this->transactionsummaryid,
            'verifyingofficerid' => $this->verifyingofficerid,
            'paydate' => $this->paydate,
            'paymentamount' => $this->paymentamount,
            'totaldue' => $this->totaldue,
            'isverified' => $this->isverified,
            'isactive' => $this->isactive,
            'isdeleted' => $this->isdeleted,
        ]);

        $query->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'receiptnumber', $this->receiptnumber]);

        return $dataProvider;
    }
}
