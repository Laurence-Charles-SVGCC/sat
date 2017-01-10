<?php

    namespace app\subcomponents\payments\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;
    use yii\web\NotFoundHttpException;
    
    use frontend\models\PaymentMethod;
    use frontend\models\PaymentMethodSearch;
    use frontend\models\Employee;

    /**
     * PaymentMethodController implements the CRUD actions for PaymentMethod model.
     */
    class PaymentMethodController extends Controller
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
         * Lists all PaymentMethod models.
         * 
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 05/10/2016
         * Date Last Modified: 05/10/2016
         */
        public function actionIndex()
        {
            $dataProvider = NULL;
            $data = array();
            
            $methods =PaymentMethod::find()
                    ->where(['isdeleted' => 0])
                    ->all();
            
            foreach ($methods as $method)
            {
                $info = array();
                $info['id'] = $method->paymentmethodid;
                $info['name'] = $method->name;
                $info['createdby'] = Employee::getEmployeeName($method->createdby);
                $lastmodifiedby = Employee::getEmployeeName($method->lastmodifiedby);
                $info['lastmodifiedby'] = ($lastmodifiedby)? $lastmodifiedby: "N/A";
                $info['active'] = $method->isactive;
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
         * Displays a single PaymentMethod model.
         * 
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 06/10/2016
         * Date Last Modified: 06/10/2016
         */
        public function actionView($id)
        {
            if (Yii::$app->user->can('viewPaymentMethod'))
            {
                return $this->render('view', [
                    'payment_method' => $this->findModel($id),
                ]);
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to view this payment method record.');
                return $this->redirect(['index']); 
            }
        }

        
        /**
         * Create and update  PaymentMethod model
         * 
         * @param type $action
         * @param type $recordid
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 06/10/2016
         * Date Last Modified: 06/10/2016
         */
        public function actionConfigurePaymentMethod($action, $id = NULL)
        {
            $load_flag = false;
            $save_flag = false;
        
            if ($action == "create")
            {
                 if (Yii::$app->user->can('createPaymentMethod'))
                 {
                      $payment_method = new PaymentMethod();
                      $operation = "Create";
                 }
                 else
                 {
                     Yii::$app->getSession()->setFlash('error', 'You are not authorized to create a new payment method record.');
                     return $this->redirect(['index']); 
                 }
            }
            elseif ($action == "update")
            {
                if (Yii::$app->user->can('updatePaymentMethod'))
                {
                    $payment_method = PaymentMethod::find()
                        ->where(['paymentmethodid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                    $operation= "Update";
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'You are not authorized to update this payment method record.');
                    return $this->redirect(['index']); 
                 }
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = $payment_method->load($post_data);
                
                if ($action == "create")
                {
                    $payment_method->createdby = Yii::$app->user->identity->personid;
                }
                elseif ($action == "update")
                {
                    $payment_method->lastmodifiedby = Yii::$app->user->identity->personid;
                }
                
                $save_flag = $payment_method->save();
                if ($save_flag == true)
                    return self::actionIndex();
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving record. Please try again.');
                }
            }
            
            return $this->render('create_update',
                    [
                        'payment_method' => $payment_method,
                        'operation' => $operation,
                    ]);
        }

        
        /**
         * Deactivates/Activates an existing PaymentMethod model.
         * 
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 05/10/2016
         * Date Last Modified: 05/10/2016
         */
        public function actionToggle($id)
        {
            if (Yii::$app->user->can('togglePaymentMethod') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to activate/deactivate this payment method record.');
                return $this->redirect(['index']); 
            }
            
            $method = PaymentMethod::find()
                    ->where(['paymentmethodid' => $id, 'isdeleted' => 0])
                    ->one();
            
            if ($method == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error retriving record');
                return $this->redirect(['index']);
            }
            
            if ($method->isactive == 1)     
            {
                $method->isactive = 0;              //deactivate
            }
            elseif ($method->isactive == 0)     
            {
                $method->isactive = 1;              //reactivate
            }
            
            $save_flag = $method->save();
            if ($save_flag == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured saving record');
                return $this->redirect(['index']); 
            }
            return $this->redirect(['index']);
        }
        
        
        /**
         * Deletes an existing PaymentMethod model.
         * 
         * @param string $id
         * @return mixed
         * 
         * Author: Laurence Charles
         * Date Created: 05/10/2016
         * Date Last Modified: 05/10/2016
         */
        public function actionDelete($id)
        {
            if (Yii::$app->user->can('deletePaymentMethod') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to delete this payment method record.');
                return $this->redirect(['index']); 
            }
            
            if (PaymentMethod::paymentMethodRecorded($id) == true)
            {
                Yii::$app->getSession()->setFlash('error', 'Payment method could not be deleted as it is associated with at least one Transactio record.');
                return $this->redirect(['index']);
            }
            
            $method = PaymentMethod::find()
                    ->where(['paymentmethodid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            if ($method == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error retrieving record');
                return $this->redirect(['index']);
            }
            
            $method->isactive = 0;
            $method->isdeleted = 1;
            $save_flag = $method->save();
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
            if (($model = PaymentMethod::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
