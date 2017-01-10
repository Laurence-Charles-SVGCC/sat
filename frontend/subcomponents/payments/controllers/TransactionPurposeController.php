<?php

    namespace app\subcomponents\payments\controllers;

    use Yii;
    use frontend\models\TransactionPurpose;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;

    use frontend\models\Employee;


    /**
    * TransactionPurposeController implements the CRUD actions for TransactionPurpose model.
    */
   class TransactionPurposeController extends Controller
   {

       /**
        * Lists all TransactionPurpose models.
        * 
        * @return mixed
        * 
        * Author: Laurence Charles
        * Date Created: 07/10/2016
        * Date Last Modified: 07/10/2016
        */
       public function actionIndex()
       {
           $dataProvider = NULL;
           $data = array();

           $purposes = TransactionPurpose::find()
                   ->where(['isdeleted' => 0])
                   ->all();

           foreach ($purposes as $purpose)
           {
               $info = array();
               $info['id'] = $purpose->transactionpurposeid;
               $info['name'] = $purpose->name;
               $info['description'] = $purpose->description;
               $info['createdby'] = Employee::getEmployeeName($purpose->createdby);
               $lastmodifiedby = Employee::getEmployeeName($purpose->lastmodifiedby);
               $info['lastmodifiedby'] = ($lastmodifiedby)? $lastmodifiedby: "N/A";
               $info['active'] = $purpose->isactive;
               $data[] = $info;
           }

           $dataProvider = new ArrayDataProvider([
               'allModels' => $data,
               'pagination' => [
                   'pageSize' => 20,
               ],
                'sort' => [
                   'defaultOrder' => ['name' => SORT_ASC],
                   'attributes' => ['name', 'createdby', 'lastmodifiedby'],
               ],
           ]);

           return $this->render('index', [
               'dataProvider' => $dataProvider,
           ]);
       }

       
       /**
        * Displays a single TransactionPurpose model.
        * 
        * @param string $id
        * @return mixed
        * 
        * Author: Laurence Charles
        * Date Created: 07/10/2016
        * Date Last Modified: 07/10/2016
        */
       public function actionView($id)
       {
           if (Yii::$app->user->can('viewTransactionPurpose'))
           {
               return $this->render('view', [
                   'transaction_purpose' => $this->findModel($id),
               ]);
           }
           else
           {
               Yii::$app->getSession()->setFlash('error', 'You are not authorized to view this transaction purpose record.');
               return $this->redirect(['index']); 
           }
       }


       /**
        * Create and update  TransactionPurpose model
        * 
        * @param type $action
        * @param type $recordid
        * @return type
        * 
        * Author: Laurence Charles
        * Date Created: 06/10/2016
        * Date Last Modified: 06/10/2016
        */
       public function actionConfigureTransactionPurpose($action, $id = NULL)
       {
           $load_flag = false;
           $save_flag = false;

           if ($action == "create")
           {
               if (Yii::$app->user->can('createTransactionPurpose'))
               {
                   $transaction_purpose = new TransactionPurpose();
                   $operation = "Create";
               }
               else
               {
                   Yii::$app->getSession()->setFlash('error', 'You are not authorized to create a new transaction purpose  record.');
                    return $this->redirect(['index']); 
                }
           }
           elseif ($action == "update")
           {
               if (Yii::$app->user->can('updateTransactionPurpose'))
               {
                   $transaction_purpose = TransactionPurpose::find()
                           ->where(['transactionpurposeid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                           ->one();
                   $operation = "Update";
               }
               else
               {
                   Yii::$app->getSession()->setFlash('error', 'You are not authorized to update this transaction purpose record.');
                   return $this->redirect(['index']); 
                }
           }

           if ($post_data = Yii::$app->request->post())
           {
               $load_flag = $transaction_purpose->load($post_data);
               
               if ($action == "create")
                {
                    $transaction_purpose->createdby = Yii::$app->user->identity->personid;
                }
                elseif ($action == "update")
                {
                    $transaction_purpose->lastmodifiedby = Yii::$app->user->identity->personid;
                }
                
               $save_flag = $transaction_purpose->save();
               if ($save_flag == true)
                   return self::actionIndex();
               else
               {
                   Yii::$app->getSession()->setFlash('error', 'Error occured when saving record. Please try again.');
               }
           }

           return $this->render('create_update',
                   [
                       'transaction_purpose' => $transaction_purpose,
                       'operation' => $operation,
                   ]);
       }


       /**
        * Deactivates/Activates an existing TransactionPurpose model.
        * 
        * @param string $id
        * @return mixed
        * 
        * Author: Laurence Charles
        * Date Created: 07/10/2016
        * Date Last Modified: 07/10/2016
        */
       public function actionToggle($id)
       {
           if (Yii::$app->user->can('toggleTransactionPurpose') == false)
           {
               Yii::$app->getSession()->setFlash('error', 'You are not authorized to activate/deactivate this transaction purpose record.');
               return $this->redirect(['index']); 
           }

           $record = TransactionPurpose::find()
                   ->where(['transactionpurposeid' => $id, 'isdeleted' => 0])
                   ->one();

           if ($record == false)
           {
               Yii::$app->getSession()->setFlash('error', 'Error retrieving record');
               return $this->redirect(['index']);
           }

           if ($record->isactive == 1)     
           {
               $record->isactive = 0;              //deactivate
           }
           elseif ($record->isactive == 0)     
           {
               $record->isactive = 1;              //reactivate
           }

           $save_flag = $record->save();
           if ($save_flag == false)
           {
               Yii::$app->getSession()->setFlash('error', 'Error occured saving record');
               return $this->redirect(['index']); 
           }
           return $this->redirect(['index']);
       }


       /**
        * Deletes an existing TransactionPurpose model.
        * 
        * If deletion is successful, the browser will be redirected to the 'index' page.
        * @param string $id
        * @return mixed
        * 
        * Author: Laurence Charles
        * Date Created: 07/10/2016
        * Date Last Modified: 07/10/2016
        */
       public function actionDelete($id)
       {
           if (Yii::$app->user->can('deleteTransactionPurpose') == false)
           {
               Yii::$app->getSession()->setFlash('error', 'You are not authorized to delete this transaction purpose record.');
               return $this->redirect(['index']); 
           }

           if (TransactionPurpose::transactionPurposeRecorded($id) == true)
           {
               Yii::$app->getSession()->setFlash('error', 'Transaction purpose could not be deleted as it is associated with at least one Transaction record.');
               return $this->redirect(['index']);
           }

           $record = TransactionType::find()
                   ->where(['transactiontypeid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                   ->one();

           if ($record == false)
           {
               Yii::$app->getSession()->setFlash('error', 'Error retrieving record');
               return $this->redirect(['index']);
           }

           $record->isactive = 0;
           $record->isdeleted = 1;
           $save_flag = $record->save();
           if ($save_flag == false)
           {
               Yii::$app->getSession()->setFlash('error', 'Error occured saving record');
               return $this->redirect(['index']); 
           }
           return $this->redirect(['index']);
       }


       /**
        * Finds the TransactionPurpose model based on its primary key value.
        * If the model is not found, a 404 HTTP exception will be thrown.
        * @param string $id
        * @return TransactionPurpose the loaded model
        * @throws NotFoundHttpException if the model cannot be found
        */
       protected function findModel($id)
       {
           if (($model = TransactionPurpose::findOne($id)) !== null) {
               return $model;
           } else {
               throw new NotFoundHttpException('The requested page does not exist.');
           }
       }
   }
