<?php

namespace app\subcomponents\payments\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Transaction;
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
                        $semester = $transaction->getSemester();
                        
                        $trans['transaction_group_id'] = $transaction->transactionsummaryid;
                        $trans['academic_year'] = $semester ? $semester->getAcademicyear()->name : '';
                        $trans['academic_semester'] = $semester ? $semester->name : '';
                        $trans['fee_purpose'] = $transaction->getTransactionpurpose();
                        $trans['total_paid'] = $transaction->getTransactionsummary()->total_paid;
                        $trans['balance'] = $transaction->getTransactionsummary()->balance;
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
    * Last Modified: 21/07/2015 by Gamal Crichton
    */
    public function actionNewPayment()
    {
        $model = new Transaction();
        var_dump(Yii::$app->request);
        if ($model->load(Yii::$app->request->post()))
        {
            var_dump(Yii::$app->request);
            /*$request = Yii::$app->request;
            $model->personid = ;
            $model->recepientid = Yii::$app->user->username;
            $model->transactionsummaryid = ;
            $model->receiptnumber = ;*/
        }
        
        return $this->render('new-payment',
                [
                    'model' => $model,
                ]);
    }
}
