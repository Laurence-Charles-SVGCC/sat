<?php

    namespace app\subcomponents\payments\controllers;

    use Yii;
    use frontend\models\TransactionItem;
    use frontend\models\TransactionPurpose;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;

    use frontend\models\Employee;

    /**
     * TransactionItemController implements the CRUD actions for TransactionItem model.
     */
    class TransactionItemController extends Controller
    {
        /**
         * Lists all TransactionItem models.
         * 
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 11/01/2017
         * Date Last Modified: 11/01/2017
         */
        public function actionIndex()
        {
            $dataProvider = NULL;
            $data = array();

            $items = TransactionItem::find()
                    ->where(['isdeleted' => 0])
                    ->all();

            foreach ($items as $item)
            {
                $info = array();
                $info['id'] = $item->transactionitemid;
                $info['name'] = $item->name;
                $info['createdby'] = Employee::getEmployeeName($item->createdby);
                $lastmodifiedby = Employee::getEmployeeName($item->lastmodifiedby);
                $info['lastmodifiedby'] = ($lastmodifiedby)? $lastmodifiedby: "N/A";
                $info['purpose'] = TransactionPurpose::find()->where(['transactionpurposeid' => $item->transactionpurposeid, 'isdeleted' => 0])->one()->name;
                $info['active'] = $item->isactive;
                $data[] = $info;
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 20,
                ],
                 'sort' => [
                    'defaultOrder' => ['name' => SORT_ASC],
                    'attributes' => ['name', 'purpose', 'createdby', 'lastmodifiedby'],
                ],
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }


        /**
         * Displays a single TransactionItem model.
         * 
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 11/01/2017
         * Date Last Modified: 11/01/2017
         */
        public function actionView($id)
        {
            if (Yii::$app->user->can('viewTransactionItem'))
            {
                return $this->render('view', [
                    'transaction_item' => $this->findModel($id),
                ]);
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to view this transaction item record.');
                return $this->redirect(['index']); 
            }
        }


        /**
         * Create and update  TransactionItem model
         * 
         * @param type $action
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 11/01/2017
         * Date Last Modified: 11/01/2017
         */
        public function actionConfigureTransactionItem($action, $id = NULL)
        {
            $load_flag = false;
            $save_flag = false;

            if ($action == "create")
            {
                if (Yii::$app->user->can('createTransactionItem'))
                {
                    $transaction_item = new TransactionItem();
                    $operation = "Create";
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'You are not authorized to create a new transaction item  record.');
                     return $this->redirect(['index']); 
                 }
            }
            elseif ($action == "update")
            {
                if (Yii::$app->user->can('updateTransactionItem'))
                {
                    $transaction_item = TransactionItem::find()
                            ->where(['transactionitemid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $operation = "Update";
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'You are not authorized to update this transaction item record.');
                    return $this->redirect(['index']); 
                 }
            }

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = $transaction_item->load($post_data);
                
                 if ($action == "create")
                {
                    $transaction_item->createdby = Yii::$app->user->identity->personid;
                }
                elseif ($action == "update")
                {
                    $transaction_item->lastmodifiedby = Yii::$app->user->identity->personid;
                }
                
                $save_flag = $transaction_item->save();
                if ($save_flag == true)
                    return self::actionIndex();
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving record. Please try again.');
                }
            }

            return $this->render('create_update',
                    [
                        'transaction_item' => $transaction_item,
                        'operation' => $operation,
                    ]);
        }


        /**
         * Deactivates/Activates an existing TransactionItem model.
         * 
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 11/01/2017
         * Date Last Modified: 11/01/2017
         */
        public function actionToggle($id)
        {
            if (Yii::$app->user->can('toggleTransactionItem') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to activate/deactivate this transaction item record.');
                return $this->redirect(['index']); 
            }

            $record = TransactionItem::find()
                    ->where(['tansactiontypeid' => $id, 'isdeleted' => 0])
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
            
            $transaction_item->lastmodifiedby = Yii::$app->user->identity->personid;
            $save_flag = $record->save();
            if ($save_flag == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured saving record');
                return $this->redirect(['index']); 
            }
            return $this->redirect(['index']);
        }


        /**
         * Deletes an existing TransactionItem model.
         * 
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 11/01/2017
         * Date Last Modified: 11/01/2017
         */
        public function actionDelete($id)
        {
            if (Yii::$app->user->can('deleteTransactionItem') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to delete this transaction item record.');
                return $this->redirect(['index']); 
            }

            if (TransactionItem::transactionItemRecorded($id) == true)
            {
                Yii::$app->getSession()->setFlash('error', 'Transaction type could not be deleted as it is associated with at least one Transaction record.');
                return $this->redirect(['index']);
            }

            $record = TransactionItem::find()
                    ->where(['transactionitemid' => $id, 'isactive' => 1, 'isdeleted' => 0])
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
         * Finds the TransactionItem model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return TransactionItem the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = TransactionItem::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
