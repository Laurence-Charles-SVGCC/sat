<?php
    namespace app\subcomponents\legacy\controllers;
  
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    
    use frontend\subcomponents\legacy\models\LegacyLevel;
    
    
    
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;
    use frontend\models\LegacyYear;
    use frontend\models\LegacyTerm;
    use frontend\models\LegacyBatch;
    
    use frontend\models\Employee;


    class LevelController extends Controller
    {
        /**
         * Renders legacy level listing
         * 
         * @return type
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_06_04
         * Modified: 2018_06_04
         */
        public function actionIndex()
        {
            if (true/*Yii::$app->user->can('viewLegacyLevels') == true*/)
            {
                $dataProvider = NULL;
                $container = array();
                $info = array();

                foreach (LegacyLevel::getActiveLevels() as $level)
                {
                    $info['legacylevelid'] = $level->legacylevelid;
                    $info['name'] = $level->name;
                    $info['status'] = ($level->isactive == 1 && $level->isdeleted == 0) ? "Active" : "Deleted";
                    $info['isactive'] = $level->isactive;
                    $info['isdeleted'] = $level->isdeleted;
                    $year_container[] = $info;
                }

                $dataProvider = new ArrayDataProvider([
                            'allModels' => $year_container,
                            'pagination' => [
                                'pageSize' => 25,
                            ],
                            'sort' => [
                                'defaultOrder' => ['name' => SORT_ASC],
                                'attributes' => [ 'name', 'status'],
                            ]
                    ]);
                
                $title = "Levels";

                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'title' => $title
                ]);
            }
            else
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
        }
        
        
        
        /**
         * 'Soft' deletes LegacyLevel record
         * 
         * @return type
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_06_05
         * Modified: 2018_06_05
         */
        public function actionDeleteLevel($id)
        {
            if (true/*Yii::$app->user->can('deleteLegacyLevels') == true*/)
            {
                $level = LegacyLevel::findOne(['legacylevelid' => $id]);
                $level->isactive = 0;
                $level->isdeleted = 1;
                if ($level->save() == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Deletion was unsuccessful.');
                }
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
        }
        
        
        /**
         * Restores LegacyLevel record
         * 
         * @return type
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2018_06_05
         * Modified: 2018_06_05
         */
        public function actionRestore($id)
        {
            if (true/*Yii::$app->user->can('deleteLegacyLevels') == true*/)
            {
                $level = LegacyLevel::findOne(['legacylevelid' => $id]);
                $level->isactive = 1;
                $level->isdeleted = 0;
                if ($level->save() == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Deletion was unsuccessful.');
                }
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
        }
        
        
        
        public function isDeletable()
        {
            return false;
        }
        
        public function isRestorable()
        {
            return false;
        }
    }
