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
use common\models\User;
use frontend\models\TransactionPurpose;
use frontend\models\Application;
use frontend\models\ApplicationPeriod;
use frontend\models\ApplicationHistory;
use frontend\models\Offer;


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
        $dataProvider = $usernames = NULL;
        $info_string = "";
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $app_id = $request->post('id');
            $firstname = $request->post('firstname');
            $lastname = $request->post('lastname');
            
            if ($app_id)
            {
                $user = User::findOne(['username' => $app_id, 'isdeleted' => 0]);
                 $cond_arr['personid'] = $user ? $user->personid : Null;
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
                    $usernames = array();
                    foreach($applicants as $applicant)
                    {
                        $user = User::findOne(['personid' => $applicant->personid]);
                        $usernames[$applicant->personid] = $user ? $user->username : NULL;
                        $app_ids[] = $applicant['personid']; 
                    }

                    $transactions = !empty($app_ids) ? Transaction::find()->where(['personid' => $app_ids])->groupby('transactionsummaryid')->all() : array();
                    $data = array();
                    foreach ($transactions as $transaction)
                    {
                        $trans = array();
                        $semester = $transaction->getSemester()->one();
                        $summary = $transaction->getTransactionsummary()->one();
                        $user = User::findOne(['personid' => $transaction->personid]);
                        
                        $trans['username'] = $user ? $user->username : NULL;
                        $trans['transaction_group_id'] = $transaction->transactionsummaryid;
                        $trans['academic_year'] = $semester ? $semester->getAcademicyear()->one()->title : '';
                        $trans['academic_semester'] = $semester ? $semester->title : '';
                        $trans['fee_purpose'] = $transaction->getTransactionpurpose()->one()->name;
                        $trans['total_paid'] = $summary->totalpaid;
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
            'result_users' => $usernames,
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
            $total_due = floatval($model->totaldue);
            $trans_amt = floatval($model->paymentamount);
            $summary = new TransactionSummary();
            if ($total_due >= 0 && $trans_amt >= 0)
            {
                $summary->balance = $total_due - $trans_amt;
                $summary->totalpaid = floatval($trans_amt);
                if ($summary->save())
                {
                    $model->personid = $request->post('payee_id');
                    $model->recepientid = Yii::$app->user->getId();
                    $model->transactionsummaryid = $summary->transactionsummaryid;
                    $model->receiptnumber = self::getReceiptNumber();
                    if ($model->save())
                    {
                        $remove_app = TransactionPurpose::findOne(['name' => 'application removal']);
                        if ($remove_app && $model->transactionpurposeid == $remove_app->transactionpurposeid)
                        {
                            self::removeApplications($model->personid);
                        }
                        $this->redirect(Url::to(['payments/view-transactions', 'personid' => $model->personid]));
                    }
                }
                Yii::$app->session->setFlash('error', 'Transaction could not be added');
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Total Paid and Total Due are required.');
            }
            
        }
        return $this->render('new-payment',
                [
                    'model' => $model,
                    'payee_id' => Yii::$app->request->post('select_user'),
                ]);
    }
    
    /*
    * Purpose: Views all transactions based on query parameters
    * Created: 22/07/2015 by Gii
    * Last Modified: 22/07/2015 by Gamal Crichton
    */
    public function actionViewTransactions($transactionsummaryid = '')
    {
        $searchModel = new TransactionSearch();
        $searchparams = $transactionsummaryid ? ['transactionsummaryid' => $transactionsummaryid] : array();
        
        $data = Transaction::find()->where($searchparams)->all();
        $dataProvider = new ArrayDataProvider(
            [
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
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
    * Purpose: Provides view to update an existing Transaction model.
    * Created: 06/08/2015 by Gamal Crichton
    * Last Modified: 06/08/2015 by Gamal Crichton
    */
    public function actionUpdateTransaction($receiptnumber)
    {
        if (Yii::$app->request->post())
        {
            $transactionid = Yii::$app->request->post('transactionid');
            $model = Transaction::findOne(['transactionid' =>$transactionid]);
            //Get old transaction attributes
            $cur_paid = $model->paymentamount;
            $summary = TransactionSummary::findOne(['transactionsummaryid' => $model->transactionsummaryid]);
            if ($model->load(Yii::$app->request->post()) && $model->save() && $summary)
            {
                $diff_paid = floatval($model->paymentamount) - $cur_paid;
                $newtotalpaid = $summary->totalpaid + $diff_paid;
                $summary->totalpaid = $newtotalpaid;
                $summary->balance = floatval($model->totaldue) - $newtotalpaid;
                if ($summary->save())
                {
                    $this->redirect(['payments/view-transactions', 'personid' => $model->personid]);
                }
            }
            Yii::$app->session->setFlash('error', 'Transaction could nto be updated');
        }
        
        $model = Transaction::findOne(['receiptnumber' =>$receiptnumber]);

        if ($model) {
            return $this->render('update-transaction', [
                'model' => $model,
                'payee_id' => $model->personid,
                    ]);
        }
        $this->redirect(['payments/index']);
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
    
    /*
    * Purpose: Deletes an applicant's applications for active application periods
    * Created: 10/08/2015 by Gamal Crichton
    * Last Modified: 10/08/2015 by Gamal Crichton
    */
    private function removeApplications($personid)
    {
        $app_periods = ApplicationPeriod::findAll(['isactive' => 1, 'isdeleted' => 0]);
        foreach ($app_periods as $ap)
        {
            $applications = Application::findAll(['personid' => $personid, 'isdeleted' => 0]);
            foreach ($applications as $app)
            {
                $ac_offering = $app->getAcademicOffering()->one();
                if ($ac_offering && $ac_offering->applicationperiodid == $ap->applicationperiodid)
                {
                    $app->isdeleted = 1;
                    $app->isactive = 0;
                    $app_history = ApplicationHistory::findAll(['applicationid' => $app->applicationid]);
                    foreach($app_history as $ah)
                    {
                        $ah->isdeleted = 1;
                        $ah->isactive = 0;
                        $ah->save();
                    }
                    $offers = Offer::findAll(['applicationid' => $app->applicationid]);
                    foreach($offers as $offer)
                    {
                        $offer->isdeleted = 1;
                        $offer->isactive = 0;
                        $offer->save();
                    }
                    $app->save();
                }
            }
        }
    }
    
    /*
    * Purpose: Provides view to update an existing Transaction model.
    * Created: 06/08/2015 by Gamal Crichton
    * Last Modified: 06/08/2015 by Gamal Crichton
    */
    public function actionGetTransactionReceipt($receiptnumber)
    {
        $models = Transaction::findAll(['receiptnumber' => $receiptnumber]);
        
        if ($models)
        {
            $personid = $models[0]->personid;
            //Only applicant supported for now, others later
            $applicant = Applicant::findOne(['personid' => $personid]);
        }
       
        return $this->render('/transaction/invoice', [
            'models' => $models,
            'applicant' => $applicant,
        ]);
        
    }
    
    /*
    * Purpose: Provides view to update an existing Transaction model.
    * Created: 06/08/2015 by Gamal Crichton
    * Last Modified: 06/08/2015 by Gamal Crichton
    */
    public function actionPrintTransactionReceipt($receiptnumber)
    {
        $models = Transaction::findAll(['receiptnumber' => $receiptnumber]);
        if ($models)
        {
            $personid = $models[0]->personid;
            //Only applicant supported for now, others later
            $applicant = Applicant::findOne(['personid' => $personid]);
        }
        return $this->renderPartial('/transaction/invoice-print', [
            'models' => $models,
            'applicant' => $applicant,
        ]);

    }
}
