<?php
    namespace app\subcomponents\legacy\controllers;
    
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    use yii\helpers\Json;
    
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;


    class SubjectsController extends Controller
    {

        /**
         * Reneders Subject listing
         * 
         * @return type
         * 
         * Author: Laurence Chrles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016 | 22/03/2017
         */
        public function actionIndex()
        {
            if (Yii::$app->user->can('manageLegacySubjects') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $dataProvider = NULL;
            $subject_container = array();
            $subject_info = array();

            $subjects = LegacySubject::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();

            foreach ($subjects as $subject)
            {
                $subject_info['subjectid'] = $subject->legacysubjectid;
                $subject_info['name'] = $subject->name;
                
                $type = LegacySubjectType::find()
                        ->where(['legacysubjecttypeid' => $subject->legacysubjecttypeid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->name;
                $subject_info['type'] = $type;
                
                $subject_container[] = $subject_info;
            }

            $dataProvider = new ArrayDataProvider([
                        'allModels' => $subject_container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['type' => SORT_ASC, 'name' =>SORT_ASC],
                            'attributes' => ['type', 'name'],
                        ]
                ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
        
        
        /**
         * Renders subject creation form and processes user entry.
         * 
         * @return type
         * 
         * Author: Laurence Chrles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016 | 22/03/2017
         */
        public function actionCreate()
        {
            if (Yii::$app->user->can('createLegacySubjects') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            $subject = new LegacySubject();
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                
                $load_flag = $subject->load($post_data);
                if($load_flag == true)
                {
                    $save_flag = $subject->save();
                    if($save_flag == true)
                    {
                        return self::actionIndex();
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving subject record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading subject record.');
                }  
            }
            
            return $this->render('create_subject',
                    [
                        'subject' => $subject,
                    ]);
        }
        
        
        /**
         * 'Soft' deletes LegacyBatch record
         * 
         * @return type
         * 
         * Author: Laurence Chrles
         * Date Created: 09/07/2016
         * Date Last Modified: 09/07/2016 | 22/03/2017
         */
        public function actionDeleteSubject($id)
        {
            if (Yii::$app->user->can('manageLegacySubjects') == false)
            {
                Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                return $this->redirect(['/site/index']);
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $subject = LegacySubject::find()
                        ->where(['legacysubjectid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $subject->isactive = 0;
                $subject->isdeleted = 1;
                $save_flag = $subject->save();
                if($save_flag == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when deleting subject.');
                }
            }
            return self::actionIndex();
        }
        
        
        /**
         * Returns a JSON formatted listing of LegacySubject records
         * 
         * @param type $subjecttypeid
         * 
         * Author: Laurence Charles
         * Date Created: 12/07/2016
         * Date Last Modified: 12/07/2016
         */
        public function actionGetListing($subjecttypeid) 
        {
            $subjects = LegacySubject::find()
                    ->where(['legacysubjecttypeid' => $subjecttypeid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
           
            $listing = array();
            foreach ($subjects as $subject) 
            {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "id");
                array_push($keys, "name");
                $k1 = strval($subject->legacysubjectid);
                $k2 = strval($subject->name);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($listing, $combined);
                $combined = NULL;
                $keys = NULL;
                $values = NULL;
            }
            
            if ($listing) 
            {
                $found = 1;
                echo Json::encode(['found' => $found, 'subjects' => $listing]);
            } 
            else 
            {
                $found = 0;
                echo Json::encode(['found' => $found]);
            }
        }
        
    }

