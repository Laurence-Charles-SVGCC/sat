<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
                 return $this->render('unauthorized');
            }
           
            $dataProvider = NULL;
            $batch_container = array();
            $batch_info = array();
            
            $batches = LegacyBatch::find()
                    ->innerJoin(['legacy_subject', '`legacy_batch`.`legacysubjectid` = `legacy_subject`.`legacysubjectid`'])
                    ->where(['legacy_subject.isactive' => 1, 'legacy_subject.isdeleted' => 0,
                                    'legacy_batch.isactive' => 1, 'legacy_batch.isdeleted' => 0
                                ])
                    ->groupby('legacy_subject.legacysubjectid')
                    ->all();
            
            foreach ($batches as $batch)
            {
                $batch_info['subjectid'] = $batch->legacysubjectid;
                
                $subject = LegacySubject::find()
                        ->where(['legacysubjectid' => $batch->legacysubjectid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one();
                $batch_info['name'] = $subject->name;
                
                $type = LegacySubjectType::find()
                        ->where(['legacysubjecttypeid' => $subject->legacysubjecttypeid, 'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->name;
                $batch_info['type'] = $type;
                
                $batch_count = LegacyBatch::find()
                    ->innerJoin(['legacy_subject', '`legacy_batch`.`legacysubjectid` = `legacy_subject`.`legacysubjectid`'])
                    ->where(['legacy_subject.legacysubjectid' => $subject->legacysubjectid,  'legacy_subject.isactive' => 1, 'legacy_subject.isdeleted' => 0,
                                    'legacy_batch.isactive' => 1, 'legacy_batch.isdeleted' => 0
                                ])
                    ->count();
                $batch_info['count'] = $batch_count;
                
                $batch_container[] = $batch_info;
            }
            
            $dataProvider = new ArrayDataProvider([
                        'allModels' => $batch_container,
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
         * Renders LegacyBatch create form and processes user input.
         * 
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 12/07/2016
         * Date Last Modified: 12/07/2016
         */
        public function actionCreate()
        {
            if (false/*Yii::$app->user->can('createLegacyBatch') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if (Yii::$app->request->post()) 
            {
                $request = Yii::$app->request;
                $batchtypeid = $request->post('batch_type_field');
                $levelid = $request->post('level_field');
                $subjecttypeid = $request->post('subject_type_field');
                $subjectid = $request->post('subject_field');
                $yearid = $request->post('year_field');
                $termid = $request->post('term_field');
                
                if ($batchtypeid && $levelid  &&  $subjecttypeid  && $subjectid  && $yearid  && $termid)
                {
                    $batch = new LegacyBatch();
                    $batch->legacytermid = $termid;
                    $batch->legacysubjectid = $subjectid;
                    $batch->legacybatchtypeid = $batchtypeid;
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
            
            return $this->render('create_batch');
        }
        
        
        public function actionDeleteBatch()
        {
            if (true/*Yii::$app->user->can('deleteLegacyBatch') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
           
        }
    }
