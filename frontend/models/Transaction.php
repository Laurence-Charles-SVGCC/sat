<?php

namespace frontend\models;

use Yii;

use common\models\User;

/**
 * This is the model class for table "transaction".
 *
 * @property string $transactionid
 * @property string $transactionitemid
 * @property string $transactiontypeid
 * @property string $personid
 * @property string $transactionpurposeid
 * @property string $recepientid
 * @property string $semesterid
 * @property string $paymentmethodid
 * @property string $transactionsummaryid
 * @property string $verifyingofficerid
 * @property string $paydate
 * @property string $paymentamount
 * @property string $totaldue
 * @property boolean $isverified
 * @property string $comments
 * @property string $receiptnumber
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property TransactionType $transactiontype
 * @property Person $person
 * @property TransactionPurpose $transactionpurpose
 * @property Person $recepient
 * @property Semester $semester
 * @property PaymentMethod $paymentmethod
 * @property TransactionSummary $transactionsummary
 * @property Person $verifyingofficer
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transactiontypeid', 'transactionitemid', 'personid', 'transactionpurposeid', 'recepientid', 'semesterid', 'paymentmethodid', 'transactionsummaryid', 'paydate', 'paymentamount', 'totaldue', 'receiptnumber'], 'required'],
//            [['transactionpurposeid'], 'required'],
            [['transactionitemid', 'transactiontypeid', 'personid', 'transactionpurposeid', 'recepientid', 'semesterid', 'paymentmethodid', 'transactionsummaryid', 'verifyingofficerid'], 'integer'],
            [['paydate'], 'safe'],
            [['paymentamount', 'totaldue'], 'number'],
            [['isverified', 'isactive', 'isdeleted'], 'boolean'],
            [['comments'], 'string'],
            [['receiptnumber'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transactionid' => 'Transactionid',
            'transactiontypeid' => 'Transaction Type',
            'personid' => 'Personid',
            'transactionpurposeid' => 'Transaction Purpose',
            'recepientid' => 'Recepientid',
            'semesterid' => 'Semester',
            'paymentmethodid' => 'Payment Method',
            'transactionsummaryid' => 'Transaction Summary',
            'verifyingofficerid' => 'Verifyingofficerid',
            'paydate' => 'Date of Payment',
            'paymentamount' => 'Amount Paid',
            'totaldue' => 'Total Due',
            'isverified' => 'Isverified',
            'comments' => 'Comments',
            'receiptnumber' => 'Receiptnumber',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactiontype()
    {
        return $this->hasOne(TransactionType::className(), ['transactiontypeid' => 'transactiontypeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(User::className(), ['personid' => 'personid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionpurpose()
    {
        return $this->hasOne(TransactionPurpose::className(), ['transactionpurposeid' => 'transactionpurposeid']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionitem()
    {
        return $this->hasOne(TransactionItem::className(), ['transactionitemid' => 'transactionitemid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecepient()
    {
        return $this->hasOne(User::className(), ['personid' => 'recepientid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semesterid' => 'semesterid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentmethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['paymentmethodid' => 'paymentmethodid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionsummary()
    {
        return $this->hasOne(TransactionSummary::className(), ['transactionsummaryid' => 'transactionsummaryid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerifyingofficer()
    {
        return $this->hasOne(User::className(), ['personid' => 'verifyingofficerid']);
    }
    
    
    /**
     * Generate a recipt number
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 17/01/2017
     * Date Last Modified: 17/01/2017
     */
    public static function generateReceiptNumber($paydate)
    {
        $initial_transaction = self::isFirstAnnualTransaction($paydate);
        if ($initial_transaction == false)
        {
            return self::getYearFromDate($paydate) . "000001";
        }
        else
        {
            $db = Yii::$app->db;
            $transactions_for_same_year = $db->createCommand(
                    "SELECT *"
                    . " FROM transaction" 
                    . " WHERE isactive=1"
                    . " AND isdeleted=0"
                    . " AND receiptnumber LIKE '" . substr($paydate, 2, 2) . "%'"
                    . " ORDER BY transactionid DESC"
                    . ";"
                )
                ->queryAll();
            
            $last_transaction = $transactions_for_same_year[0];
            return strval(intval($last_transaction["receiptnumber"])+1);
        }
    }
   
    
    /**
     * Returns the first annual transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 17/01/2017
     * Date Last Modified: 17/01/2017
     */
    public static function isFirstAnnualTransaction ($date)
    {
        $target_receipt = self::getYearFromDate($date) . "000001";
        $transaction = Transaction::find()
                ->where(['receiptnumber' => $target_receipt,  'isactive' => 1, 'isdeleted' => 0])
                ->one();
        if ($transaction)
            return $transaction;
        return false;
    }
    
    
    /**
     * Returns 'year' section of a date
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 17/01/2017
     * Date Last Modified: 17/01/2017
     */
    public static function getYearFromDate($date)
    {
         return substr($date, 2, 2);
    }
    
    
    /**
     * Create full payment transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/01/2017
     * Date Last Modified: 18/01/2017
     */
    public static function getInitialPayment($summaryid)
    {
        $transactions = Transaction::find()
                ->where(['transactionsummaryid' => $summaryid, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('transactionid ASC')
                ->all();
        if ($transactions)
            return $transactions[0];
        return false;
    }
    
    
    /**
     * Determines if a transaction can be deleted.
     * All 'full payment' transaction can be deleted.
     * If transaction is 'partial payment' it must be the 'only insallment' or the 'last installment of a chain' to be elgible for deletion
     * 
     * @param type $transactionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 20/1/2017
     * Date Last Modified: 20/01/2017
     */
    public static function canDelete($transactionid)
    {
         $transaction = Transaction::find()
                ->where(['transactionid' => $transactionid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
       
         //if full payment
        if ($transaction->transactiontypeid == 1)
        {
            return true;
        }
        
        //if partial payment
        elseif ($transaction->transactiontypeid == 2)
        {
            $related_transactions = Transaction::find()
                ->where(['transactionsummaryid' => $transaction->transactionsummaryid, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('transactionid ASC')
                ->all();
            
            $count = count($related_transactions);
            
            if ($count == 1)
            {
                return true;
            }
            else
            {
                $target_index = NULL;
                for($i=0 ; $i<$count ; $i++)
                {
                    if ($related_transactions[$i]->transactionid == $transactionid)
                    {
                        $target_index = $i;
                        break;
                    }
                }
                if($count-$target_index == 1)       // if is last transaction
                {
                    return true;
                }
            }
            
        }
         
        return false;
    }
    
}
