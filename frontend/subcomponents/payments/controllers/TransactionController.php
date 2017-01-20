<?php

namespace app\subcomponents\payments\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use yii\base\Model;

use frontend\models\Transaction;
use frontend\models\TransactionSummary;
use frontend\models\TransactionItem;
use frontend\models\TransactionSearch;
use frontend\models\Applicant;
use common\models\User;

use frontend\subcomponents\payments\controllers\PaymentsController;


/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    
    /**
     * Create full payment transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 17/01/2017
     * Date Last Modified: 17/01/2017
     */
    public function actionCreateFullPayment($personid, $status)
    {
        $applicant = Applicant::find()->where(['personid' => $personid, 'isdeleted' => 0])->one();
        $name = $applicant->firstname . " " . $applicant->lastname;
        $username = User::find()->where(['personid' => $personid, 'isdeleted' => 0])->one()->username;
                
        $transaction = new Transaction();
        $transaction->transactiontypeid = 1;
        $transaction->paymentmethodid = 1;
        $transaction->paydate = date('Y-m-d');

        if ($post_data = Yii::$app->request->post()) 
        {
            $transaction_load_flag = $transaction->load($post_data);
            
            if($transaction_load_flag == true)
            {
                $transaction_unit = \Yii::$app->db->beginTransaction();
                try 
                {
                    $transaction_summary = new TransactionSummary();
                    $transaction_summary->balance = 0;
                    $transaction_summary->totalpaid = $transaction->paymentamount;
                    if ($transaction_summary->save() == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record');
                        $transaction_unit->rollBack();
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                    
                    $transaction->personid = $personid;
                    $transaction->recepientid = Yii::$app->user->getId();
                    $transaction->totaldue = $transaction->paymentamount;
                    $transaction->transactionsummaryid = $transaction_summary->transactionsummaryid;
                    $purpose = TransactionItem::find()->where(['transactionitemid' => $transaction->transactionitemid, 'isactive' => 1, 'isdeleted' => 0])->one()->transactionpurposeid;
                    $transaction->transactionpurposeid = $purpose;
                    $transaction->receiptnumber = Transaction::generateReceiptNumber($transaction->paydate);
                
                    if ($transaction->save() == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction record');
                        $transaction_unit->rollBack();
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                    
                    $transaction_unit->commit();
                    return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]);    //doesn't work
                
                } catch (Exception $ex) {
                    $transaction_unit->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
            else 
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load transaction. Please try again.');  
            }
        }
        
        return $this->render('create_full_payment', [
            'transaction' => $transaction,
            'id' => $personid,
            'status' => $status,
            'name' => $name,
            'username' => $username,
        ]); 
    }
    
    
    /**
     * Create part-payment transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/01/2017
     * Date Last Modified: 18/01/2017
     */
    public function actionCreatePartPayment($personid, $status)
    {
        $applicant = Applicant::find()->where(['personid' => $personid, 'isdeleted' => 0])->one();
        $name = $applicant->firstname . " " . $applicant->lastname;
        $username = User::find()->where(['personid' => $personid, 'isdeleted' => 0])->one()->username;
                
        $transaction = new Transaction();
        $transaction->transactiontypeid = 2;
        $transaction->paymentmethodid = 1;
        $transaction->paydate = date('Y-m-d');

        if ($post_data = Yii::$app->request->post()) 
        {
            $transaction_load_flag = $transaction->load($post_data);
            
            if($transaction_load_flag == true)
            {
                $transaction_unit = \Yii::$app->db->beginTransaction();
                try 
                {
                    $transaction_summary = new TransactionSummary();
                    $transaction_summary->balance = $transaction->totaldue - $transaction->paymentamount;
                    $transaction_summary->totalpaid = $transaction->paymentamount;
                    if ($transaction_summary->save() == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record');
                        $transaction_unit->rollBack();
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                    
                    $transaction->personid = $personid;
                    $transaction->recepientid = Yii::$app->user->getId();
                    $transaction->transactionsummaryid = $transaction_summary->transactionsummaryid;
                    $purpose = TransactionItem::find()->where(['transactionitemid' => $transaction->transactionitemid, 'isactive' => 1, 'isdeleted' => 0])->one()->transactionpurposeid;
                    $transaction->transactionpurposeid = $purpose;
                    $transaction->receiptnumber = Transaction::generateReceiptNumber($transaction->paydate);
                
                    if ($transaction->save() == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction record');
                        $transaction_unit->rollBack();
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                    
                    $transaction_unit->commit();
                    return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]);   
                
                } catch (Exception $ex) {
                    $transaction_unit->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
            else 
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load transaction. Please try again.');  
            }
        }
        
        return $this->render('create_part_payment', [
            'transaction' => $transaction,
            'id' => $personid,
            'status' => $status,
            'name' => $name,
            'username' => $username,
        ]);
    }

    
     /**
     * Pay on outstanding charge payment transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 17/01/2017
     * Date Last Modified: 17/01/2017
     */
    public function actionPayOutstanding($personid, $status, $summaryid)
    {
        
        $applicant = Applicant::find()->where(['personid' => $personid, 'isdeleted' => 0])->one();
        $name = $applicant->firstname . " " . $applicant->lastname;
        $username = User::find()->where(['personid' => $personid, 'isdeleted' => 0])->one()->username;
                
        $transaction = new Transaction();
        $transaction->transactiontypeid = 2;
        $transaction->paymentmethodid = 1;
        $transaction->paydate = date('Y-m-d');
        
        $initial_transaction = Transaction::getInitialPayment($summaryid);
        $transaction_summary = TransactionSummary::find()
                             ->where(['transactionsummaryid' => $summaryid, 'isactive' => 1, 'isdeleted' => 0])
                             ->one();
        
        $transaction->transactionitemid = $initial_transaction->transactionitemid;
        $transaction->totaldue = $transaction_summary->balance;

        if ($post_data = Yii::$app->request->post()) 
        {
            $transaction_load_flag = $transaction->load($post_data);
            
            if($transaction_load_flag == true)
            {
                $transaction_unit = \Yii::$app->db->beginTransaction();
                try 
                {
                    $transaction->totaldue = $transaction_summary->balance;
                            
                    $transaction_summary->totalpaid += $transaction->paymentamount;
                    $transaction_summary->balance -= $transaction->paymentamount;
                    if ($transaction_summary->totalpaid > $initial_transaction->totaldue  || $transaction_summary->balance < 0)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error. Amount paid exceeds current outstanding balance');
                    }
                    else
                    {
                        if ($transaction_summary->save() == false)
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record');
                            $transaction_unit->rollBack();
                            return $this->redirect(\Yii::$app->request->getReferrer());
                        }

                        $transaction->transactionitemid = $initial_transaction->transactionitemid;
                        $transaction->transactiontypeid = 2;
                        $transaction->personid = $personid;
                        $transaction->transactionpurposeid = $initial_transaction->transactionpurposeid;
                        $transaction->recepientid = Yii::$app->user->getId();
                        $transaction->semesterid = $initial_transaction->semesterid;
                        $transaction->transactionsummaryid = $summaryid;
                        $transaction->receiptnumber = Transaction::generateReceiptNumber($transaction->paydate);

                        if ($transaction->save() == false)
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction record');
                            $transaction_unit->rollBack();
                            return $this->redirect(\Yii::$app->request->getReferrer());
                        }

                        $transaction_unit->commit();
                        return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]);   
                    }
                } catch (Exception $ex) {
                    $transaction_unit->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
            else 
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load transaction. Please try again.');  
            }
        }
        
         return $this->render('pay_outstanding', [
            'transaction' => $transaction,
            'id' => $personid,
            'status' => $status,
            'name' => $name,
            'username' => $username,
        ]);
    }
        
    
    /**
     * Edit transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 18/01/2017
     * Date Last Modified: 18/01/2017 | 19/01/2017
     */
    public function actionEditTransaction($transactionid, $personid, $status, $receiptnumber)
    {
        $applicant = Applicant::find()->where(['personid' => $personid, 'isdeleted' => 0])->one();
        $name = $applicant->firstname . " " . $applicant->lastname;
        $username = User::find()->where(['personid' => $personid, 'isdeleted' => 0])->one()->username;
                
        $transaction = Transaction::find()
                ->where(['transactionid' => $transactionid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        $all_related_transactions = Transaction::find()
                ->where(['transactionsummaryid' => $transaction->transactionsummaryid, 'isactive' => 1, 'isdeleted' => 0])
                ->orderBy('transactionid ASC')
                ->all();

        if ($post_data = Yii::$app->request->post()) 
        {
            //if part-time payment
            if ($transaction->transactiontypeid == 2)
            {
                 $transaction_summary = TransactionSummary::find()
                     ->where(['transactionsummaryid' => $transaction->transactionsummaryid, 'isactive' => 1, 'isdeleted' => 0])
                     ->one();
                 
                $last_total_due = $transaction->totaldue;
                $last_amount_paid = $transaction->paymentamount;

                $transaction_load_flag = $transaction->load($post_data);
            
                if($transaction_load_flag == true)
                {
                    $transaction_unit = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        // if part time transaction has no subsequent payments yet
                        if(count($all_related_transactions) == 1) 
                        {
                            $transaction_summary->balance = $transaction->totaldue - $transaction->paymentamount;
                            $transaction_summary->totalpaid = $transaction->paymentamount;
                            if ($transaction_summary->save() == false)
                            {
                                Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record');
                                $transaction_unit->rollBack();
                                return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]);   
                            }
                            
                            if ($transaction->save() == false)
                            {
                                Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction record');
                                $transaction_unit->rollBack();
                                return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]);   
                            }
                            $transaction_unit->commit();
                            return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]);      
                        }
                        
                        else        //if multiple installments exist
                        {
                            $diff = $last_amount_paid - $transaction->paymentamount;
                            $abs_diff = abs($diff);
                            $diff_status = 0;
                            if ($diff == 0) //if no change
                            {
                                 return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]); 
                            }
                            elseif ($diff < 0)  //amount paid was increased
                            {
                                $diff_status = 1;

                            }
                            elseif ($diff > 0)  //amount paid was reduced
                            {
                                $diff_status = -1;
                            }

                            if ($diff_status == -1)     //amount paid was reduced
                            {
                                $transaction_summary->balance += $abs_diff;
                                $transaction_summary->totalpaid -= $abs_diff;
                            }
                            elseif ($diff_status == 1)      // amount paid was increased
                            {
                                $transaction_summary->balance -= $abs_diff;
                                $transaction_summary->totalpaid += $abs_diff;
                                if ($transaction_summary->balance < 0 )
                                {
                                    Yii::$app->getSession()->setFlash('error', 'Error. Amount paid exceeds current outstanding balance');
                                    $transaction_unit->rollBack();
                                    return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]); 
                                }
                            }
                                
                            if ($transaction_summary->save() == false)
                            {
                                Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record');
                                $transaction_unit->rollBack();
                                return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]); 
                            }

                            $transaction_flag = true;
                            foreach ($all_related_transactions as $tran)
                            {
                                if ($tran->transactionid == $transactionid)
                                {
                                    $tran->paymentamount  = $transaction->paymentamount;
                                    if ($tran->save() == false)
                                    {
                                        $transaction_flag = false;
                                    }
                                }
                                elseif ($tran->transactionid > $transactionid)
                                {
                                    if ($diff_status == 1)     //amount paid was increased
                                    {
                                         $tran->totaldue  -= $abs_diff;
                                    }
                                    elseif($diff_status == -1)     //amount paid was reduced
                                    {
                                         $tran->totaldue  += $abs_diff;
                                    }
                                    if ($tran->save() == false)
                                    {
                                        $transaction_flag = false;
                                    }
                                }
                            }

                            if ($transaction_flag == false)
                            {
                                Yii::$app->getSession()->setFlash('error', 'Error occured updating transaction.');
                                $transaction_unit->rollBack();
                                return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]);   
                            }

                            $transaction_unit->commit();
                            return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]); 
                        }
                    } catch (Exception $ex) {
                        $transaction_unit->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                        return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]); 
                    }
                }
                else 
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load transaction. Please try again.');  
                    return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]); 
                }
            }
            
            
            // if full-payment
            elseif ($transaction->transactiontypeid == 1)
            {
                $transaction_load_flag = $transaction->load($post_data);

                if($transaction_load_flag == true)
                {
                    $transaction_unit = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        $transaction_summary = TransactionSummary::find()
                             ->where(['transactionsummaryid' => $transaction->transactionsummaryid, 'isactive' => 1, 'isdeleted' => 0])
                             ->one();
                        $transaction_summary->balance = 0;
                        $transaction_summary->totalpaid = $transaction->paymentamount;
                        if ($transaction_summary->save() == false)
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record');
                            $transaction_unit->rollBack();
                            return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]);
                        }

                        $transaction->totaldue = $transaction->paymentamount;
                        if ($transaction->save() == false)
                        {
                            Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction record');
                            $transaction_unit->rollBack();
                            return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]);
                        }

                        $transaction_unit->commit();
                        return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]); 
                     } catch (Exception $ex) {
                        $transaction_unit->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                        return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]);
                    }
                }
                else 
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load transaction. Please try again.');
                    return Yii::$app->runAction('subcomponents/payments/payments/get-transaction-receipt', ['receiptnumber'=> $receiptnumber, 'status'=> $status]);
                }
            }
        }
        
        return $this->render('edit_payment', [
            'all_related_transactions' => $all_related_transactions,
            'transaction' => $transaction,
            'id' => $personid,
            'status' => $status,
            'name' => $name,
            'username' => $username,
        ]); 
    }
    
    
        /**
     * Create part-payment transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 19/01/2017
     * Date Last Modified: 19/01/2017
     */
    public function actionCreateMultiplePayments($personid, $status, $count)
    {
        $transactions = array();
        for ($i = 0; $i < $count ; $i++)
        {
            $record = new Transaction();
            $record->paymentmethodid = 1;
            $record->paydate = date('Y-m-d');
            $transactions[] = $record;
        }
        
        $applicant = Applicant::find()->where(['personid' => $personid, 'isdeleted' => 0])->one();
        $name = $applicant->firstname . " " . $applicant->lastname;
        $username = User::find()->where(['personid' => $personid, 'isdeleted' => 0])->one()->username;
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = Model::loadMultiple($transactions, $post_data);
            if($load_flag == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error loading records.');
            }
            else
            {
                $transaction_unit = \Yii::$app->db->beginTransaction();
                try 
                {
                    $transaction_save_flag = true;
                    $transaction_summary_save_flag = true;

                    $receiptnumber = NULL;
                    foreach ($transactions as $transaction)
                    {
                        //if full payment
                        if ($transaction->transactiontypeid == 1)
                        {
                            $transaction_summary = new TransactionSummary();
                            $transaction_summary->balance = 0;
                            $transaction_summary->totalpaid = $transaction->paymentamount;
                            if ($transaction_summary->save() == false)
                            {
                                $transaction_summary_save_flag = false;
                                break;
                            }

                            $transaction->personid = $personid;
                            $transaction->recepientid = Yii::$app->user->getId();
                            $transaction->totaldue = $transaction->paymentamount;
                            $transaction->transactionsummaryid = $transaction_summary->transactionsummaryid;
                            $purpose = TransactionItem::find()->where(['transactionitemid' => $transaction->transactionitemid, 'isactive' => 1, 'isdeleted' => 0])->one()->transactionpurposeid;
                            $transaction->transactionpurposeid = $purpose;
                            $transaction->receiptnumber = ($receiptnumber == NULL) ? Transaction::generateReceiptNumber($transaction->paydate): $receiptnumber;
                            if ($transaction->save() == false)
                            {
                                $transaction_save_flag = false;
                                break;
                            }
                        }

                        //if partial payment
                        elseif ($transaction->transactiontypeid == 2)
                        {
                            $transaction_summary = new TransactionSummary();
                            $transaction_summary->balance = $transaction->totaldue - $transaction->paymentamount;
                            $transaction_summary->totalpaid = $transaction->paymentamount;
                            if ($transaction_summary->save() == false)
                            {
                                $transaction_summary_save_flag = false;
                                break;
                            }

                            $transaction->personid = $personid;
                            $transaction->recepientid = Yii::$app->user->getId();
                            $transaction->transactionsummaryid = $transaction_summary->transactionsummaryid;
                            $purpose = TransactionItem::find()->where(['transactionitemid' => $transaction->transactionitemid, 'isactive' => 1, 'isdeleted' => 0])->one()->transactionpurposeid;
                            $transaction->transactionpurposeid = $purpose;
                            $transaction->receiptnumber = ($receiptnumber == NULL) ? Transaction::generateReceiptNumber($transaction->paydate): $receiptnumber;
                            if ($transaction->save() == false)
                            {
                                $transaction_save_flag = false;
                                break;
                            }
                        }
                    }           

                    if ($transaction_summary_save_flag == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record.');
                        $transaction_unit->rollBack();
                    }
                    elseif ($transaction_save_flag == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction records');
                        $transaction_unit->rollBack();
                    }
                    else
                    {
                        $transaction_unit->commit();
                        return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]); 
                    }
                 } catch (Exception $ex) {
                    $transaction_unit->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
        }
        
        return $this->render('create_multiple_payments', [
            'transactions' => $transactions,
            'id' => $personid,
            'status' => $status,
            'name' => $name,
            'username' => $username,
            'count' => $count,
        ]);
    }
    

    /**
     * Soft Deletes a transaction
     * If full time transaction -> both transaction and transaction model are removed
     * If part time transaction -> only transaction record is removed and transationsummary's toaldue and balanace are updated
     * 
     * @param type $transactionid
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 20/1/2017
     * Date Last Modified: 20/01/2017
     */
    public function actionDeleteTransaction($personid, $status, $transactionid)
    {
         $transaction = Transaction::find()
                ->where(['transactionid' => $transactionid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
         
         $transaction_summary = TransactionSummary::find()
                 ->where(['transactionsummaryid' => $transaction->transactionsummaryid, 'isactive' => 1, 'isdeleted' => 0])
                 ->one();
         
         //if full payment
         if ($transaction->transactiontypeid == 1)
         {
            $transaction_unit = \Yii::$app->db->beginTransaction();
            try 
            {
                $transaction->isactive = 0;
                $transaction->isdeleted = 1;
                if ($transaction->save() == false)
                {
                     $transaction_unit->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting transaction records');
                }
                else
                {
                    $transaction_summary->isactive = 0;
                    $transaction_summary->isdeleted = 1;
                    if ($transaction_summary->save() == false)
                    {
                        $transaction_unit->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured deleting transaction summary records');
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('success', 'Transaction removal was successful.');
                        $transaction_unit->commit();
                    }
                }
             } catch (Exception $ex) {
                $transaction_unit->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
            }
         }
         
         //if partial pyment
         elseif ($transaction->transactiontypeid == 2)
         {
            $transaction_unit = \Yii::$app->db->beginTransaction();
            try 
            {
                $transaction->isactive = 0;
                $transaction->isdeleted = 1;
                if ($transaction->save() == false)
                {
                     $transaction_unit->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting transaction records');
                }
                else
                {
                    $transaction_summary->balance += $transaction->paymentamount;
                    $transaction_summary->totalpaid -= $transaction->paymentamount;
                    if ($transaction_summary->save() == false)
                    {
                        $transaction_unit->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Error occured deleting transaction summary records');
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('success', 'Transaction removal was successful.');
                        $transaction_unit->commit();
                    }
                }
             } catch (Exception $ex) {
                $transaction_unit->rollBack();
                Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
            }
         }
         
         return Yii::$app->runAction('subcomponents/payments/payments/view-user-transactions', ['id'=> $personid, 'status'=> $status]); 
    }
            
    
    
    
    
    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    
    
}
