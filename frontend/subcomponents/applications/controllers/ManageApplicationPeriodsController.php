<?php
    namespace app\subcomponents\applications\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    
    use yii\custom\UnauthorizedAccessException;
    use frontend\models\ApplicationPeriod;
    use frontend\models\AcademicYear;
    use frontend\models\Division;
    use frontend\models\Employee;
    use frontend\models\ApplicationPeriodType;
    use frontend\models\ApplicationperiodStatus;
    
    use frontend\models\ProgrammeCatalog;
    use frontend\models\AcademicOffering;
     use frontend\models\CapeGroup;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\CapeSubject;
    use frontend\models\Subject;
    use frontend\models\IntentType;
    use frontend\models\ExaminationBody;
    use frontend\models\QualificationType;
    use frontend\models\Department;
    use frontend\models\ApplicantIntent;
    
    class ManageApplicationPeriodsController extends Controller
    {
        /**
         * Renders details and programme listing of an application period
         * 
         * @param Integer $id
         * @return view 'view_application_period'
         * @throws UnauthorizedAccessException
         * 
         *  Author: charles.laurence1@gmail.com
         *  Created: 2017_09_07
         *  Modified: 2017_10_09
         */
        public function actionViewApplicationPeriod($id)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $application_period =  ApplicationPeriod::getApplicationPeriod($id);
            
            $programmes = $application_period->prepareAcademicOfferingsSummary();
            
            $cape_offerings =  $application_period-> prepareCapeOfferingSummary();
            
            
            return $this->render('view_application_period', [
                'id' => $application_period->applicationperiodid,
                'iscomplete' => $application_period->iscomplete,
                'applicationperiodstatusid' => $application_period->applicationperiodstatusid,
                'academic_year' => AcademicYear::find()->where(['academicyearid' => $application_period->academicyearid])->one()->title,
                'division' => Division::find()->where(['divisionid' => $application_period->divisionid])->one()->name,
                'creator' => Employee::getEmployeeName($application_period->personid),
                'name' => $application_period->name,
                'onsitestartdate' => date_format(date_create($application_period->onsiteenddate), "d/m/Y"),
                'onsiteenddate' => date_format(date_create($application_period->onsiteenddate), "d/m/Y"),
                'offsitestartdate' => date_format(date_create($application_period->offsitestartdate), "d/m/Y"),
                'offsiteenddate' => date_format(date_create($application_period->offsiteenddate), "d/m/Y"),
                'period_type' => ApplicationPeriodType::find()->where(['applicationperiodtypeid' => $application_period->applicationperiodtypeid])->one()->name,
                 'period_status' => ApplicationPeriodStatus::find()->where(['applicationperiodstatusid' => $application_period->applicationperiodstatusid])->one()->name,
                'applicant_visibility' => $application_period->iscomplete == 1 ? "Excluded" : "Visible",
                'programmes' => $programmes,
                'cape_offerings' => $cape_offerings]);
        }
        
        
        /**
         * Updates an ApplicationPeriod record
         * 
         * @param Integer $id
         * @return view 'edit_application_period'
         * @throws UnauthorizedAccessException
         * 
         * Author: charles.laurence1@gmail.com
         *  Created: 2017_09_11
         *  Modified: 2017_10_09
         */
        public function actionEditApplicationPeriod($id)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $period = ApplicationPeriod::getApplicationPeriod($id);
            $applicantintentid = $period->getApplicantIntent();
            $divisions = Division::find()
                    ->where(['divisionid' => [4,5,6,7], 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
            $academic_years = AcademicYear::find()
                    ->where(['applicantintentid' => $applicantintentid, 'isactive' => 1 , 'isdeleted' => 0])
                    ->all();

            if ($post_data = Yii::$app->request->post())
            {
                if($period->load($post_data) == true)
                {
                    $period->personid = Yii::$app->user->identity->personid;
                    if($period->save() == true)
                    {
                        return $this->redirect(['manage-application-periods/view-application-period', 'id' => $id]);
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update application period record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load application period record.');     
                }
            }

            return $this->render('edit_application_period',[
                'period' => $period,
                'divisions' => $divisions,
                'academic_years' => $academic_years]);
        }
        
        
        /**
         * Update programme_listing and cape_subject listing
         * 
         * @param Integer $id
         * @return view 'manage_programme_offerings'
         * @throws UnauthorizedAccessException
         * 
         * Author: charles.laurence1@gmail.com
         *  Created: 2017_09_12
         *  Modified: 2017_10_09
         */
        public function actionManageProgrammeOfferings($id)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $operation_successful = true;
            $period = ApplicationPeriod::getApplicationPeriod($id);
            
            /********************************     Process Associate Degree Programmes    **************************************/
            
            $offerings_result_set = $period->prepareAcademicOfferings();
            $programmes = $offerings_result_set[0];
            $programme_count = count($programmes);
            $offering_copy = $offerings_result_set[1];
            $offerings = $offerings_result_set[2];
            
           /****************************************     Process CAPE Subjects    *******************************************/
            
            $subjects = array();
            $cape_result_set = $period->prepareCapeOfferings();
            $subjects = $cape_result_set[0];
            $subject_count = count($subjects);
            $group_copy = $cape_result_set[1];
            $cape_offering_copy = $cape_result_set[2];
            $cape_subjects_copy = $cape_result_set[3];
            $cape_subjects = $cape_result_set[4];
            $subject_groups = $cape_result_set[5];

            /************************************************************************************************/
            
            if ($post_data = Yii::$app->request->post())
            {
                //period flags
                $period_save_flag = false;

                //academic offering flags
                $offerings_load_flag = false;
                $offering_save_flag = false;

                //cape subject flags
                $subjects_load_flag = false;
                $subject_save_flag = false;

                //cape-subject-group flags
                $groups_load_flag = false;
                $groups_save_flag = false;

                $offerings_load_flag = Model::loadMultiple($offerings, $post_data);

                if($period->divisionid == 4)
                {
                    $subjects_load_flag = Model::loadMultiple($cape_subjects, $post_data);
                    $groups_load_flag = Model::loadMultiple($subject_groups, $post_data);
                }
                else
                {
                    $subjects_load_flag = true;
                    $groups_load_flag = true;
                }

                if($offerings_load_flag == true  &&  $subjects_load_flag == true  &&  $groups_load_flag == true)
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try 
                    {
                        if($saved_offerings == true)    //if previous offering exists in database
                        {
                            AcademicOffering::deleteAll(['and', 'applicationperiodid = ' . $period->applicationperiodid, 'programmecatalogid != 10']);
                        }

                        $index = 0;
                        foreach ($offerings as $offering) 
                        {
                            //Checkbox for this programme is ticked
                            if ($offering->programmecatalogid != false)
                            {
                                $none_cape_check = true;

                                //Checkbox for this programme is ticked
                                $ao_model = new AcademicOffering();
                                $ao_model->programmecatalogid = $programmes[$index]->programmecatalogid;
                                $ao_model->academicyearid = $period->academicyearid;
                                $ao_model->applicationperiodid = $period->applicationperiodid;
                                $ao_model->spaces = $offering->spaces;
                                $ao_model->interviewneeded = $offering->interviewneeded;
                                $offering_save_flag = $ao_model->save();
                                if($offering_save_flag == false)
                                {
                                    $transaction->rollBack();
                                    if($saved_offerings == true)    //if previous offering exist in database
                                    {
                                        AcademicOffering::restore($offering_copy);
                                    }
                                    Yii::$app->getSession()->setFlash('error', 'Academic Offering was not saved.');
                                    $operation_successful = false;
                                }
                            }
                            $index++;
                        }

                        $cape_model = NULL;
                        $cape_selected = false;

                        if($period->divisionid == 4)
                        {
                            foreach ($cape_subjects as $cape_subject)
                            {
                                //if checkbox is 'checked'
                                if ($cape_subject->subjectname != false)
                                {
                                    $cape_check = true;
                                    $cape_selected = true;

                                    if ($saved_cape_offering == false)  //if no CAPE academic-offering exists it must be created
                                    {
                                        $cape_model = new AcademicOffering();
                                        $cape_model->programmecatalogid = 10;
                                        $cape_model->academicyearid = $period->academicyearid;
                                        $cape_model->applicationperiodid = $period->applicationperiodid;
                                        $cape_model->spaces = NULL;
                                        $cape_model->interviewneeded = 0;
                                        $save_flag = $cape_model->save();
                                        if($save_flag == false)
                                        {
                                            $transaction->rollBack();
                                            if($saved_offerings == true)    //if previous offering exist in database
                                            {
                                                AcademicOffering::restore($offering_copy);
                                            }
                                            Yii::$app->getSession()->setFlash('error', 'CAPE Academic Offering was not saved.');
                                            $operation_successful = false;
                                        }
                                    }
                                    break;
                                }
                            }
                        }

                        if($cape_selected == false)      //if CAPE academic offering is not under selection
                        {
                            if ($saved_cape_offering == true  && $saved_cape_subjects == true  && $saved_subject_groups == true)       //if previous CAPE offering exists
                            {
                                $groups_candidates = CapeSubjectGroup::getAssociatedCapeGroups($period->applicationperiodid);
                                CapeSubjectGroup::deleteGroups($groups_candidates);
                                CapeSubject::deleteAll(['academicofferingid' => $saved_cape_offering->academicofferingid]);
                                AcademicOffering::deleteAll(['applicationperiodid' => $period->applicationperiodid, 'programmecatalogid' => 10]);
                            }
                        }
                        else    //if CAPE academic offering is present selection
                        {
                            if ($saved_cape_offering == true)
                            {
                                $groups_candidates = CapeSubjectGroup::getAssociatedCapeGroups($period->applicationperiodid);
                                CapeSubjectGroup::deleteGroups($groups_candidates);
                                CapeSubject::deleteAll(['academicofferingid' => $saved_cape_offering->academicofferingid]);
                            }

                            $counter = 0;
                            foreach ($cape_subjects as $cape_subject) 
                            {
                                //Checkbox for this cape_subject is ticked
                                if ($cape_subject->subjectname != false)
                                {
                                    $c_model = new CapeSubject();
                                    $c_model->cordinatorid = NULL;

                                    if ($saved_cape_offering == true)
                                        $c_model->academicofferingid = $saved_cape_offering->academicofferingid;
                                    else
                                        $c_model->academicofferingid = $cape_model->academicofferingid;

                                    $c_model->subjectname = $subjects[$counter]["name"];
                                    $c_model->unitcount = $cape_subject->unitcount;
                                    $c_model->capacity = $cape_subject->capacity;
                                    $subjects_save_flag = $c_model->save();
                                    if($subjects_save_flag == false)        ///if save has failed
                                    {
                                        $transaction->rollBack();
                                        if($saved_cape_subjects == true)    //if previous offering exist in database
                                        {
                                            CapeSubject::restore($cape_subjects_copy);
                                        }
                                        if($saved_offerings == true)    //if previous offering exist in database
                                        {
                                            AcademicOffering::restore($offering_copy);
                                        }
                                        Yii::$app->getSession()->setFlash('error', 'Cape subjects was not saved.');
                                        $operation_successful = false;
                                    }
                                    else        //if save is successful create associated groups
                                    {
                                        $group_flag = false;
                                        $g_model = new CapeSubjectGroup();
                                        $g_model->capesubjectid = $c_model->capesubjectid;
                                        $g_model->capegroupid = $subject_groups[$counter]->capegroupid;
                                        $group_flag = $g_model->save();
                                        if($group_flag == false)        ///if save has failed
                                        {
                                            $transaction->rollBack();
                                            if($saved_subject_groups == true)
                                                CapeSubjectGroup::restore($groups_copy);

                                            if($saved_cape_subjects == true)    //if previous offering exist in database
                                                CapeSubject::restore($cape_subjects_copy);

                                            if($saved_offerings == true)    //if previous offering exist in database
                                                AcademicOffering::restore($offering_copy);
                                            Yii::$app->getSession()->setFlash('error', 'Cape subjects was not saved.');
                                            $operation_successful = false;
                                        }
                                    }
                                }
                                $counter++;
                            }
                        }

                        //update application period record accordingly
                        if($cape_check == false && $none_cape_check == false)
                            $period->applicationperiodstatusid = 3;

                        elseif($cape_check == true || $none_cape_check == true)
                            $period->applicationperiodstatusid = 4;

                        $period_save_flag = $period->save();
                        if ($period_save_flag == false)
                        {
                            $transaction->rollBack();
                            if($saved_offerings == true)    //if previous offering exist in database
                            {
                                AcademicOffering::restore($offering_copy);
                            }
                            if($saved_offerings == true)    //if previous offering exist in database
                            {
                                AcademicOffering::restore($offering_copy);
                            }
                            Yii::$app->getSession()->setFlash('error', 'Academic Offering was not saved.');
                            $operation_successful = false;
                        }

                        if ($operation_successful == true)
                        {
                            $transaction->commit();
                            return $this->redirect(['view-application-period', 'id' => $period->applicationperiodid]);
                        }

                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error loading records.');
                }
            }

            $offerings_limit = ($programme_count > $subject_count)? $programme_count : $subject_count ;
             
            return $this->render('manage_programme_offerings', [
                'period' => $period,
                'programmes' => $programmes,
                'programme_count' => count($programmes),
                'subjects' => $subjects,
                'subject_count' => count($subjects),
                'offerings_limit' => $offerings_limit,
                'offerings' => $offerings,
                'cape_subjects' => $cape_subjects,
                'subject_groups' => $subject_groups]);
        }
        
        
        /**
         * Adds new ProgrammeCatalog record
         * 
         * @param Integer $id
         * @return view 'add_programme'
         * 
         * Author: charles.laurence1@gmail.com
         *  Created: 2017_09_12
         *  Modified: 2017_10_09
         */
        public function actionAddProgrammeToCatalog($id = NULL)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            if ($id == NULL)
            {
                $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
            }
            else
            {
                 $period = ApplicationPeriod::getApplicationPeriod($id);
            }
            
            $programme = new ProgrammeCatalog();
            
            $duration = [
                '' => 'Select Duration', 
                1 => '1 Year', 
                2 => '2 Years'];
            
            $intent_types = IntentType::find()->all();
            $qualification_types = QualificationType::find()->all();
            $examination_bodies = ExaminationBody::find()->all();
            $departments = Department::find()
                    ->where(['not', ['like', 'name', 'Administrative']])
                    ->andWhere(['not', ['like', 'name', 'Library']])
                    ->andWhere(['not', ['like', 'name', 'Senior']])
                    ->andWhere(['not', ['like', 'name', 'CAPE']])
                    ->all();

            if ($post_data = Yii::$app->request->post())
            {
                if ($programme->load($post_data) == true)
                { 
                    $programme->creationdate = date('Y-m-d');
                    $save_flag = $programme->save();
                    if ($programme->save() == true)
                    {
                        if ($id == NULL)
                        {
                            return $this->redirect(['period-setup-step-three']);
                        }
                        else
                        {
                             return $this->redirect(['manage-programme-offerings',  'id' => $period->applicationperiodid]);
                        }
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update programme record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load programme record.');              
                }
            }

            return $this->render('add_programme', [
                'period' => $period,
                'programme' => $programme,
                'duration' => $duration,
                'intent_types' => $intent_types,
                'qualification_types' => $qualification_types,
                'examination_bodies' => $examination_bodies,
                'departments' => $departments,
                'id' => $id]);
        }

        
        /**
         * Adds new Subject record
         * 
         * @param Integer $id
         * @return view 'add_cape_subject'
         * 
         * Author: charles.laurence1@gmail.com
         *  Created: 2017_09_12
         *  Modified: 2017_10_09
         */
        public function actionAddCapeSubject($id = NULL)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            if ($id == NULL)
            {
                $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
            }
            else
            {
                 $period = ApplicationPeriod::getApplicationPeriod($id);
            }
           
            $subject = new Subject();

            if ($post_data = Yii::$app->request->post())
            {
                if ($subject->load($post_data) == true)
                { 
                    $subject->examinationbodyid = 2;
                    if ($subject->save() == true)
                    { 
                        if ($id == NULL)
                        {
                            return $this->redirect(['period-setup-step-three']);
                        }
                        else
                        {
                             return $this->redirect(['manage-programme-offerings',  'id' => $period->applicationperiodid]);
                        }
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update CAPE record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load CAPE record.');
                }
            }

            return $this->render('add_cape_subject', [
                'subject' => $subject,
                'period' => $period,
                'id' => $id]);
        }
        
        
        /**
         * Renders the Application Period Setup Dashboard view
         * 
         * @param Integer $recordid
         * @return view 'period_setup_dashobard'
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2016_02_10
         * Modified: 2017_10_09
         */
        public function actionInitiatePeriod($id = NULL)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            // if initializing new application period
            if ($id == NULL)      
            {
                $period = ApplicationPeriod::createDefaultApplicationPeriod();
                if ($period  == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when initiating application period record.');
                    return $this->redirect(['application-periods/view-periods']);
                }
            }
            // if continuing the configuration of application period
            else            
            {
                $period = ApplicationPeriod::find()
                        ->where(['applicationperiodid' => $id])
                        ->one();
                if ($period == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when retreiving outstanding application period record.');
                    return $this->redirect(['application-periods/view-periods']);
                }
            }

            return $this->render('period_setup_dashobard', [
                'period' => $period,
                'cape_offering_selected' => AcademicOffering::hasCapeOffering($period->applicationperiodid)]);
        }
        
        
        /**
         * Renders the Application Period Setup Year Verification view
         * 
         * @return view 'period_setup_step_one'
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2016_02_10
         * Modified: 2017_10_09
         */
        public function actionPeriodSetupStepOne($divisionid = NULL, $applicationperiodtypeid = NULL)
        {
            $new_year = new AcademicYear();
            $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
            $result_set = array();
            
            if ($divisionid !=NULL && $applicationperiodtypeid != NULL)
            {
                $result_set = ApplicationPeriod::processApplicantIntentid($divisionid, $applicationperiodtypeid);
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                $operation_successful = true;
                $academic_year_exists = NULL;
                $application_period_exists = NULL;
            
                $request = Yii::$app->request;
                $submitted_divisionid = $request->post('divisionid');
                $submitted_applicationperiodtypeid = $request->post('applicationperiodtypeid');
                
                $transaction = \Yii::$app->db->beginTransaction();
                try 
                {
                    if ($divisionid == true && $applicationperiodtypeid == true)
                    {
                        $academic_year_exists = $request->post('academic_year_exists'); 
                        $application_period_exists = $request->post('application_period_exists');

                        // if academic year record needed to be initialized
                        if ($academic_year_exists == 0 && $application_period_exists == 0)
                        {
                            if($new_year->load($post_data) == true)
                            {
                                $applicantintentid = ApplicantIntent::getApplicantIntent($divisionid, $applicationperiodtypeid);
                                $new_year->applicantintentid =  $applicantintentid; 
                                $new_year->iscurrent = 1;
                                $year_save_flag = $new_year->save();
                                if ($new_year->save() == false)
                                {
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured saving new academic year.');
                                    $operation_successful = false;
                                }
                                else
                                {
                                    $period->divisionid = $submitted_divisionid;
                                    $period->applicationperiodtypeid = $submitted_applicationperiodtypeid;
                                    $period->academicyearid = $new_year->academicyearid;
                                    $period->applicationperiodstatusid = 2;
                                    if ($period->save() == false)
                                    {
                                        $transaction->rollBack();
                                        Yii::$app->getSession()->setFlash('error', 'Error occured updating application period.');
                                        $operation_successful = false;
                                    }
                                    
                                    if ($operation_successful == true)
                                    {
                                        $transaction->commit();
                                        return $this->redirect(['initiate-period', 'id' => $period->applicationperiodid]);
                                    }
                                }
                            }
                            else
                            {
                                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load records.');  
                            }
                        }
                        else        //only executes when DASGS  OR DTVE full time application period is is being creatd and its counterpart already exists
                        {
                            $new_year = AcademicYear::find()
                                    ->where(['applicantintentid' => 1,  'iscurrent' => 1, 'isactive' => 1, 'isdeleted' => 0])
                                    ->one();
                            
                            $period->divisionid = $submitted_divisionid;
                            $period->applicationperiodtypeid = $submitted_applicationperiodtypeid;
                            $period->academicyearid = $new_year->academicyearid;
                            $period->applicationperiodstatusid = 2;
                            if ($period->save() == false)
                            {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', 'Error occured updating application period.');
                                $operation_successful = false;
                            }
                            
                            if ($operation_successful == true)
                            {
                                $transaction->commit();
                                return $this->redirect(['initiate-period', 'id' => $period->applicationperiodid]);
                            }
                        }
                   } 
                }catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }

            return $this->render('period_setup_step_one', [
                'new_year' => $new_year,
                'period' => $period,
                'divisionid' => $divisionid,
                'applicationperiodtypeid' => $applicationperiodtypeid,
                'result_set' => $result_set]);
        }
        
        
        /**
         * Renders the Application Period Setup view
         * 
         * @return view 'period_setup_step_two'
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2016_02_10
         * Modified: 2017_10_09
         */
        public function actionPeriodSetupStepTwo()
        {
            $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
            $divisions = Division::find()
                    ->where(['abbreviation' => ["DASGS", "DTVE", "DTE", "DNE"]])
                    ->all();
            $academic_years = AcademicYear::getAllAcademicYears();
            $application_period_types = ApplicationPeriodType::find()->all();
            
            $template_period = new ApplicationPeriod();
            $template_period->divisionid = $period->divisionid;
            $template_period->academicyearid = $period->academicyearid;
            $template_period->applicationperiodtypeid = $period->applicationperiodtypeid;

            if ($post_data = Yii::$app->request->post())
            {
                $load_flag = false;
                $save_flag = false;
                $template_period = new ApplicationPeriod();

                if($template_period->load($post_data) == true)
                { 
                    $period->applicationperiodtypeid = $template_period->applicationperiodtypeid;
                    $period->applicationperiodstatusid = 3;
                    $period->divisionid = $template_period->divisionid;
                    $period->academicyearid = $template_period->academicyearid;
                    $period->name = $template_period->name;
                    $period->onsitestartdate = $template_period->onsitestartdate;
                    $period->onsiteenddate = $template_period->onsiteenddate;
                    $period->offsitestartdate = $template_period->offsitestartdate;
                    $period->offsiteenddate =  $template_period->offsiteenddate;
                    if($period->save() == true)
                    {
                        return $this->redirect(['initiate-period', 'id' => $period->applicationperiodid]);
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update application period record.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load application period record.');              
                }
            }

            if ($period->applicationperiodstatusid > 2)
            {
                return $this->render('period_setup_step_two', [
                    'template_period' => $period,
                    'period' => $period,
                    'divisions' => $divisions,
                    'academic_years' => $academic_years,
                    'application_period_types' => $application_period_types]);
            }
            else
            {
                return $this->render('period_setup_step_two', [
                    'template_period' => $template_period,
                    'period' => $period,
                    'divisions' => $divisions,
                    'academic_years' => $academic_years,
                    'application_period_types' => $application_period_types]);
            }
        }
        
        
         /**
         * Renders the Programme Catalog Approval view
         * 
         * @return view 'period_setup_step_three'
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2016_02_10
         * Modified: 2017_10_09
         */
        public function actionPeriodSetupStepThree($approve = NULL)
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
            
            if($approve  == true)
            {
                $feedback = $period->toggleProgrammeCatalogApproval("approve");
                if ($feedback == true)
                {
                    return $this->redirect(['initiate-period', 'id' => $period->applicationperiodid]);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured approving programme catalog.');        
                }
            }
            else
            {
                $feedback = $period->toggleProgrammeCatalogApproval("reset");
                $programmes = NULL;
                $subjects = NULL;

                $programmes = ProgrammeCatalog::getProgrammeListing($period->divisionid, $period->applicationperiodtypeid); 

                if ($period->divisionid == 4)
                {
                    $subjects = Subject::findAll(['examinationbodyid' => 2, 'isactive' => 1, 'isdeleted' => 0]);
                }
            }
            
             return $this->render('period_setup_step_three', [
                'period' => $period,
                'programmes' => $programmes,
                'subjects' => $subjects]);
        }
        
        
        /**
         * Renders the Assign Programmes view
         * 
         * @return view 'period_setup_step_four' view
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2016_09_27
         * Modified: 2017_10_09
         */
        public function actionPeriodSetupStepFour()
        {
             if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
            $programmes = $period->getAvailableProgrammes();
            $offerings = $period->processProgrammes($programmes);
            
            if ($post_data = Yii::$app->request->post())
            {
                $error_occurred = false;
                $cape_offering_exists = false;
                $offerings_exist = false;
                $user_input_feedback = Model::loadMultiple($offerings, $post_data);

                $transaction = \Yii::$app->db->beginTransaction();
                try 
                {
                    $index = 0;
                    foreach ($offerings as $offering) 
                    {
                        $existing_offer = $period->getAcadmeicOffering($programmes[$index]["programmecatalogid"]);
                        
                        // if input checkbox is selected
                        if ($offering->isactive != false)
                        {
                             $offerings_exist = true;
                             
                             if ($programmes[$index]["programmecatalogid"] == 10)
                            {
                                $cape_offering_exists = true;
                            }
                            
                            // if academic offering does not already exist, it is created
                            if ($existing_offer == false)
                            {
                                $new_offering = new AcademicOffering();
                                $new_offering->programmecatalogid = $programmes[$index]["programmecatalogid"];
                                $new_offering->academicyearid = $period->academicyearid;
                                $new_offering->applicationperiodid = $period->applicationperiodid;
                                $new_offering->spaces = $offering->spaces;
                                $new_offering->interviewneeded = $offering->interviewneeded;
                                $new_offering->credits_required = $offering->credits_required;
                                if ($new_offering->save() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured creating new academic offering.');
                                }
                            }
                            //else update necessary field to take user changes into account
                            else
                            {
                                $existing_offering->spaces = $offering->spaces;
                                $existing_offering->interviewneeded = $offering->interviewneeded;
                                $existing_offering->credits_required = $offering->credits_required;
                                if ($existing_offering->save() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured updating academic offering.');
                                }
                            }
                        }
                        // else if input checkbox is not selected
                        else
                        {
                            // if academic offering previously existed, it is deleted
                            if ($existing_offer == true)
                            {
                                if ($existing_offer->softDelete() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting existing academic offering.');
                                }
                            }
                        }
                        
                        // if rollback is triggerred the processing of the user input is stopped
                        if ($error_occurred == true)
                        {
                            break;
                        }
                         $index += 1;
                    }
                   
                    if ($offerings_exist == true)
                    {
                        $period->programmes_added = 1;

                        /* applicationperiodstatus is incremented if period belongs to the DTVE, DTE or DNE division; thus indicating
                         *  CAPE subject selection is uncessary
                         *  OR
                         * period belongs to DASGS division and CAPE academic offering does not exist
                         * 
                         */
                        if ($period->divisionid != 4 || ($period->divisionid == 4 && $cape_offering_exists == false))
                        {
                            $period->applicationperiodstatusid = 4 ;
                        }
                        elseif ($cape_offering_exists == true && empty($period->getCapeSubjects()) == true)
                        {
                            $period->applicationperiodstatusid = 3 ;
                        }
                        if ($period->save() == false)
                        {
                            $error_occurred = true;
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occured updating application period.');
                        }

                        $transaction->commit();
                        return $this->redirect(['initiate-period', 'id' => $period->applicationperiodid]);
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'No programmes were selected. Please select the programmes that will be offered this academic year.');
                    }

                } catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
  
            return $this->render('period_setup_step_four', [
                    'period' => $period,
                    'programmes' => $programmes,
                    'offerings' => $offerings,
                ]);
        }
        
        
        /**
         * Renders the Assign CAPE Subject view
         * 
         * @return view 'period_setup_step_five'
         * @throws UnauthorizedAccessException
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2016_10_03
         * Modified: 2017_10_09
         */
        public function actionPeriodSetupStepFive()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();
            $cape_groups = $period->getAvailableCapeSubjectGroups();
            $subjects = $period->getAvailableCapeSubjects();
            $cape_subject_data = $period->processCapeSubjectsAndGroups($subjects);
            $cape_subjects = $cape_subject_data[0];
            $subject_groups = $cape_subject_data[1];
            
            if ($post_data = Yii::$app->request->post())
            {
                $error_occurred = false;
                $cape_academic_offering = $period->getCurrentCapeAcademicOffering();
                
                $subjects_load_flag = Model::loadMultiple($cape_subjects, $post_data);
                $groups_load_flag = Model::loadMultiple($subject_groups, $post_data);
                
                $cape_subjects_selected = false;
                 
                $transaction = \Yii::$app->db->beginTransaction();
                try 
                {
                    $index = 0;
                    foreach ($cape_subjects as $cape_subject) 
                    {
                        $existing_cape_subject = $period->getExistingCapeSubjectOffering($subjects[$index]);
                        
                        //Checkbox for this cape_subject is ticked
                        if ($cape_subjects[$index]->isactive != false)
                        {
                            $cape_subjects_selected = true;
                            
                            // if academic offering does not already exist, it is created
                            if ($existing_cape_subject == false)
                            {
                                $new_cape_subject = new CapeSubject();
                                $new_cape_subject->cordinatorid = NULL;
                                $new_cape_subject->academicofferingid = $cape_academic_offering->academicofferingid;
                                $new_cape_subject->subjectname = $subjects[$index]->name;
                                $new_cape_subject->unitcount = $cape_subject->unitcount;
                                $new_cape_subject->capacity = $cape_subject->capacity;
                                if ($new_cape_subject->save() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured creating cape subject '. $subjects[$index]->name . '.');
                                }
                                
                                $new_group = new CapeSubjectGroup();
                                $new_group->capesubjectid = $new_cape_subject->capesubjectid;
                                $new_group->capegroupid = $subject_groups[$index]->capegroupid;
                                if ($new_group->save() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured creating CAPE subject group '. $group->capegroupid . ' for ' . $subjects[$index]->name . '.');
                                }
                            }
                            
                            //else update necessary fields for 'cape_subject' record and its associated group
                            // to take user changes into account
                            else
                            {
                                $existing_cape_subject->unitcount = $cape_subject->unitcount;
                                $existing_cape_subject->capacity = $cape_subject->capacity;
                                if ($existing_cape_subject->save() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured creating cape subject '. $subjects[$index]->name . '.');
                                }
                                $existing_group = CapeSubjectGroup::find()
                                        ->where(['capesubjectid' =>  $existing_cape_subject->capesubjectid, 'isactive' => 1, 'isdeleted' => 0])
                                        ->one();
                                if ($existing_group->save() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured creating CAPE subject group '. $group->capegroupid . ' for ' . $subjects[$index]->name . '.');
                                }
                            }
                        }
                        
                        // else if input checkbox is not selected
                        else
                        {
                            // if cape subject previously existed, it is deleted
                            if ($existing_cape_subject == true)
                            {
                                if ($existing_cape_subject->softDelete() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting existing cape subject.');
                                }
                                $existing_group = CapeSubjectGroup::find()
                                        ->where(['capesubjectid' =>  $existing_cape_subject->capesubjectid, 'isactive' => 1, 'isdeleted' => 0])
                                        ->one();
                                if ($existing_group->save() == false)
                                {
                                    $error_occurred = true;
                                    $transaction->rollBack();
                                    Yii::$app->getSession()->setFlash('error', 'Error occured creating CAPE subject group '. $group->capegroupid . ' for ' . $subject->name . '.');
                                }
                            }
                        }
                       
                        // if rollback is triggerred the processing of the user input is stopped
                        if ($error_occurred == true)
                        {
                            break;
                        }
                         $index += 1;
                    }
                        
                    if ($cape_subjects_selected == true)
                    {
                        $period->cape_subjects_added = 1;
                        $period->applicationperiodstatusid = 4 ;
                        if ($period->save() == false)
                        {
                            $error_occurred = true;
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', 'Error occured updating application period.');
                        }

                        $transaction->commit();
                        return $this->redirect(['initiate-period', 'id' => $period->applicationperiodid]);
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'No cape subjects were selected. Please select the CAPE subjects that will be offered this academic year.');
                    }
                } catch (Exception $ex) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
                }
            }
  
            return $this->render('period_setup_step_five', [
                    'period' => $period,
                    'subjects' => $subjects,
                    'cape_subjects' => $cape_subjects,
                    'subject_groups' => $subject_groups,
                    'cape_groups' => $cape_groups
                ]);
        }
        
         
        /**
         * Confirms Application period setting
         * 
         * @return view 'manage-application-period' || 'initiate-period'
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 2016_02_10
         * Modified: 2016_10_09
         */
        public function actionPeriodSetupConfirm()
        {
            if (Yii::$app->user->can('System Administrator') == false)
            {
                throw new UnauthorizedAccessException();
            }
            
            $period = ApplicationPeriod::getUnconfiguredAppplicationPeriod();

            if($period)
            {
                $period->applicationperiodstatusid = 6;
                $period->isactive = 1;
                $period->isdeleted = 0;
                if($period->save() == true)
                {
                     Yii::$app->getSession()->setFlash('success', 'Application period configuration is complete.');
                     return $this->redirect(['manage-application-period']);
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured when confirming application period settings.');
                    return $this->redirect(['initiate-period', 'recordid' => $period->applicationperiodid]);
                }
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when loading application period record.');
                return $this->redirect(['initiate-period', 'recordid' => $period->applicationperiodid]);
            }
        }


        /**
         * Deletes an application period
         * 
         * @param Integer $personid
         * @return view 'periods'
         * 
         * Author: charles.laurence1@gmail.com
         * Created: 21/03/2016
         * Modified: 2016_10_09
         */
        public function actionDeleteApplicationPeriod($id)
        {
            $period = ApplicationPeriod::getApplicationPeriod($id);
            if ($period == true)
            {
                $period->isdeleted = 1;
                $period->isactive = 0;
                if($period->save() == false)
                {
                    Yii::$app->getSession()->setFlash('error', 'Error occured deleting ApplicationPeriod record ID => ' . $period . "not found.");    
                }
            }
            else
            {
                $error_message = "ApplicationPeriod record with AcademicYear ->ID= " . $period;
                throw new ModelNotFoundException($error_message);
            }
            return $this->redirect(\Yii::$app->request->getReferrer());
        }
        
        
    }
