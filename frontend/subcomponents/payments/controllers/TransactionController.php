<?php

namespace app\subcomponents\payments\controllers;

use Yii;
use yii\helpers\Url;
use frontend\models\Transaction;
use frontend\models\TransactionSummary;
use frontend\models\TransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($transactionsummaryid, $payee_id)
    {
        $model = new Transaction();

        if ($model->load(Yii::$app->request->post()) ) 
        {
            $request = Yii::$app->request;
            $model->personid = $request->post('payee_id');
            $model->recepientid = Yii::$app->user->getId();
            $model->transactionsummaryid = $request->post('transactionsummaryid');
            $model->receiptnumber = PaymentsController::getReceiptNumber();

            if ($model->save())
            {
                return $this->redirect(Url::to(['payments/view-transactions',
                    'transactionsummaryid' => $model->transactionsummaryid
                ]));
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'payee_id' => $payee_id,
            'transactionsummaryid' => $transactionsummaryid,
        ]); 
    }
    

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->transactionid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
    public function actionCreateFullPayment($id)
    {
        $transaction = new Transaction();

        if ($post_data = Yii::$app->request->post()) 
        {
            $transaction_load_flag = $transaction->load($post_data);
            
            if($transaction_load_flag == true)
            {
                $transaction = \Yii::$app->db->beginTransaction();
                try 
                {
                    $transaction_summary = new TransactionSummary();
                    $transaction_summary->balance = 0;
                    $transaction_summary->totalpaid = $transaction->paymentamount;
                    if ($transaction_summary->save() == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction summary record');
                        $transaction->rollBack();
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                    
                    $transaction->personid = $id;
                    $transaction->recepientid = Yii::$app->user->getId();
                    $transaction->transactionsummaryid = $transaction_summary->transactionsummaryid;
                    $transaction->receiptnumber = PaymentsController::getReceiptNumber();
                
                    if ($transaction>save() == false)
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving transaction record');
                        $transaction->rollBack();
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    }
                    
                    $transaction->commit();
                } catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
            else 
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load transaction. Please try again.');  
            }
        }
        
        return $this->render('create-full-payment', [
            'transaction' => $transaction,
            'id' => $id,
        ]); 
    }
    
    
    
    
    
    
    /**
     * Create part-payment transaction
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 17/01/2017
     * Date Last Modified: 17/01/2017
     */
    public function actionCreatePartPayment($id)
    {
        
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
