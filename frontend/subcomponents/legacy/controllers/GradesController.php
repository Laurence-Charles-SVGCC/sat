<?php
    namespace app\subcomponents\legacy\controllers;
    
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    use yii\base\Model;

    use frontend\models\LegacyStudent;
    use frontend\models\LegacySubject;
    use frontend\models\LegacySubjectType;
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
        public function actionFindBatches($yearid = null, $termid = null, $levelid = null)
        {
             if (false/*Yii::$app->user->can('manageLegacyMarksheet') == false*/)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
             $years = LegacyYear::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();
             
             $info_string = "";
             $dataProvider = null;
             $cond_arr = array();
             $batches_container = array();
             $terms = null;
             $levels = null;
             $subjects = null;
            
            if ($yearid == null && $termid == null && $levelid == null)
            {
               //do nothing
            }
            else
            {
                if ($yearid != null)
                {
                    $year = LegacyYear::find()
                    ->where([ 'legacyyearid' => $yearid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
                    
                    $terms = LegacyTerm::find()
                         ->innerJoin('legacy_batch', '`legacy_term`.`legacytermid` = `legacy_batch`.`legacytermid`')
                         ->innerJoin('legacy_marksheet', '`legacy_batch`.`legacybatchid` = `legacy_marksheet`.`legacybatchid`')
                        ->where(['legacy_term.legacyyearid' => $yearid, 'legacy_term.isactive' => 1, 'legacy_term.isdeleted' => 0])
                        ->all();
                }
                
                if ($termid != null)
                {
                    $term= LegacyTerm::find()
                            ->where([ 'legacytermid' => $termid,  'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                            ->name;
                    
                    $cond_arr['legacy_batch.isactive'] = 1;
                    $cond_arr['legacy_batch.isdeleted'] = 0;
                    $cond_arr['legacy_marksheet.isactive'] = 1;
                    $cond_arr['legacy_marksheet.isdeleted'] = 0;
                    
                    $cond_arr['legacy_batch.legacytermid'] = $termid;
                    $info_string = $info_string .  " Term: " . LegacyTerm::find()
                                                                                        ->where([ 'legacytermid' => $termid,  'isactive' => 1, 'isdeleted' => 0])
                                                                                        ->one()
                                                                                        ->name;
                    
                    $levels = LegacyLevel::find()
                        ->where(['isactive' => 1, 'isdeleted' => 0])
                        ->all();
                }
                
                if ($levelid != null)
                {
                    $cond_arr['legacy_batch.legacylevelid'] = $levelid;
                    $info_string = $info_string .  " Level: " . LegacyLevel::find()
                                                                                        ->where([ 'legacylevelid' => $levelid,  'isactive' => 1, 'isdeleted' => 0])
                                                                                        ->one()
                                                                                        ->name;
                    
                    $subjects = LegacySubject::find()
                        ->where(['isactive' => 1, 'isdeleted' => 0])
                        ->all();
                }
                
                $batches = LegacyBatch::find()
                    ->innerJoin('legacy_marksheet', '`legacy_batch`.`legacybatchid` = `legacy_marksheet`.`legacybatchid`')
                    ->where($cond_arr)
                    ->all();

                foreach ($batches as $batch)
                {
                    $batch_info = array();
                    $batch_info['batchid'] = $batch->legacybatchid;

                    $level = LegacyLevel::find()
                        ->where([ 'legacylevelid' => $batch->legacylevelid,  'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->name;

                    $term = LegacyTerm::find()
                            ->where([ 'legacytermid' => $batch->legacytermid,  'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                            ->name;

                     $subject_record = LegacySubject::find()
                            ->where([ 'legacysubjectid' => $batch->legacysubjectid,  'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                     $subject = $subject_record->name;

                     $subject_type = LegacySubjectType::find()
                            ->where([ 'legacysubjecttypeid' => $subject_record->legacysubjecttypeid,  'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                             ->name;
                     
                     $student_count = LegacyMarksheet::find()
                             ->where(['legacybatchid' => $batch->legacybatchid, 'isactive' => 1, 'isdeleted' => 0])
                             ->count();

                    $batch_info['year'] = $year;
                    $batch_info['term'] = $term;
                    $batch_info['level'] = $level;
                    $batch_info['subject'] = $subject;
                    $batch_info['subject_type'] = $subject_type;
                    $batch_info['student_count'] = $student_count;
                    
                    $batches_container[] = $batch_info;
                }

                $dataProvider = new ArrayDataProvider([
                                'allModels' => $batches_container,
                                'pagination' => ['pageSize' => 25],
                                'sort' => ['defaultOrder' => ['subject' => SORT_ASC],
                                                'attributes' => ['subject', 'subjecttype']
                                              ]
                        ]);
            }

            
             return $this->render('batch_listing',
                     [  'dataProvider' => $dataProvider,
                         'years' => $years,
                         'yearid' => $yearid,
                         
                         'termid' => $termid,
                         'terms' => $terms,
                         
                         'levelid' => $levelid,
                         'levels' => $levels
                     ]);
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
        
        
        /**
         *Edit 'LegacyMarksheet' record
         * 
         * @param type $id
         * @return type
         * 
         * Author: Laurence Charles
         * Date Created: 22/03/2017
         * Date Last Modified: 22/03/2017
         */
        public function actionEditGrade($studentid, $batchid)
        {
            if (false/*Yii::$app->user->can('editLEgacyMarksheet') == false*/)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
            $marksheet = LegacyMarksheet::find()
                                     ->where(['legacystudentid' => $studentid, 'legacybatchid' => $batchid,  'isactive' => 1, 'isdeleted' => 0])
                                     ->one();
            
            $batch = LegacyBatch::find()
                    ->where([ 'legacybatchid' => $batchid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $level = LegacyLevel::find()
                    ->where([ 'legacylevelid' => $batch->legacylevelid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
            
            $term_record = LegacyTerm::find()
                    ->where([ 'legacytermid' => $batch->legacytermid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $term = $term_record->name;
            
            $year = LegacyYear::find()
                    ->where([ 'legacyyearid' => $term_record->legacyyearid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
            
             $subject_record = LegacySubject::find()
                    ->where([ 'legacysubjectid' => $batch->legacysubjectid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
             $subject = $subject_record->name;
             
             $subject_type = LegacySubjectType::find()
                    ->where([ 'legacysubjecttypeid' => $subject_record->legacysubjecttypeid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                     ->name;
             
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                
                $load_flag = $marksheet->load($post_data);
                if($load_flag == true)
                {
                    $date = date('Y-m-d');
                    $employeeid = Yii::$app->user->identity->personid;
                    $marksheet->lastmodifiedby =$employeeid ;
                    $marksheet->datemodified = $date;
                    $save_flag = $marksheet->save();
                    if($save_flag == true)
                    {
                        return $this->redirect(['/subcomponents/legacy/student/view', 'id' => $studentid]);
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured saving grade record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading grade record.');
                }  
            }
            
            return $this->render('edit_grade', 
                                                [ 'marksheet' => $marksheet,
                                                    'level' => $level,
                                                    'year' => $year,
                                                    'term' => $term,
                                                    'subject' => $subject,
                                                    'subject_type' => $subject_type
                                                ]);
        }
        
        
        public function actionUpdateBatchMarksheet($batchid)
        {
            if (false/*Yii::$app->user->can('manageLegacyMarksheet') == false*/)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
            $marksheets = LegacyMarksheet::find()
                    ->where(['legacybatchid' => $batchid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
            
            $batch = LegacyBatch::find()
                    ->where([ 'legacybatchid' => $batchid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $level = LegacyLevel::find()
                    ->where([ 'legacylevelid' => $batch->legacylevelid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
            
            $term_record = LegacyTerm::find()
                    ->where([ 'legacytermid' => $batch->legacytermid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $term = $term_record->name;
            
            $year = LegacyYear::find()
                    ->where([ 'legacyyearid' => $term_record->legacyyearid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                    ->name;
            
             $subject_record = LegacySubject::find()
                    ->where([ 'legacysubjectid' => $batch->legacysubjectid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
             $subject = $subject_record->name;
             
             $subject_type = LegacySubjectType::find()
                    ->where([ 'legacysubjecttypeid' => $subject_record->legacysubjecttypeid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                     ->name;
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                
                $load_flag = Model::loadMultiple($marksheets, $post_data);
                if ($load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        foreach($marksheets as $marksheet)
                        {
                            $date = date('Y-m-d');
                            $employeeid = Yii::$app->user->identity->personid;
                            $marksheet->lastmodifiedby =$employeeid ;
                            $marksheet->datemodified = $date;
                            $save_flag = $marksheet->save();
                            if($save_flag == false)
                            {
                                $transaction->rollback();
                                Yii::$app->getSession()->setFlash('error', 'Error occured when saving records.');
                                return $this->render('update_batch_marksheet', 
                                    ['marksheets' => $marksheets,
                                        'level' => $level,
                                        'year' => $year,
                                        'term' => $term,
                                        'subject' => $subject,
                                        'subject_type' => $subject_type
                                    ]);
                            }
                        }
                        $transaction->commit();
                        return self::actionFindBatches();
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading records.');
                }
            }
            
            return $this->render('update_batch_marksheet', 
                    ['batchid' => $batchid,
                        'marksheets' => $marksheets,
                        'level' => $level,
                        'year' => $year,
                        'term' => $term,
                        'subject' => $subject,
                        'subject_type' => $subject_type
                    ]);
        }
        
        
        public function actionAddMarksheets($batchid, $count)
        {
            if (false/*Yii::$app->user->can('manageLegacyMarksheet') == false*/)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
            $marksheets = array();
            for ($i = 0 ; $i < $count ; $i++)
            {
                $marksheet = new LegacyMarksheet();
                $marksheets[] = $marksheet;
            }
            
            $batch = LegacyBatch::find()
                    ->where([ 'legacybatchid' => $batchid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $level_record = LegacyLevel::find()
                    ->where([ 'legacylevelid' => $batch->legacylevelid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $level = $level_record->name;
            
            $term_record = LegacyTerm::find()
                    ->where([ 'legacytermid' => $batch->legacytermid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $term = $term_record->name;
            
            $year_record = LegacyYear::find()
                    ->where([ 'legacyyearid' => $term_record->legacyyearid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $year = $year_record->name;
            
             $subject_record = LegacySubject::find()
                    ->where([ 'legacysubjectid' => $batch->legacysubjectid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one();
             $subject = $subject_record->name;
             
             $subject_type = LegacySubjectType::find()
                    ->where([ 'legacysubjecttypeid' => $subject_record->legacysubjecttypeid,  'isactive' => 1, 'isdeleted' => 0])
                    ->one()
                     ->name;
            
            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $valid_records = 0;
                
                $load_flag = Model::loadMultiple($marksheets, $post_data);
                if ($load_flag == true)
                {
                    $exisiting_student_ids = array();
                    foreach($marksheets as $marksheet)
                    {
                        if (in_array($marksheet->legacystudentid, $exisiting_student_ids) == false)
                        {
                            $exisiting_student_ids[] = $marksheet->legacystudentid;
                        }
                        else
                        {
                            Yii::$app->getSession()->setFlash('error', 'Please remove duplicate entry then resubmit marksheet.');
                            return $this->render('add_marksheets', 
                                    ['marksheets' => $marksheets,
                                        'batchid' => $batchid,
                                        'level' => $level,
                                        'year' => $year,
                                        'term' => $term,
                                        'subject' => $subject,
                                        'subject_type' => $subject_type
                                    ]);
                        }
                    }
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        foreach($marksheets as $marksheet)
                        {
                            $date = date('Y-m-d');
                            $employeeid = Yii::$app->user->identity->personid;
                            $marksheet->legacybatchid = $batchid;
                            $marksheet->createdby =$employeeid ;
                            $marksheet->datecreated = $date;
                            $marksheet->lastmodifiedby =$employeeid ;
                            $marksheet->datemodified = $date;
                            if ($marksheet->term == true  && $marksheet->exam == true && $marksheet->final == true)
                            {
                                $save_flag = $marksheet->save();
                                if($save_flag == false)
                                {
                                    $transaction->rollback();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured when saving records.');
                                    return $this->render('add_marksheets', 
                                        ['marksheets' => $marksheets,
                                            'batchid' => $batchid,
                                            'level' => $level,
                                            'year' => $year,
                                            'term' => $term,
                                            'subject' => $subject,
                                            'subject_type' => $subject_type
                                        ]);
                                }
                                else
                                {
                                    $valid_records ++;
                                }
                            }
                        }
                        if($valid_records > 0)
                            $transaction->commit();
                        return self::actionFindBatches($year_record->legacyyearid, $term_record->legacytermid, $level_record->legacylevelid);
                    } catch (Exception $ex) {
                        Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured loading records.');
                }
            }
            
            return $this->render('add_marksheets', 
                    ['batchid' => $batchid,
                        'marksheets' => $marksheets,
                        'level' => $level,
                        'year' => $year,
                        'term' => $term,
                        'subject' => $subject,
                        'subject_type' => $subject_type
                    ]);
        }
        
         
        public function actionConfigureBatchMarksheets($yearid = null, $termid = null, $levelid = null)
        {
            if (false/*Yii::$app->user->can('manageLegacyMarksheet') == false*/)
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
            $years = LegacyYear::find()
                    ->where(['isactive' => 1, 'isdeleted' => 0])
                    ->all();
            
            $dataProvider = null;
            $batches_container = array();
            $info_string = "";
            $cond_arr = array();
            $terms = null;
            $levels = null;
            $subjects = null;
            $batchid = null;
            
            if ($yearid == null && $termid == null && $levelid == null)
            {
               //do nothing
            }
            else
            {
                if ($yearid != null)
                {
                    $year = LegacyYear::find()
                        ->where([ 'legacyyearid' => $yearid,  'isactive' => 1, 'isdeleted' => 0])
                        ->one()
                        ->name;
                    
                    $terms = LegacyTerm::find()
                        ->where(['legacyyearid' => $yearid, 'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                
                    if ($termid != null)
                    {
                        $cond_arr['legacy_batch.isactive'] = 1;
                        $cond_arr['legacy_batch.isdeleted'] = 0;
                        $cond_arr['legacy_marksheet.isactive'] = 1;
                        $cond_arr['legacy_marksheet.isdeleted'] = 0;

                        $cond_arr['legacy_batch.legacytermid'] = $termid;
                        $info_string = $info_string .  " Term -> " . LegacyTerm::find()
                                                                                            ->where([ 'legacytermid' => $termid,  'isactive' => 1, 'isdeleted' => 0])
                                                                                            ->one()
                                                                                            ->name . ",   ";

                        $levels = LegacyLevel::find()
                            ->where(['isactive' => 1, 'isdeleted' => 0])
                            ->all();

                        if ($levelid != null)
                        {
                            $cond_arr['legacy_batch.legacylevelid'] = $levelid;
                            $info_string = $info_string .  " Level -> " . LegacyLevel::find()
                                                                                                ->where([ 'legacylevelid' => $levelid,  'isactive' => 1, 'isdeleted' => 0])
                                                                                                ->one()
                                                                                                ->name;

                            

                            $batches = LegacyBatch::find()
                                ->innerJoin('legacy_marksheet', '`legacy_batch`.`legacybatchid` = `legacy_marksheet`.`legacybatchid`')
                                ->where($cond_arr)
                                ->all();
                            
                            if ($batches)
                            {
                                foreach ($batches as $batch)
                                {
                                    $batch_info = array();
                                    $batch_info['batchid'] = $batch->legacybatchid;

                                    $level = LegacyLevel::find()
                                        ->where(['legacylevelid' => $batch->legacylevelid,  'isactive' => 1, 'isdeleted' => 0])
                                        ->one()
                                        ->name;

                                    $term = LegacyTerm::find()
                                            ->where(['legacytermid' => $batch->legacytermid,  'isactive' => 1, 'isdeleted' => 0])
                                            ->one()
                                            ->name;

                                     $subject_record = LegacySubject::find()
                                            ->where([ 'legacysubjectid' => $batch->legacysubjectid,  'isactive' => 1, 'isdeleted' => 0])
                                            ->one();
                                     $subject = $subject_record->name;

                                     $subject_type = LegacySubjectType::find()
                                            ->where([ 'legacysubjecttypeid' => $subject_record->legacysubjecttypeid,  'isactive' => 1, 'isdeleted' => 0])
                                            ->one()
                                             ->name;

                                     $student_count = LegacyMarksheet::find()
                                             ->where(['legacybatchid' => $batch->legacybatchid, 'isactive' => 1, 'isdeleted' => 0])
                                             ->count();

                                    $batch_info['year'] = $year;
                                    $batch_info['term'] = $term;
                                    $batch_info['level'] = $level;
                                    $batch_info['subject'] = $subject;
                                    $batch_info['subject_type'] = $subject_type;
                                    $batch_info['student_count'] = $student_count;

                                    $batches_container[] = $batch_info;
                                }

                                $dataProvider = new ArrayDataProvider([
                                                'allModels' => $batches_container,
                                                'pagination' => ['pageSize' => 25],
                                                'sort' => ['defaultOrder' => ['subject' => SORT_ASC],
                                                                'attributes' => ['subject', 'subjecttype']
                                                              ]
                                        ]);
                            }
                            else
                            {
                                $subjects = LegacySubject::find()
                                    ->where(['isactive' => 1, 'isdeleted' => 0])
                                    ->all();
                                
                                foreach ($subjects as $subject)
                                {
                                    $subject_info = array();
                                    $batch_info['subjectid'] = $subject->legacysubjectid;
                                    
                                    $batch = LegacyBatch::find()
                                        ->where(['legacysubjectid' => $subject->legacysubjectid, 'legacytermid' => $termid, 'legacylevelid' => $levelid,  'isactive' => 1, 'isdeleted' => 0])
                                        ->one();
                                    $batch_info['batchid'] = $batch->legacybatchid;
                                    $batchid = $batch->legacybatchid;

                                    $level = LegacyLevel::find()
                                        ->where(['legacylevelid' => $levelid,  'isactive' => 1, 'isdeleted' => 0])
                                        ->one()
                                        ->name;

                                    $term = LegacyTerm::find()
                                            ->where(['legacytermid' => $termid,  'isactive' => 1, 'isdeleted' => 0])
                                            ->one()
                                            ->name;

                                     $subject_name = $subject->name;

                                     $subject_type = LegacySubjectType::find()
                                            ->where([ 'legacysubjecttypeid' => $subject->legacysubjecttypeid,  'isactive' => 1, 'isdeleted' => 0])
                                            ->one()
                                             ->name;

                                     $student_count = LegacyMarksheet::find()
                                             ->where(['legacybatchid' => $batch->legacybatchid, 'isactive' => 1, 'isdeleted' => 0])
                                             ->count();

                                    $batch_info['year'] = $year;
                                    $batch_info['term'] = $term;
                                    $batch_info['level'] = $level;
                                    $batch_info['subject'] = $subject_name;
                                    $batch_info['subject_type'] = $subject_type;
                                    $batch_info['student_count'] = $student_count;

                                    $batches_container[] = $batch_info;
                                    
                                    $dataProvider = new ArrayDataProvider([
                                                'allModels' => $batches_container,
                                                'pagination' => ['pageSize' => 25],
                                                'sort' => ['defaultOrder' => ['subject' => SORT_ASC],
                                                                'attributes' => ['subject', 'subjecttype']
                                                              ]
                                        ]);
                                }
                            }
                        }
                    }
                }
            }

            
             return $this->render('configure_batch_marksheets',
                     [  'dataProvider' => $dataProvider,
                         'info_string' => $info_string,
                         'years' => $years,
                         'yearid' => $yearid,
                         
                         'termid' => $termid,
                         'terms' => $terms,
                         
                         'levelid' => $levelid,
                         'levels' => $levels,
                         
                         'batchid' => $batchid,
                         'batches_container' => $batches_container
                     ]);
            
        }
        
        
        
        
        
        
        
    }

