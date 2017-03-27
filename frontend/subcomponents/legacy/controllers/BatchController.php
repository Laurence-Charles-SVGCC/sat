<?php
    namespace app\subcomponents\legacy\controllers;
    
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;
    use frontend\models\LegacyYear;
    use frontend\models\LegacyTerm;
    use frontend\models\LegacyBatch;
    use frontend\models\LegacyLevel;
    use frontend\models\LegacyMarksheet;
    use frontend\models\Employee;


    class BatchController extends Controller
    {

        /**
         * Renders listing of batches
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 12/07/2016
         * Date Last Modified: 12/07/2016
         */
        public function actionIndex()
        {
            if (false/*Yii::$app->user->can('manageLegacyBatches') == false*/)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
           
            $dataProvider = NULL;
            $batch_container = array();
            $batch_info = array();
            
            $batches = LegacyBatch::find()
                    ->innerJoin(['legacy_subject', '`legacy_batch`.`legacysubjectid` = `legacy_subject`.`legacysubjectid`'])
                    ->where(['legacy_subject.isactive' => 1, 'legacy_subject.isdeleted' => 0,
                                    'legacy_batch.isactive' => 1, 'legacy_batch.isdeleted' => 0])
//                    ->groupby('legacy_subject.legacysubjectid')
                    ->all();
            
            foreach ($batches as $batch)
            {
                $batch_info['subjectid'] = $batch->legacysubjectid;
                $batch_info['batchid'] = $batch->legacybatchid;
                
                $term = LegacyTerm::find()
                        ->where(['legacytermid' => $batch->legacytermid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $batch_info['term'] = $term->name;
                
                $year = LegacyYear::find()
                        ->where(['legacyyearid' => $term->legacyyearid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $batch_info['year'] = $year->name;
                
                $subject = LegacySubject::find()
                        ->where(['legacysubjectid' => $batch->legacysubjectid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $batch_info['name'] = $subject->name;
                
                 $level = LegacyLevel::find()
                        ->where(['legacylevelid' => $batch->legacylevelid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $batch_info['level'] = $level->name;
                
                $type = LegacySubjectType::find()
                        ->where(['legacysubjecttypeid' => $subject->legacysubjecttypeid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->name;
                $batch_info['type'] = $type;
                
                $student_count = LegacyMarksheet::find()
                    ->where(['legacybatchid' => $batch->legacybatchid,  'isactive' => 1, 'isdeleted' => 0])
                    ->count();
                $batch_info['student_count'] = $student_count;
                
                $batch_container[] = $batch_info;
            }
            
            $dataProvider = new ArrayDataProvider([
                        'allModels' => $batch_container,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'defaultOrder' => ['type' => SORT_ASC, 'name' =>SORT_ASC],
                            'attributes' => ['name', 'type', 'year', 'term', 'level'],
                        ]
                ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
        
        
        /**
         * Renders LegacyBatch create form and processes user input.
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 12/07/2016
         * Date Last Modified: 12/07/2016 | 27/03/2017
         */
        public function actionCreate()
        {
            if (false/*Yii::$app->user->can('createLegacyBatch') == false*/)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
            if (Yii::$app->request->post()) 
            {
                $request = Yii::$app->request;
                $levelid = $request->post('level_field');
                $subjecttypeid = $request->post('subject_type_field');
                $subjectid = $request->post('subject_field');
                $yearid = $request->post('year_field');
                $termid = $request->post('term_field');
                
                if ($levelid  &&  $subjecttypeid  && $subjectid  && $yearid  && $termid)
                {
                    $batch = new LegacyBatch();
                    $batch->legacytermid = $termid;
                    $batch->legacysubjectid = $subjectid;
                    $batch->legacylevelid = $levelid;
                    
                    $term = LegacyTerm::find()
                            ->where(['legacytermid' =>  $termid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $subject = LegacySubject::find()
                            ->where(['legacysubjectid' =>  $subjectid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $level = LegacyLevel::find()
                            ->where(['legacylevelid' =>  $levelid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    
                    $existing_batch = LegacyBatch::find()
                            ->where(['legacytermid' =>  $termid, 'legacysubjectid' =>  $subjectid, 'legacylevelid' =>  $levelid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($existing_batch == true)
                    {
                        Yii::$app->getSession()->setFlash('error', 'A batch with this configuration already exists.');
                         return $this->render('create');
                    }
                    
                    $batch->name = $term->name . "-" . $subject->name . "-" . $level->name . " Batch";
                    $employeeid = Yii::$app->user->identity->personid;
                    $date = date('Y-m-d');
                    $batch->createdby = $employeeid;
                    $batch->datecreated = $date;
                    $batch->lastmodifiedby =$employeeid ;
                    $batch->datemodified = $date;
                    $save_flag = $batch->save();
                    if($save_flag == true)
                    {
                        return self::actionIndex();
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving exam batch record.');
                    }
                }
            }
            return $this->render('create');
        }
        
        
        public function actionDeleteBatch()
        {
            if (true/*Yii::$app->user->can('deleteLegacyBatch') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
           
        }
    }
