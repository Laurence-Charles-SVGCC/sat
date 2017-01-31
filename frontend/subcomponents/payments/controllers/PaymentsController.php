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
    /**
     * Displays transaction with the same receipt number
     * 
     * @param type $receiptnumber
     * @param type $status
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: 06/08/2015
     * Date Last Modified [L.Charles] : 19/01/2017 | 20/01/2017
     */
    public function actionGetTransactionReceipt($receiptnumber, $status)
    {
        $models = Transaction::findAll(['receiptnumber' => $receiptnumber, 'isactive' => 1, 'isdeleted' => 0]);
        
        if ($models)
        {
            $personid = $models[0]->personid;
            $applicant = Applicant::findOne(['personid' => $personid]);
        }
       
        return $this->render('/transaction/invoice', [
            'models' => $models,
            'applicant' => $applicant,
            'status' => $status,
            'personid' => $personid,
            'receiptnumber' => $receiptnumber,
        ]);
        
    }
    
    
    /**
     * Prints transaction with the same receipt number
     * 
     * @param type $receiptnumber
     * @param type $status
     * @return type
     * 
     * Author: Gamal Crichton
     * Date Created: 06/08/2015
     * Date Last Modified [L.Charles] : 19/01/2017 | 20/01/2017
     */
    public function actionPrintTransactionReceipt($receiptnumber)
    {
        $models = Transaction::findAll(['receiptnumber' => $receiptnumber, 'isactive' => 1, 'isdeleted' => 0]);
        if ($models)
        {
            $personid = $models[0]->personid;
            $applicant = Applicant::findOne(['personid' => $personid]);
            $user = User::find()->where(['personid' => $personid])->one();
        }
        return $this->renderPartial('/transaction/print_invoice', [
            'models' => $models,
            'applicant' => $applicant,
            'user' => $user,
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
                            ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
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
                    $user = $applicant->getPerson()->one();
                    if($status == "applicant")
                    {
                        $app = array();
//                        $user = $applicant->getPerson()->one();
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
        $data = array();
        $dataProvider = array();
        $heading = NULL;
        
        $transactions = Transaction::find()
                ->where(['personid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('transactionsummaryid')
                ->all();
        
        if ($transactions)
        {
            
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
                
                $trans['id'] = $user->personid;
                $trans['status'] = $status;
                $trans['username'] = $user->username;
                $trans['fullname'] = $applicant->firstname . " " . $applicant->lastname;
                $trans['transactionid'] = $transaction->transactionid;
                $trans['summaryid'] = $summary->transactionsummaryid;
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
                $trans['total_due'] =  $transaction->totaldue;
                $trans['total_paid'] =  $transaction->paymentamount;
                $trans['balance'] = $transaction->totaldue - $transaction->paymentamount;
                $trans['can_delete'] = Transaction::canDelete($transaction->transactionid);
                $data[] = $trans;
            }
        }
        
        $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => ['pageSize' => 10],
                'sort' => [ 'attributes' => ['academic_year', 'purpose', 'transaction_item'],],
            ]);
        
        return $this->render('view_transactions', 
            [
            'dataProvider' => $dataProvider,
            'status' => $status,
            'heading' => $heading,
            'id' => $id
        ]);
    }
    
    
}
