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
    use yii\base\Model;

    use frontend\models\LegacyStudent;
    use frontend\models\LegacySubject;
    use frontend\models\LegacyFaculty;
    use frontend\models\LegacyYear;
    use frontend\models\LegacyBatch;
    use frontend\models\LegacyBatchType;
    use frontend\models\LegacyMarksheet;
    use frontend\models\LegacyLevel;
    use frontend\models\LegacyTerm;


    class GradesController extends Controller
    {
        
        /**
         * Updates the records for a particluar batch
         * 
         * @param type $record_count
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 13/07/2016
         * Date Last Modified: 13/07/2016
         */
         public function actionFindBatchMarksheet()
        {
             if (false/*Yii::$app->user->can('manageLegacyMarksheet') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if (Yii::$app->request->post()) 
            {
                $request = Yii::$app->request;
                $batchtypeid = $request->post('grades_batch_type_field');
                $levelid = $request->post('grades_level_field');
                $subjecttypeid = $request->post('grades_subject_type_field');
                $subjectid = $request->post('grades_subject_field');
                $yearid = $request->post('grades_year_field');
                $termid = $request->post('grades_term_field');
                
                if ($batchtypeid && $levelid  &&  $subjecttypeid  && $subjectid  && $yearid  && $termid)
                {
                    $batch = LegacyBatch::find()
                            ->where(['legacybatchtypeid' => $batchtypeid , 'legacylevelid' => $levelid  , 'legacysubjectid' => $subjectid, 
                                            'legacytermid' => $termid , 'isactive' => 1 , 'isdeleted' => 0
                                        ])
                            ->one();
                    if($batch == true)
                    {
                        $marksheets = LegacyMarksheet::find()
                                ->where(['legacybatchid' => $batch->legacybatchid, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();
                        if($marksheets == true)
                        {
                            $year = LegacyYear::find()
                                     ->where(['legacyyearid' => $yearid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one()
                                    ->name;
                            $term = LegacyTerm::find()
                                    ->where(['legacytermid' => $termid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one()
                                    ->name;
                            $subject = LegacySubject::find()
                                    ->where(['legacysubjectid' => $subjectid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one()
                                    ->name;
                            $level = LegacyLevel::find()
                                    ->where(['legacylevelid' => $levelid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one()
                                    ->name;
                            $type = LegacyBatchType::find()
                                    ->where(['legacybatchtypeid' => $batchtypeid, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one()
                                    ->name;
                            $students = array();
                            foreach($marksheets as $marksheet)
                            {
                                $student = LegacyStudent::find()
                                    ->where(['legacystudentid' => $marksheet->legacystudentid , 'isactive' => 1 , 'isdeleted' => 0])
                                    ->one();
                                $name = $student->lastname . ", " . $student->firstname . " " . $student->middlename;
                                $students[] = $name;
                            }

                             return $this->render('grade_entry_step_two',
                                    [
                                        'marksheets' => $marksheets,
                                        'year' => $year,
                                        'term' => $term,
                                        'subject' => $subject,
                                        'level' => $level,
                                        'type' => $type,
                                        'students' => $students,
                                    ]);  
                        }
                        else
                        {
                            Yii::$app->getSession()->setFlash('error', 'No batch-marksheets currently exists matching your entered criteria.');
                        }
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'No batch currently exists matching your entered criteria.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
            return $this->render('grade_entry_step_one');
        }
        
        
        /**
         * Updates the records for a particluar batch
         * 
         * @param type $record_count
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 13/07/2016
         * Date Last Modified: 13/07/2016
         */
        public function actionUpdateGrades($record_count, $batchid)
        {
            if (false/*Yii::$app->user->can('enrollLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $marksheets = LegacyMarksheet::find()
                                ->where(['legacybatchid' => $batchid, 'isactive' => 1, 'isdeleted' => 0])
                                ->all();
                
                $load_flag = false;
                $all_saves_successful = true;
                $load_flag = Model::loadMultiple($marksheets, $post_data);
                if($load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        foreach($marksheets as $marksheet)
                        {
                            if($marksheet->mark == true && $marksheet->mark >= 0)
                            {
                                $save_flag = false;
                                $date = date('Y-m-d');
                                $employeeid = Yii::$app->user->identity->personid;
                                $marksheet->lastmodifiedby =$employeeid ;
                                $marksheet->datemodified = $date;
                                $save_flag = $marksheet->save();
                                if($save_flag == false)
                                {
                                    $all_saves_successful = false;
                                    $transaction->rollback();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving records.');
                                    break;
                                }
                            }
                        }
                        
                        if($all_saves_successful ==true)
                        {
                            $transaction->commit();
                        }
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading marksheet record.');
                }
            }
            return $this->redirect(\Yii::$app->request->getReferrer());
        }
        
        
    }

