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
use frontend\models\TransactionType;
use frontend\models\PaymentMethod;
use frontend\models\Semester;
use frontend\models\EmployeeDepartment;
use frontend\models\Division;
use frontend\models\TransactionItem;


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
            $app_fee = TransactionPurpose::findOne(['name' => 'application']);
            if ($app_fee && $model->transactionpurposeid == $app_fee->transactionpurposeid)
            {
                $type = TransactionType::findOne(['name' => 'full payment', 'isdeleted' => 0]);
                $method = PaymentMethod::findOne(['name' => 'cash', 'isdeleted' => 0]);
                $sem = Semester::findOne(['isactive' => 1, 'isdeleted' => 0]);
                if ($model->totaldue == '') { $model->totaldue = 20.00;}
                if ($model->paymentamount == '') { $model->paymentamount = 20.00;}
                if ($type && $model->transactiontypeid == '') { $model->transactiontypeid = $type->transactiontypeid;}
                if ($method && $model->paymentmethodid == '') { $model->paymentmethodid = $method->paymentmethodid;}
                if ($sem && $model->semesterid == '') { $model->semesterid = $sem->semesterid;}
                if ($model->paydate == '') { $model->paydate = date('Y-m-d');}
            }
            
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
    public function actionGetTransactionReceipt($receiptnumber, $status)
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
            'status' => $status,
           'personid' => $personid,
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Facilitates search for current applicants
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 12/01/2017
     * Date Last Modified: 12/01/2017
     */
    public function actionFindApplicantOrStudent($status, $new_search = 0)
    {
        $dataProvider = null;
        $info_string = null;
        
        if (Yii::$app->request->post())
        {
             //Everytime a new search is initiated session variable must be removed
             if (Yii::$app->session->get('app_id'))
                Yii::$app->session->remove('app_id');
             
            if (Yii::$app->session->get('firstname'))
                Yii::$app->session->remove('firstname');
            
            if (Yii::$app->session->get('lastname'))
                Yii::$app->session->remove('lastname');
            
             if (Yii::$app->session->get('email'))
                Yii::$app->session->remove('email');
            
            $request = Yii::$app->request;
            $app_id = $request->post('applicantid_field');
            $firstname = $request->post('FirstName_field');
            $lastname = $request->post('LastName_field');
            $email = $request->post('email_field');
            
            if(Yii::$app->session->get('app_id') == null  && $app_id == true)
                Yii::$app->session->set('app_id', $app_id);
            
            if(Yii::$app->session->get('firstname') == null  && $firstname == true)
                Yii::$app->session->set('firstname', $firstname);
            
            if(Yii::$app->session->get('lastname') == null  && $lastname == true)
                Yii::$app->session->set('lastname', $lastname);
            
            if(Yii::$app->session->get('email') == null  && $email == true)
                Yii::$app->session->set('email', $email);
        }
        else    
        {
            $app_id = Yii::$app->session->get('app_id');
            $firstname = Yii::$app->session->get('firstname');
            $lastname = Yii::$app->session->get('lastname');
            $email = Yii::$app->session->get('email');
        }
            
            
        //if user initiates search based on applicantid
        if ($app_id)
        {
            if ($status == "applicant")
            {
                $user = User::findOne(['username' => $app_id, 'isdeleted' => 0]);
                if ($user)
                {
                    $cond_arr['applicant.personid'] = $user->personid;
                    $info_string = $info_string .  " Applicant ID: " . $app_id;
                }
                else
                {
                    $applicant = Applicant::find()
                            ->where(['potentialstudentid' => $app_id, 'isdeleted' => 0])
                            ->one();
                    $cond_arr['applicant.personid'] = $applicant ? $aplicant->personid : NULL;
                    $info_string = $info_string .  " Student ID: " . $app_id;
                }
            }
            elseif ($status == "student")
            {
                $user = User::findOne(['username' => $app_id, 'isdeleted' => 0]);
                $cond_arr['applicant.personid'] = $user? $user->personid : null;
                $info_string = $info_string .  " Studnet ID: " . $app_id;
            }
        }    

        //if user initiates search based on applicant name    
        if ($firstname)
        {
            $cond_arr['applicant.firstname'] = $firstname;
            $info_string = $info_string .  " First Name: " . $firstname; 
        }
        if ($lastname)
        {
            $cond_arr['applicant.lastname'] = $lastname;
            $info_string = $info_string .  " Last Name: " . $lastname;
        }        

        //if user initiates search based on applicant email
        if ($email)
        {
            $email_add = Email::findOne(['email' => $email, 'isdeleted' => 0]);
            $cond_arr['applicant.personid'] = $email_add? $email_add->personid: null;
            $info_string = $info_string .  " Email: " . $email;
        }

        if ($new_search == 1)
        {
            
        }
        elseif (empty($cond_arr))
        {
            Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
        }
        else
        {
            $cond_arr['applicant.isactive'] = 1;
            $cond_arr['applicant.isdeleted'] = 0;

            if ($status == "student")
            {
                 $cond_arr['application.isactive'] = 1;
                $cond_arr['application.isdeleted'] = 0;
                $cond_arr['application.applicationstatusid'] = 9;
                $cond_arr['offer.isactive'] = 1;  
                $cond_arr['offer.isdeleted'] = 0;
                $cond_arr['offer.ispublished'] = 1;
            }

            if ($status == "applicant")
            {
                $applicants = Applicant::find()
                            ->where($cond_arr)
                            ->groupBy('applicant.personid')
                            ->all();
            }
            elseif($status == "student")
            {
                $applicants = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                         ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->where($cond_arr)
                        ->groupBy('applicant.personid')
                        ->all();
            }

            if (empty($applicants))
            {
                Yii::$app->getSession()->setFlash('error', 'No applicant found matching this criteria.');
            }
            else
            {
                $data = array();
                foreach ($applicants as $applicant)
                {
                    if($status == "applicant")
                    {
                        $app = array();
                        $user = $applicant->getPerson()->one();
                        $app['username'] = $applicant->potentialstudentid? $applicant->potentialstudentid : $user->username;
                    }
                    elseif($status == "student")
                    {
                        $app['username'] = $user->username;
                    }

                    $app['personid'] = $applicant->personid;
                    $app['firstname'] = $applicant->firstname;
                    $app['middlename'] = $applicant->middlename;
                    $app['lastname'] = $applicant->lastname;
                    $app['gender'] = $applicant->gender;
                    $app['status'] = $status;

                    $applications = Application::getApplications($applicant->personid);
                    $divisionid = $applications[0]->divisionid;

                    /*
                     * If division is DTE or DNE then all applications refer to one division
                     */
                    if ($divisionid == 6  || $divisionid == 7)
                    {
                        $division = Division::getDivisionAbbreviation($divisionid);
                        $app["division"] = $division;
                    }
                    /*
                     * If division is DASGS or DTVE then applications may refer to multiple divisions
                     */
                    elseif ($divisionid == 4  || $divisionid == 5)
                    {
                        $dasgs = 0;
                        $dtve = 0;
                        foreach($applications as $application)
                        {
                            if ($application->divisionid == 4)
                                $dasgs++;
                            elseif ($application->divisionid == 5)
                                $dtve++;
                        }
                        if ($dasgs>=1  && $dtve>=1)
                            $divisions = "DASGS & DTVE";
                        elseif ($dasgs>=1  && $dtve==0)
                            $divisions = "DASGS";
                        elseif ($dasgs==0  && $dtve>=1)
                            $divisions = "DTVE";
                        else
                             $divisions = "Unknown";
                        $app["division"] = $divisions;
                    }
                    $data[] = $app;
                }

                $dataProvider = new ArrayDataProvider([
                    'allModels' => $data,
                    'pagination' => [
                        'pageSize' => 15,
                    ],
                    'sort' => [
                        'attributes' => ['username', 'firstname', 'lastname'],
                        ],
                ]);
            }
        }
        
        return $this->render('find_applicant', 
            [
            'dataProvider' => $dataProvider,
            'status' => $status,
            'info_string' => $info_string,
        ]);
    }
    
    
     /**
     * Generates list of all transaction for a user
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 14/01/2017
     * Date Last Modified: 14/01/2017
     */
    public function actionViewUserTransactions($id, $status)
    {
        $dataProvider = NULL;
        $heading = NULL;
        
        $transactions = Transaction::find()
                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->groupBy('transactionsummaryid')
                ->all();
        
        if ($transactions)
        {
            $data = array();
            foreach ($transactions as $transaction)
            {
                $trans = array();
                $semester = $transaction->getSemester()->one();
                if ($semester == false)
                    continue;
                
                $summary = $transaction->getTransactionsummary()->one();
                 if ($summary == false)
                    continue;
                 
                $user = User::findOne(['personid' => $transaction->personid]);
                 if ($user == false)
                    continue;
                 
                 $applicant = Applicant::find()
                         ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                if ($applicant == false)
                    continue;
                 
                $payment_method = PaymentMethod::find()
                        ->where(['paymentmethodid' => $transaction->paymentmethodid, 'isdeleted' => 0])
                        ->one();
                if ($payment_method == false)
                    continue;
                
                $transaction_type = TransactionType::find()
                        ->where(['transactiontypeid' => $transaction->transactiontypeid, 'isdeleted' => 0])
                        ->one();
                if ($transaction_type == false)
                    continue;
                
                $transaction_item = TransactionItem::find()
                        ->where(['transactionitemid' => $transaction->transactionitemid, 'isdeleted' => 0])
                        ->one();
                if ($transaction_item == false)
                    continue;
                
                $trans['status'] = $status;
                $trans['username'] = $user->username;
                $trans['fullname'] = $applicant->firstname . " " . $applicant->lastname;
                $trans['payment_method'] = $payment_method->name;
                $trans['type'] = $transaction_type->name;
                $trans['date_paid'] = $transaction->paydate;
                $trans['transaction_item'] = $transaction_item->name;
                $heading = $heading == NULL? $applicant->firstname . " " . $applicant->lastname . " Payments" : $heading;
                $trans['receiptnumber'] =  $transaction->receiptnumber;
                $trans['comments'] =  $transaction->comments ?  $transaction->comments: "N/A";
                $trans['transaction_group_id'] = $transaction->transactionsummaryid;
                $trans['academic_year'] = $semester->getAcademicyear()->one()->title;
                $trans['academic_semester'] = $semester->title;
                $trans['purpose'] = $transaction->getTransactionpurpose()->one()->name;
                $trans['total_paid'] = $summary->totalpaid;
                $trans['balance'] = $summary->balance;
                $data[] = $trans;
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => ['pageSize' => 20],
                'sort' => [ 'attributes' => ['academic_year', 'purpose', 'transaction_item'],],
            ]);
        }
        
        return $this->render('view_transactions', 
            [
            'dataProvider' => $dataProvider,
            'status' => $status,
            'heading' => $heading,
            'id' => $id
        ]);
    }
}
