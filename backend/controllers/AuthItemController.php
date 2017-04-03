<?php
    namespace backend\controllers;

    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;

    use backend\models\AuthItem;
    use backend\models\AuthItemChild;
    
    
    class AuthItemController extends Controller
    {
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get'],
                    ],
                ],
            ];
        }

        // (laurence_charles) - View listing of roles and permissions
        public function actionIndex($type)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $dataProvider = NULL;
            $container = array();
            
            if ($type == "Roles")
            {
                $auth_items = AuthItem::find()
                        ->where(['type' => 1])
                        ->all();
            }
            elseif($type == "Permissions")
            {
                $auth_items = AuthItem::find()
                        ->where(['type' => 2])
                        ->all();
            }
            
            if (empty($auth_items) == false)
            {
                foreach ($auth_items as $auth_item)
                { 
                    $data = array();
                    $data['type'] = $auth_item->type;
                    $data['name'] = $auth_item->name;
                    $data['description'] = $auth_item->description;
                    $container[] = $data;
                }
            }
            
            $dataProvider = new ArrayDataProvider([
                        'allModels' => $container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['name' => SORT_ASC],
                            'attributes' => ['name']
                        ]
                ]);
          
            return $this->render('index', ['dataProvider' => $dataProvider,
                                                            'type' => $type]);
        }
        
        
        // (laurence_charles) - Displays a single AuthItem model.
        public function actionView($name, $type)
        {
             if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $permission_dataProvider = NULL;
            $permission_container = array();
            
            if ($type == 1)
            {
                $permissions = array();
                $direct_permissions = AuthItemChild::find()
                    ->innerJoin('auth_item' , '`auth_item_child`.`child` = `auth_item`.`name`')
                     ->where(['auth_item_child.parent' => $name, 'auth_item.type' => 2])
                     ->all();
                foreach($direct_permissions as $direct_permission)
                {
                    if(in_array($direct_permission->child, $permissions) == false)
                    {
                        $permissions[] = $direct_permission->child;
                    }
                }

                $descendant_roles = AuthItemChild::getRoleDescendants($name);
                if (empty($descendant_roles) == false)
                {
                    foreach($descendant_roles  as $descendant_role)
                    {
                        $associated_permissions = AuthItemChild::find()
                            ->innerJoin('auth_item' , '`auth_item_child`.`child` = `auth_item`.`name`')
                             ->where(['auth_item_child.parent' => $descendant_role, 'auth_item.type' => 2])
                             ->all();
                        foreach($associated_permissions as $associated_permission)
                        {
                            if(in_array($associated_permission->child, $permissions) == false)
                            {
                                $permissions[] = $associated_permission->child;
                            }
                        }
                    }
                }
                
                if (empty($permissions) == false)
                {
                    foreach ($permissions as $permission)
                    { 
                        $permission_info = array();
                        $permission_info['role_name'] = $name;
                        $permission_info['name'] = $permission;
                         $description = AuthItem::find()
                             ->where(['name' => $permission])
                            ->one()
                            ->description;
                        $permission_info['description'] = $description;
                        $permission_container[] = $permission_info;
                    }
                }

                $permission_dataProvider = new ArrayDataProvider([
                            'allModels' => $permission_container,
                            'pagination' => [
                                'pageSize' => 25,
                            ],
                            'sort' => [
                                'defaultOrder' => ['name' => SORT_ASC],
                                'attributes' => ['name']
                            ]
                    ]);
                
                $new_permission = new AuthItemChild();
                $new_permission->parent = $name;
            }
            
            return $this->render('view', ['model' => $this->findModel($name),
                                                            'name' => $name,
                                                            'type' => $type,
                                                            'permissions' => $permissions,
                                                            'permission_dataProvider' => $permission_dataProvider,
                                                            'new_permission' => $new_permission,
                                                        ]);
        }

        
        // (laurence_charles) - Creates a new AuthItem model.
        public function actionCreate($type)
        {
             if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = new AuthItem();
            $model->type = $type;

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    $model->rule_name = $model->rule_name == "" ? NULL : $model->rule_name;
                    if ($model->save())
                    {
                        return $this->redirect(['view', 'name' => $model->name, 'type' => $type]);
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Error occured saving record.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            } 
            return $this->render('create', ['model' => $model,
                                                            'type' => $type]);
        }

        
        // (laurence_charles) - Updates an existing AuthItem model.
        public function actionUpdate($name, $type)
        {
             if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = $this->findModel($name);

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    $model->rule_name = $model->rule_name == "" ? NULL : $model->rule_name;
                    if ($model->save())
                    {
                        return $this->redirect(['view', 'name' => $model->name, 'type' => $type]);
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Error occured saving record.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            } 
            
            return $this->render('update', ['model' => $model,
                                                            'type' => $type]);
        }

        // (laurence_charles) - Deletes an existing AuthItem model.
        public function actionDelete($name, $type)
        {
             if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $this->findModel($name)->delete();
            
            if ($type == 1)
            {
                return $this->redirect(['index', 'type' => "Roles"]);
            }
            elseif ($type == 2)
            {
                return $this->redirect(['index', 'type' => "Permissions"]);
            }
        }
        
        // (laurence_charles) - Assigns new permission to an existing role
        public function actionAssignNewPermissionToRole($name, $type)
        {
             if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = new AuthItemChild();

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    $model->parent = $name;
                    if ($model->save())
                    {
                        return $this->redirect(['view', 'name' => $name, 'type' => $type]);
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Error occured saving record.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            } 
        }
        
        
        // (laurence_charles) - Removes permission to an existing role
        public function actionDeletePermissionFromRole($name, $type, $permission_name)
        {
             if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $model = AuthItemChild::find()
                    ->where(['parent' => $name, 'child' => $permission_name])
                    ->one();
            
            if ($model == true)
            {
                $model->delete();
            }
            
            return $this->redirect(['view', 'name' => $name, 'type' => $type, $permission_name]);
        }

        
        // (laurence_charles) - Finds the AuthItem model based on its primary key value.
        // If the model is not found, a 404 HTTP exception will be thrown.
        protected function findModel($name)
        {
            if (($model = AuthItem::find()->where(['name' => $name])->one()) !== null)
            {
                return $model;
            } else 
            {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        protected function addChild()
        {
            $model = new AuthItemChild();

            if ($model->load(Yii::$app->request->post()))
            {
               // $model->rule_name = $model->rule_name == "" ? NULL : $model->rule_name;
                if ($model->save())
                {
                    return $this->redirect(['view', 'id' => $model->name]);
                }
                //Raise error
            } 
            return $this->render('add-child', [
                    'model' => $model,
                ]);
        }
        
        
        
        
        
        
    }
