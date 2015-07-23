<?php

namespace app\subcomponents\payments\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\Transaction;
use frontend\models\TransactionSearch;
use frontend\models\TransactionSummary;
use frontend\models\Applicant;
use yii\data\ArrayDataProvider;


class PaymentsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionManagePayments()
    {
        return $this->render('manage-payments');
    }
    
    /*
    * Purpose: Provides for to collect search parameters and display results.
    * Created: 21/07/2015 by Gamal Crichton
    * Last Modified: 21/07/2015 by Gamal Crichton
    */
    public function actionSearchApplicant()
    {
        $dataProvider = $app_ids = NULL;
        $info_string = "";
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $app_id = $request->post('id');
            $firstname = $request->post('firstname');
            $lastname = $request->post('lastname');
            
            if ($app_id)
            {
                 $cond_arr['personid'] = $app_id;
                 $info_string = $info_string .  " Applicant ID: " . $app_id;
            }
            if ($firstname)
            {
                $cond_arr['firstname'] = $firstname;
                $info_string = $info_string .  " First Name: " . $firstname; 
            }
            if ($lastname)
            {
                $cond_arr['lastname'] = $lastname;
                $info_string = $info_string .  " Last Name: " . $lastname;
            }
            
            if (empty($cond_arr))
            {
                Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
            }
            else
            {
                $cond_arr['isdeleted'] = 0;  
            
                $applicants = Applicant::find()->where($cond_arr)->all();
                if (empty($applicants))
                {
                    Yii::$app->getSession()->setFlash('error', 'No user found matching this criteria.');
                }
                else
                {
                    $app_ids = array();
                    foreach($applicants as $applicant)
                    {
                        $app_ids[] = $applicant['personid']; 
                    }

                    $transactions = !empty($app_ids) ? Transaction::find()->where(['personid' => $app_ids])->groupby('transactionsummaryid')->all() : array();
                    $data = array();
                    foreach ($transactions as $transaction)
                    {
                        $trans = array();
                        $semester = $transaction->getSemester()->one();
                        $summary = $transaction->getTransactionsummary()->one();
                        
                        $trans['transaction_group_id'] = $transaction->transactionsummaryid;
                        $trans['academic_year'] = $semester ? $semester->getAcademicyear()->one()->title : '';
                        $trans['academic_semester'] = $semester ? $semester->title : '';
                        $trans['fee_purpose'] = $transaction->getTransactionpurpose()->one()->name;
                        $trans['total_paid'] = $summary->total_paid;
                        $trans['balance'] = $summary->balance;
                        $data[] = $trans;
                    }
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $data,
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                    ]);
                }
        }
    }
    return $this->render('search', 
        [
            'type' => 'applicant',
            'results' => $dataProvider,
            'result_users' => $app_ids,
            'info_string' => $info_string,
        ]);
  }
  
    /*
    * Purpose: Provides for to collect search parameters and display results.
    * Created: 21/07/2015 by Gamal Crichton
    * Last Modified: 22/07/2015 by Gamal Crichton
    */
    public function actionNewPayment()
    {
        $model = new Transaction();
        if ($model->load(Yii::$app->request->post()))
        {
            $request = Yii::$app->request;
            $total_due = $model->totaldue;
            $trans_amt = $model->paymentamount;
            $summary = new TransactionSummary();
            if ($total_due && $trans_amt)
            {
                $summary->balance = $total_due - $trans_amt;
                $summary->total_paid = $trans_amt;
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Total Paid ans Total Due are required.');
            }
            if ($summary->save())
            {
                $model->personid = $request->post('payee_id');
                $model->recepientid = Yii::$app->user->getId();
                $model->transactionsummaryid = $summary->transactionsummaryid;
                $model->receiptnumber = self::getReceiptNumber();
                
                if ($model->save())
                {
                    $this->redirect(Url::to(['payments/view-transactions', 'personid' => $model->personid]));
                }
                //var_dump($model);
                
            }
            Yii::$app->session->setFlash('error', 'Transaction could not be added');
        }
        return $this->render('new-payment',
                [
                    'model' => $model,
                    'payee_id' => Yii::$app->request->post('payee_id'),
                ]);
    }
    
    /*
    * Purpose: Views all transactions based on query parameters
    * Created: 22/07/2015 by Gii
    * Last Modified: 22/07/2015 by Gamal Crichton
    */
    public function actionViewTransactions($transactionsummaryid = '')
    {
        //var_dump(Yii::$app->request->queryParams);
        $searchModel = new TransactionSearch();
        $searchparams = $transactionsummaryid ? ['transactionsummaryid' => $transactionsummaryid] : array();
        //$dataProvider = $searchModel->search($searchparams);
        
        $data = Transaction::find()->where($searchparams)->all();
        $dataProvider = new ArrayDataProvider(
            [
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['personid', 'firstname', 'middlenames', 'lastname', 'gender'],
            ],
        ]);

        return $this->render('view-transactions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'transactionsummaryid' => $transactionsummaryid,
        ]);
    }
    
    public function actionTransactionTypes()
    {
        $this->redirect(['transaction-type/index']);
    }
    
    public function actionTransactionPurposes()
    {
        $this->redirect(['transaction-purpose/index']);
    }
    
    public function actionPaymentMethods()
    {
        $this->redirect(['payment-method/index']);
    }
    
    
    /*
    * Purpose: Creates receipt number for new transaction
     * TODO: Make private static. Not able to because it is used in TransactionController::create()
    * Created: 22/07/2015 by Gamal Crichton
    * Last Modified: 22/07/2015 by Gamal Crichton
    */
    public static function getReceiptNumber()
    {
        $last_transaction = Transaction::find()->orderBy('transactionid DESC', 'desc')->one();
        $num = $last_transaction ? strval($last_transaction->receiptnumber + 1) : 1;
        while (strlen($num) < 6)
        {
            $num = '0' . $num;
        }
        
        return strlen($num) > 6 ? $num : '15' . $num;
    }
}
