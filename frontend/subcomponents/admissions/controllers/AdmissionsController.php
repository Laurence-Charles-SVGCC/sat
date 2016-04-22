<?php

    namespace app\subcomponents\admissions\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\base\Model;
    use yii\data\ArrayDataProvider;
    
    use common\models\User;
    use frontend\models\ApplicationPeriod;
    use frontend\models\AcademicYear;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\CapeSubject;
    use frontend\models\Subject;
    use frontend\models\CapeGroup;
    use frontend\models\CapeSubjectGroup;
    use frontend\models\AcademicOffering;
    use frontend\models\EmployeeDepartment;
    use frontend\models\Email;
    use frontend\models\Applicant;

class AdmissionsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    /**
     * Renders the Application Period Summary view
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 08/02/2016
     * Date Last Modified: 08/02/2016 | 23/02/2016
     */
    public function actionManageApplicationPeriod()
    {
        $periods = ApplicationPeriod::getAllApplicationPeriods();
        
        if (count($periods) == 0)
            $container = false;
        else
        {
            $container = array();

            $keys = array();
            array_push($keys, 'id');
            array_push($keys, 'name');
            array_push($keys, 'division');
            array_push($keys, 'year');
            array_push($keys, 'onsitestartdate');
            array_push($keys, 'onsiteenddate');
            array_push($keys, 'offsitestartdate');
            array_push($keys, 'offsiteenddate');
            array_push($keys, 'type');
            array_push($keys, 'status');
            array_push($keys, 'creator');
            array_push($keys, 'iscomplete');
            

            foreach ($periods as $period)
            {
                $values = array();
                $row = array();
                
                array_push($values, $period["id"]);
                array_push($values, $period["name"]);
                array_push($values, $period["division"]);
                array_push($values, $period["year"]);
                array_push($values, $period["onsitestartdate"]);
                array_push($values, $period["onsiteenddate"]);
                array_push($values, $period["offsitestartdate"]);
                array_push($values, $period["offsiteenddate"]);
                array_push($values, $period["type"]);
                array_push($values, $period["status"]);
                $creator = $period["emptitle"] . ". " . $period["firstname"] . " " . $period["lastname"];       
                array_push($values, $creator);
                array_push($values, $period["iscomplete"]);
                $row = array_combine($keys, $values);
                array_push($container, $row);

                $values = NULL;
                $row = NULL;
            }
        }
        
        return $this->render('period_summary', 
            [
                'periods' => $container,
            ]);
    }
    
    
    /**
     * Renders the Application Period Setup Dashboard view
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/02/2016
     * Date Last Modified: 10/02/2016
     */
    public function actionInitiatePeriod($recordid = NULL)
    {
        if ($recordid == NULL)      //if no outstanding application period session exists for user
        {
            $save_flag = false;
            $personid = Yii::$app->user->identity->personid;
            $period = new ApplicationPeriod();
            
            $period->applicationperiodtypeid = 1;
            $period->applicationperiodstatusid = 1;
            $period->divisionid = 4;
            $period->personid = $personid;
            $period->academicyearid = 4;
            $period->name = strval(date('Y'));
            $period->onsitestartdate = date('Y-m-d');
            $period->onsiteenddate = date('Y-m-d');
            $period->offsitestartdate = date('Y-m-d');
            $period->offsiteenddate =  date('Y-m-d');
            $period->isactive = 1;
            $period->isdeleted = 1;

            $save_flag = $period->save();
            if ($save_flag == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when initiating application period record. Please try again.');
                return $this->redirect(['manage-application-period']);
            }
        }
        else            //if outstanding application period session exists for user
        {
            $period = ApplicationPeriod::find()
                    ->where(['applicationperiodid' => $recordid])
                    ->one();
            if ($period == false)
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when retreiving outstanding application period record. Please try again.');
                return $this->redirect(['manage-application-period']);
            }
        }
        
        return $this->render('period_setup_dashobard', [
                'period' => $period,
            ]);
    }
    
    
    /**
     * Renders the Application Period Setup Year Verification view
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/02/2016
     * Date Last Modified: 10/02/2016
     */
    public function actionPeriodSetupStepOne($reuse_year = NULL)
    {
        $new_year_save_flag = false;
        $current_year_load_flag = false;
        $current_year_save_flag = false;
        $period_save_flag = false;
        
        $current_year = AcademicYear::getCurrentYear();
        $new_year = new AcademicYear();
        $period = ApplicationPeriod::getIncompletePeriod();
        
        if ($reuse_year == 1)
        {
            /*
             * If openeing a full time application period for DASGS or DTVE the academic year must be updated
             */
            if($period->divisionid == 4  ||  $period->divisionid == 5  && ($period->applicationperiodtypeid == 1))
            {
                $new_year = AcademicYear::getMostRecentlyCreatedYear();
                if($new_year == true)
                {
                    $new_year->iscurrent = 1;
                    $new_year->save();
                }
            }
            $period->applicationperiodstatusid = 2;
            $period_save_flag = $period->save();
            if($period_save_flag == true)
            {
                return $this->redirect(['admissions/initiate-period',
                                'recordid' => $period->applicationperiodid
                            ]);
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update record. Please try again.');
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $year_load_flag = $new_year->load($post_data);
            if($year_load_flag == true)
            {
                $new_year->iscurrent = 0;
//                $new_year->iscurrent = 1;
//                $current_year->iscurrent = 0;
                $period->applicationperiodstatusid = 2;
                
                $new_year_save_flag = $new_year->save();
                $current_year_save_flag = $current_year->save();
                $period_save_flag = $period->save();
                
                if($new_year_save_flag == true  && $current_year_save_flag == true && $period_save_flag == true)
                    return $this->redirect(['admissions/initiate-period',
                                'recordid' => $period->applicationperiodid
                            ]);
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update record. Please try again.');
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load records. Please try again.');              
        }
        
        return $this->render('period_setup_step_one', [
                'current_year' => $current_year,
                'new_year' => $new_year,
                'period' => $period
            ]);
    }
    
    
    /**
     * Renders the Application Period Setup view
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/02/2016
     * Date Last Modified: 10/02/2016
     */
    public function actionPeriodSetupStepTwo()
    {
        $template_period = new ApplicationPeriod();
        $period = ApplicationPeriod::getIncompletePeriod();
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $save_flag = false;
            
            $load_flag = $template_period->load($post_data);
            if($load_flag == true)
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
                $save_flag = $period->save();
                if($save_flag == true)
                    return $this->redirect(['admissions/initiate-period',
                                'recordid' => $period->applicationperiodid
                            ]);
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update application period record. Please try again.');
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load application period record. Please try again.');              
        }
        
        if ($period->applicationperiodstatusid > 2)
        {
            return $this->render('period_setup_step_two', [
                'template_period' => $period,
                'period' => $period
            ]);
        }
        else
        {
            return $this->render('period_setup_step_two', [
                'template_period' => $template_period,
                'period' => $period
            ]);
        }
        
    }
    
    
    /**
     * Renders the Prgoramme Creation Setup view
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/02/2016
     * Date Last Modified: 16/02/2016
     */
    public function actionPeriodSetupStepThree()
    {
        $cape_check = false;
        $none_cape_check = false;
        
        $period = ApplicationPeriod::getIncompletePeriod();
        $year = AcademicYear::getCurrentYear();
            
        $programmes = NULL;
        $all_programmes = NULL;
        $subjects = NULL;
        
        if ($period->divisionid == 4)
        {
            $programmes = ProgrammeCatalog::getProgrammeListing($period->divisionid, $period->applicationperiodtypeid); 
//            $all_programmes = ProgrammeCatalog::getFullProgrammeListing($period->divisionid, $period->applicationperiodtypeid); 
            $subjects = Subject::findAll(['examinationbodyid' => 2, 'isactive' => 1, 'isdeleted' => 0]);
        }
        
        elseif ($period->divisionid == 5)
        {
            $programmes = ProgrammeCatalog::getProgrammeListing($period->divisionid, $period->applicationperiodtypeid); 
//            $all_programmes = ProgrammeCatalog::getFullProgrammeListing($period->divisionid, $period->applicationperiodtypeid); 
        }
        
        elseif ($period->divisionid == 6)
        {
            $programmes = ProgrammeCatalog::getProgrammeListing($period->divisionid, $period->applicationperiodtypeid);
//            $all_programmes = ProgrammeCatalog::getFullProgrammeListing($period->divisionid, $period->applicationperiodtypeid); 
        }
        
        elseif ($period->divisionid == 7)
        {
           $programmes = ProgrammeCatalog::getProgrammeListing($period->divisionid, $period->applicationperiodtypeid);
//           $all_programmes = ProgrammeCatalog::getFullProgrammeListing($period->divisionid, $period->applicationperiodtypeid); 
        }
        
        //process programmes
        $programme_count = count($programmes);
        $offerings = array();
        $saved_offerings = array();
        $offering_copy = array();
        if (AcademicOffering::hasNoneCapeOffering($period->applicationperiodid) == true)
        {
            $saved_offerings = AcademicOffering::getNoneCapeOffering($period->applicationperiodid);
            $offering_copy = AcademicOffering::backUp($saved_offerings);
            for ($j = 0 ; $j < $programme_count ; $j++)
            {
                $selected_offer = AcademicOffering::find()
                            ->where(['programmecatalogid' => $programmes[$j]['id'], 'applicationperiodid' => $period->applicationperiodid])
                            ->one();
                if ($selected_offer == true)
                {
                    $selected_offer->programmecatalogid = 1;        //done to ensure checkbox is 'checked'
                    array_push($offerings, $selected_offer);
                }
                else
                {
                    $offer = new AcademicOffering();
                    array_push($offerings, $offer);
                }
            }
        }
        else
        {
            for ($j = 0 ; $j < $programme_count ; $j++)
            {
                $offer = new AcademicOffering();
                array_push($offerings, $offer);
            }
        }       
           
       //process cape subjects
        $subject_count = count($subjects);
        $cape_subjects = array();
        $saved_cape_offering = NULL;
        $cape_offering_copy = NULL;
        $saved_cape_subjects = array();
        $cape_subjects_copy = array();
        
        $subject_groups = array();
        $group_copy = array();
        $saved_subject_groups = array();
        if (AcademicOffering::hasCapeOffering($period->applicationperiodid) == true)
        {
            $saved_subject_groups = CapeSubjectGroup::getAssociatedCapeGroups($period->applicationperiodid);
            $group_copy = CapeSubjectGroup::backup($saved_subject_groups);
            
            $saved_cape_offering = AcademicOffering::getCapeOffering($period->applicationperiodid);
            $cape_offering_copy = AcademicOffering::backUpSingle($saved_cape_offering);
            
            $saved_cape_subjects = CapeSubject::getCapeSubjects($saved_cape_offering->academicofferingid);
            $cape_subjects_copy = CapeSubject::backUp($saved_cape_subjects);
            
            for ($i = 0 ; $i < $subject_count ; $i++)
            {
                $subject = CapeSubject::find()
                            ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                            ->where(['cape_subject.subjectname' => $subjects[$i]['name'], 'academic_offering.applicationperiodid' => $period->applicationperiodid])
                            ->one();
                if ($subject == true)
                {
                    $subject->subjectname = 1;
                    array_push($cape_subjects, $subject);
                    
                    //prepare appropriate associated capesubjectgroup record
                    $subject_group = CapeSubjectGroup::find()
                                ->where(['capesubjectid' => $subject->capesubjectid])
                                ->one();
                    if($subject_group == true)
                        array_push($subject_groups, $subject_group);
                    else
                    {
                        $subject_group = new CapeSubjectGroup();
                        array_push($subject_groups, $subject_group);
                    }
                }
                else
                {
                    $subject = new CapeSubject();
                    array_push($cape_subjects, $subject);
                    
                    $subject_group = new CapeSubjectGroup();
                    array_push($subject_groups, $subject_group);
                }
            }
        }
        else
        {
            for ($i = 0 ; $i < $subject_count ; $i++)
            {
                $cape = new CapeSubject();
                array_push($cape_subjects, $cape);
                
                $subject_group = new CapeSubjectGroup();
                array_push($subject_groups, $subject_group);
            }
        }  
        
        
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
                    if($saved_offerings == true)    //if previous offering exist in database
                    {
                        $period_id = $period->applicationperiodid;
                        AcademicOffering::deleteAll(['and', 'applicationperiodid = ' . $period_id, 'programmecatalogid != 10']);
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
                            $ao_model->programmecatalogid = $programmes[$index]["id"];
                            $ao_model->academicyearid = $year->academicyearid;
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
                                return $this->render('period_setup_step_three', [
                                    'period' => $period,
                                    'programmes' => $programmes,
//                                    'all_programmes' => $all_programmes,
                                    'subjects' => $subjects,
                                    'offerings' => $offerings,
                                    'cape_subjects' => $cape_subjects,
                                ]);
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
                                    $cape_model->academicyearid = $year->academicyearid;
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
                                        return $this->render('period_setup_step_three', [
                                            'period' => $period,
                                            'programmes' => $programmes,
    //                                        'all_programmes' => $all_programmes,
                                            'subjects' => $subjects,
                                            'offerings' => $offerings,
                                            'cape_subjects' => $cape_subjects,
                                        ]);
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
                                    return $this->render('period_setup_step_three', [
                                        'period' => $period,
                                        'programmes' => $programmes,
//                                        'all_programmes' => $all_programmes,
                                        'subjects' => $subjects,
                                        'offerings' => $offerings,
                                        'cape_subjects' => $cape_subjects,
                                    ]);
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
                                        return $this->render('period_setup_step_three', [
                                            'period' => $period,
                                            'programmes' => $programmes,
    //                                        'all_programmes' => $all_programmes,
                                            'subjects' => $subjects,
                                            'offerings' => $offerings,
                                            'cape_subjects' => $cape_subjects,
                                        ]);
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
                        return $this->render('period_setup_step_three', [
                            'period' => $period,
                            'programmes' => $programmes,
//                            'all_programmes' => $all_programmes,
                            'subjects' => $subjects,
                            'offerings' => $offerings,
                            'cape_subjects' => $cape_subjects,
                        ]);
                    }
                    
                    $transaction->commit();
                    return $this->redirect(['admissions/initiate-period',
                        'recordid' => $period->applicationperiodid
                    ]);
                
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error loading records.');
        }
        
        return $this->render('period_setup_step_three', [
                'period' => $period,
                'programmes' => $programmes,
//                'all_programmes' => $all_programmes,
                'subjects' => $subjects,
                'offerings' => $offerings,
                'cape_subjects' => $cape_subjects,
                'subject_groups' => $subject_groups,
            ]);
    }
    
    /**
     * Manages the process of adding a Programme Catalog record
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 13/02/2016
     * Date Last Modified: 13/02/2016
     */
    public function actionAddProgrammeCatalog()
    {
        $period = ApplicationPeriod::getIncompletePeriod();
        $programme = new ProgrammeCatalog();
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $save_flag = false;
            
            $load_flag = $programme->load($post_data);
            if($load_flag == true)
            { 
                $programme->creationdate = date('Y-m-d');
                $save_flag = $programme->save();
                if($save_flag == true)
                    return $this->redirect(['period-setup-step-three'
                            ]);
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update programme record. Please try again.');
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load programme record. Please try again.');              
        }
        
        return $this->render('add_programme', [
                'programme' => $programme,
                'period' => $period
            ]);
            
    }
    
    
    /**
     * Manages the process of adding a CAPE subject record
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 14/02/2016
     * Date Last Modified: 14/02/2016
     */
    public function actionAddCapeSubject()
    {
        $period = ApplicationPeriod::getIncompletePeriod();
        $subject = new Subject();
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $save_flag = false;
            
            $load_flag = $subject->load($post_data);
            if($load_flag == true)
            { 
                $subject->examinationbodyid = 2;
                $save_flag = $subject->save();
                if($save_flag == true)
                { 
                    return $this->redirect(['period-setup-step-three']);
                }
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to update CAPE record. Please try again.');
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load CAPE record. Please try again.');              
        }
        
        return $this->render('add_cape_subject', [
                'subject' => $subject,
                'period' => $period
            ]);
    }
    
    
    /**
     * Confirms Application period setting
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 10/02/2016
     * Date Last Modified: 16/02/2016
     */
    public function actionPeriodSetupConfirm($recordid)
    {
        $save_flag = false;
        $period = ApplicationPeriod::find()
                ->where(['applicationperiodid' => $recordid])
                ->one();
        if($period)
        {
            $period->applicationperiodstatusid = 5;
            $period->isactive = 1;
            $period->isdeleted = 0;
            $save_flag = $period->save();
            if($save_flag == true)
               return $this->redirect(['manage-application-period']);
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
     * Facilitates search for current applicants
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 24/02/2016
     * Date Last Modified: 24/02/2016
     */
    public function actionFindCurrentApplicant($status)
    {
        $division_id = EmployeeDepartment::getUserDivision();
        
        $dataProvider = null;
        $info_string = null;
        
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $app_id = $request->post('applicantid_field');
            $email = $request->post('email_field');
            $firstname = $request->post('FirstName_field');
            $lastname = $request->post('LastName_field');
        
            //if user initiates search based on applicantid
            if ($app_id)
            {
                $user = User::findOne(['username' => $app_id, 'isdeleted' => 0]);
                $cond_arr['applicant.personid'] = $user? $user->personid : null;
                $info_string = $info_string .  " Applicant ID: " . $app_id;
            }    

            //if user initiates search based on applicant name    
            if ($firstname)
            {
                $cond_arr['applicant.firstname'] = $firstname;
                $info_string = $info_string .  " First Name: " . $firstname; 
            }
            if ($lastname)
            {
                $cond_arr['applicant.lastname'] = $lastname;
                $info_string = $info_string .  " Last Name: " . $lastname;
            }        

            //if user initiates search based on applicant email
            if ($email)
            {
                $email_add = Email::findOne(['email' => $email, 'isdeleted' => 0]);
                $cond_arr['applicant.personid'] = $email_add? $email_add->personid: null;
                $info_string = $info_string .  " Email: " . $email;
            }


            if (empty($cond_arr))
            {
                Yii::$app->getSession()->setFlash('error', 'A search criteria must be entered.');
            }
            else
            {
                $cond_arr['applicant.isactive'] = 1;
                $cond_arr['applicant.isdeleted'] = 0;
                $cond_arr['academic_offering.isactive'] = 1;
                $cond_arr['academic_offering.isdeleted'] = 0;
                $cond_arr['application_period.isactive'] = 1;
                $cond_arr['application_period.iscomplete'] = 0;
                $cond_arr['application.isactive'] = 1;
                $cond_arr['application.isdeleted'] = 0;
                if ($status == "pending")
//                    $cond_arr['application.applicationstatusid'] = [3,4,5,6,7,8,9,10];
                    $cond_arr['application.applicationstatusid'] = [2,3,4,5,6,7,8,9,10];
                else
                {
                    $cond_arr['application.applicationstatusid'] = 9;
                    $cond_arr['offer.isactive'] = 1;  
                    $cond_arr['offer.isdeleted'] = 0;
                    $cond_arr['offer.ispublished'] = 1;
                }

                /*
                 *  If DASGS or DTVE, both divisions are searched
                 *  This is because applicants may apply to both divisions
                 */
                if ($division_id == 4  || $division_id == 5 )
                    $cond_arr['application.divisionid'] = [4,5];

                /*
                 *  If DTE or DNE the applicants are constrained to each division
                 */
                elseif ($division_id == 6  || $division_id == 7 )
                    $cond_arr['application.divisionid'] = $division_id;

                if ($status == "pending")
                {
                    $applicants = Applicant::find()
                                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                                ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                                ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                                ->where($cond_arr)
                                ->groupBy('applicant.personid')
                                ->all();
                }
                else
                {
                    $applicants = Applicant::find()
                            ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                             ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                            ->innerJoin('academic_offering', '`application`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                            ->innerJoin('application_period', '`academic_offering`.`applicationperiodid` = `application_period`.`applicationperiodid`')
                            ->where($cond_arr)
                            ->groupBy('applicant.personid')
                            ->all();
                }
               
                if (empty($applicants))
                {
                    Yii::$app->getSession()->setFlash('error', 'No applicant found matching this criteria.');
                }
                else
                {
                    $data = array();
                    foreach ($applicants as $applicant)
                    {
                        $app = array();
                        $user = $applicant->getPerson()->one();

                        $app['username'] = $user ? $user->username : '';
                        $app['personid'] = $applicant->personid;
                        $app['applicantid'] = $applicant->applicantid;
                        $app['firstname'] = $applicant->firstname;
                        $app['middlename'] = $applicant->middlename;
                        $app['lastname'] = $applicant->lastname;
                        $app['gender'] = $applicant->gender;
                        $app['dateofbirth'] = $applicant->dateofbirth;
                        
                        $info = Applicant::getApplicantInformation($applicant->personid);
                        
                        $app['programme_name'] = $info["prog"];
                        $app['application_status'] = $info["status"];
                        
                        $data[] = $app;
                    }
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $data,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                        'sort' => [
                            'attributes' => ['applicantid', 'firstname', 'lastname'],
                            ],
                    ]);
                }
            }
        }
        
        
        return $this->render('find_current_applicant', 
            [
            'dataProvider' => $dataProvider,
            'status' => $status,
            'info_string' => $info_string,
        ]);
    }
    
    
    
}
