<?php

    namespace app\subcomponents\payments\controllers;

    use Yii;
    use frontend\models\TransactionType;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;

    use frontend\models\Employee;

    /**
     * TransactionTypeController implements the CRUD actions for TransactionType model.
     */
    class TransactionTypeController extends Controller
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
         * Lists all TransactionType models.
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

            $types = TransactionType::find()
                    ->where(['isdeleted' => 0])
                    ->all();

            foreach ($types as $type)
            {
                $info = array();
                $info['id'] = $type->transactiontypeid;
                $info['name'] = $type->name;
                $info['createdby'] = Employee::getEmployeeName($type->createdby);
                $lastmodifiedby = Employee::getEmployeeName($type->lastmodifiedby);
                $info['lastmodifiedby'] = ($lastmodifiedby)? $lastmodifiedby: "N/A";
                $info['active'] = $type->isactive;
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
         * Displays a single TransactionType model.
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
            if (Yii::$app->user->can('viewTransactionType'))
            {
                return $this->render('view', [
                    'transaction_type' => $this->findModel($id),
                ]);
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to view this transaction type record.');
                return $this->redirect(['index']); 
            }
        }


        /**
         * Create and update  TransactionType model
         * 
         * @param type $action
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/10/2016
         * Date Last Modified: 06/10/2016
         */
        public function actionConfigureTransactionType($action, $id = NULL)
        {
            $load_flag = false;
            $save_flag = false;

            if ($action == "create")
            {
                if (Yii::$app->user->can('createTransactionType'))
                {
                    $transaction_type = new TransactionType();
                    $action = "Create";
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'You are not authorized to create a new transaction type  record.');
                     return $this->redirect(['index']); 
                 }
            }
            elseif ($action == "update")
            {
                if (Yii::$app->user->can('updateTransactionType'))
                {
                    $transaction_type = TransactionType::find()
                            ->where(['transactiontypeid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $action = "Update";
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'You are not authorized to update this transaction type record.');
                    return $this->redirect(['index']); 
                 }
            }

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = $transaction_type->load($post_data);
                $save_flag = $transaction_type->save();
                if ($save_flag == true)
                    return self::actionIndex();
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving record. Please try again.');
                }
            }

            return $this->render('create_update',
                    [
                        'transaction_type' => $transaction_type,
                        'action' => $action,
                    ]);
        }


        /**
         * Deactivates/Activates an existing TransactionType model.
         * 
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 06/10/2016
         * Date Last Modified: 06/10/2016
         */
        public function actionToggle($id)
        {
            if (Yii::$app->user->can('toggleTransactionType') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to activate/deactivate this transaction type record.');
                return $this->redirect(['index']); 
            }

            $record = TransactionType::find()
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

            $save_flag = $record->save();
            if ($save_flag == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured saving record');
                return $this->redirect(['index']); 
            }
            return $this->redirect(['index']);
        }


        /**
         * Deletes an existing TransactionType model.
         * 
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 05/10/2016
         * Date Last Modified: 05/10/2016
         */
        public function actionDelete($id)
        {
            if (Yii::$app->user->can('deleteTransactionType') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to delete this transaction type record.');
                return $this->redirect(['index']); 
            }

            if (TransactionType::transactionTypeRecorded($id) == true)
            {
                Yii::$app->getSession()->setFlash('error', 'Transaction type could not be deleted as it is associated with at least one Transaction record.');
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
         * Finds the TransactionType model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return TransactionType the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = TransactionType::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
