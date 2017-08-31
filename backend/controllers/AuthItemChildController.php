<?php
    namespace backend\controllers;

    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\data\ArrayDataProvider;
    
    use backend\models\AuthItemChild;
    use backend\models\AuthItem;

    // (laurence_charles) - Manages auth_item_child associations.
    // Its scope is contrained to the building of role hierarchies, i.e. assignment of child roles to parent roles.
    // The assignment of permissions to roles is managed by the AuthItem controller, which facilitate permission
    // assignment on a role by role basis
    class AuthItemChildController extends Controller
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
         * Displays parent-child role associations
         * 
         * @param type $type
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: ??
         * Date Last Modified: 2017_08_31
         */
        public function actionIndex($type)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
             $dataProvider = NULL;
            $container = array();
            
            $role_associations = AuthItemChild::find()->all();
                    
            if ($type == "assign-role-to-role")
            {
                $title = "Role Hierarchies";
                if (empty($role_associations) == false)
                {
                    foreach ($role_associations as $role_association)
                    { 
                         $data = array();

                        $parent_type = AuthItem::find()->where(['name' => $role_association->parent])->one()->type;
                        $child_type = AuthItem::find()->where(['name' => $role_association->child])->one()->type;
                        if ($parent_type == 1 && $child_type == 1)
                        {
                            $data['parent'] =  $role_association->parent;
                            $data['child'] = $role_association->child;
                            $data['type'] = $type;
                            $container[] = $data;
                        }
                    }
                }
            }
            elseif($type == "assign-permission-to-role")
            {
                $title = "Role Responsibilities";
                if (empty($role_associations) == false)
                {
                    foreach ($role_associations as $role_association)
                    { 
                         $data = array();

                        $parent_type = AuthItem::find()->where(['name' => $role_association->parent])->one()->type;
                        $child_type = AuthItem::find()->where(['name' => $role_association->child])->one()->type;
                        if ($parent_type == 1 && $child_type == 2)
                        {
                            $data['parent'] =  $role_association->parent;
                            $data['child'] = $role_association->child;
                            $data['type'] = $type;
                            $container[] = $data;
                        }
                    }
                }
            }
            
            $dataProvider = new ArrayDataProvider([
                        'allModels' => $container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['parent' => SORT_ASC],
                            'attributes' => ['parent', 'child']
                        ]
                ]);
            
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'type' => $type,
                'title' => $title,
                'parents' => 'parents',
                'children' => 'children'
            ]);
        }


         /**
         *  Assigns child role to parent role
         * 
         * @param type $type
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: ??
         * Date Last Modified: 2017_08_31
         */
        public function actionCreate($type)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                  return $this->redirect(['index', 'type' => $type]);
            }
            
            $model = new AuthItemChild();
            
            if ($type == "assign-role-to-role")
            {
                $title = "Role Hierarchies";
                $children = AuthItem::find()
                        ->where(['type' => 1])
                        ->all();
            }
            elseif($type == "assign-permission-to-role")
            {
                $title = "Role Responsibilities";
                $children = AuthItem::find()
                        ->where(['type' => 2])
                        ->all();
            }
            
            $parents = AuthItem::find()->where(['type' => 1])->all();
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    if ($model->save())
                    {
                         return $this->redirect(['index', 'type' => $type]);
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Error occured assigning role.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            } 
            return $this->render('create', [
                'model' => $model,
                'type' => $type,
                'title' => $title,
                'parents' => $parents,
                'children' => $children]);
        }
           
        
        // (laurence_charles) - Modifies child role of a parent role
        public function actionUpdate($parent, $child)
        {
            $model = $this->findModel($parent, $child);
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $load_flag = $model->load($post_data); 
                
                if ($load_flag == true)
                {
                    if ($model->save())
                    {
                        return self::actionIndex();
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', 'Error occured assigning role.');
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Error occured loading data entry.');
                } 
            } 
            return $this->render('update', ['model' => $model,]);
        }

        
        // (laurence_charles) - Delete role-role assignment
        public function actionDelete($parent, $child, $type)
        {
            $this->findModel($parent, $child)->delete();

              return $this->redirect(['index', 'type' => $type]);
        }

        /**
         * Finds the AuthItemChild model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $parent
         * @param string $child
         * @return AuthItemChild the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($parent, $child)
        {
            if (($model = AuthItemChild::findOne(['parent' => $parent, 'child' => $child])) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
