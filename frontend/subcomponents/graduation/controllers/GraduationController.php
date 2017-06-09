<?php

    namespace app\subcomponents\graduation\controllers;
    
    use Yii;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    
    use common\models\User;
    use frontend\models\Division;
    use frontend\models\ProgrammeCatalog;
    use frontend\models\QualificationType;
    use frontend\models\Department;
    use frontend\models\ExaminationBody;
    use frontend\models\IntentType;
    use frontend\models\CourseOffering;
    use frontend\models\CourseCatalog;
    use frontend\models\GraduationProgrammeCourse;
    use frontend\models\StudentRegistration;
    use frontend\models\AcademicYear;
    use frontend\models\GraduationReport;
    use frontend\models\GraduationReportItem;
    use frontend\models\Employee;
    use frontend\models\AcademicOffering;
    use frontend\models\CourseOffering;
    use frontend\models\BatchStudent;
    
    class GraduationController extends Controller
    {
        
        // (laurence_charles) - Generates list of courses that must be successfully completed for student to graduate
        public function actionProgrammeGraduationRequirements($division_id = NULL)
        {
            if (!Yii::$app->user->can('Deputy Dean') || !Yii::$app->user->can('Dean')  || !Yii::$app->user->can('Registrar'))
            {
                 Yii::$app->getSession()->setFlash('error', 'You are not authorized to perform the selected action. Please contact System Administrator.');
                 return $this->redirect(['/site/index']);
            }
            
            $programme_catalog_dataprovider = array();
            $info_string = "";
            
            if ($division_id != NULL)
            {
                $info_string .= Division::getDivisionAbbreviation($division_id);
               
                
                $programme_container = array();
                $programme_info = array();
                
                $programmes = ProgrammeCatalog::getProgrammes($division_id);
                if ($programmes)
                {
                    foreach ($programmes as $programme)
                    {
                        $programme_info['programmecatalogid'] = $programme->programmecatalogid;
                        
                        $qualificationtype = QualificationType::find()
                                ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()->abbreviation;
                        $programme_info['qualificationtype'] = $qualificationtype;
                        
                        $p_name = $programme->name;
                        if ($programme->programmetypeid == 1)
                        {
                            $programme_info['name'] = $programme->name . " (FT)";
                        }
                        if ($programme->programmetypeid == 2)
                        {
                            $programme_info['name'] = $programme->name . " (PT)";
                        }
                        
                        $programme_info['division_id'] = $division_id;
                        
                        $programme_info['specialisation'] = $programme->specialisation;
                        
                        $department = Department::find()
                                ->where(['departmentid' => $programme->departmentid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()->name;
                        $programme_info['department'] = $department;
                        
                        $exambody = ExaminationBody::find()
                                ->where(['examinationbodyid' => $programme->examinationbodyid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()->abbreviation;
                        $programme_info['exambody'] = $exambody;
                        
                        $programmetype = IntentType::find()
                                ->where(['intenttypeid' => $programme->programmetypeid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()
                                ->name;
                        $programme_info['programmetype'] = $programmetype;
                       
                        $programme_info['duration'] = $programme->duration;
                        $programme_info['creationdate'] = $programme->creationdate;
 
                        $programme_container[] = $programme_info;
                    }
                }
                
                $programme_catalog_dataprovider = new ArrayDataProvider([
                            'allModels' => $programme_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => [ 'name' => SORT_ASC],
                                'attributes' => ['programmetype', 'name'],
                            ]
                    ]);
            }
            
            return $this->render('programme_graduation_requirements_dashboard', 
                    ['division_id' => $division_id,
                        'info_string' => $info_string,
                        'programme_catalog_dataprovider' => $programme_catalog_dataprovider]);
        }
        
        
        // (laurence_charles) - View catalog of courses required for student to graduate from a programme
        public function actionViewCourseCatalog($division_id, $programmecatalog_id)
        {
            $programme = ProgrammeCatalog::find()
                    ->where(['programmecatalogid' => $programmecatalog_id, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $programme_name = $programme->name;
            if ($programme->specialisation)
            {
                 $programme_name .= " (" . $programme->specialisation . ")";
            }
            
            if ($programme->programmetypeid == 1)
            {
                 $programme_name .=  " - (Full Time)";
            }
            if ($programme->programmetypeid == 2)
            {
                $programme_name .=  " - (Part Time)";
            }
            
            $approved_courses = array();
            $course_container = array();
            $course_info = array();
            $db = Yii::$app->db;
                
            // if selected programme is CAPE
            if ($programmecatalog_id == 10)
            {
                // retreives all Cape courses that have ever been delivered
//                $courses = CapeCourse::find()
//                         ->innerJoin('batch_cape', '`cape_course`.`capecourseid` = `batch_cape`.`capecourseid`')
//                         ->innerJoin('batch_student_cape', '`batch_cape`.`batchcapeid` = `batch_student_cape`.`batchcapeid`')
//                         ->where(['cape_course.isactive' => 1, 'cape_course.isdeleted' => 0,
//                                        'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0,
//                                        'batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0])
//                         ->all();
//                
//                foreach ($courses as $course)
//                {
//                    $course_info['code'] = $course->coursecode;
//                    $course_info['name'] = $course->name;
//                    $cape_unit = CapeUnit::find()
//                                        ->where(['capeunitid' => $course->capeunitid, 'isactive' => 1, 'isdeleted' => 0])
//                                        ->one();
//                    $course_info['subject'] = CapeSubject::find()
//                                        ->where(['capeunitid' => $cape_unit->capeunitid, 'isactive' => 1, 'isdeleted' => 0])
//                                        ->one();
//                    $course_info['unit'] = $cape_unit->title;
//                    $course_info['semester'] =  Semester::find()
//                                                            ->where(['capesubjectid' => $cape_unit->capesubjectid, 'isactive' => 1, 'isdeleted' => 0])
//                                                            ->one()
//                                                            ->title;       
//                    $course_info['courseworkweight'] = $course->courseworkweight;
//                    $course_info['examweight'] = $course->examweight;
//                    $course_container[] = $course_info;
//                }
            }
            
            // if selected programme is not CAPE
            else
            {
                $courses = CourseOffering::find()
                        ->innerJoin('academic_offering', '`course_offering`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                       ->innerJoin('batch', '`course_offering`.`courseofferingid` = `batch`.`courseofferingid`')
                        ->innerJoin('batch_students', '`batch`.`batchid` = `batch_students`.`batchid`')
                        ->where(['course_offering.isactive' => 1, 'course_offering.isdeleted' => 0,
                                         'academic_offering.programmecatalogid' => $programmecatalog_id, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                        'batch.isactive' => 1, 'batch.isdeleted' => 0,
                                        'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,])
                        ->groupBy('course_offering.coursecatalogid')
                        ->all();
                foreach ($courses as $course)
                {
                    $couse_catalog = CourseCatalog::find()
                            ->where(['coursecatalogid' => $course->coursecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $course_info['coursecode'] = $couse_catalog->coursecode;
                    $course_info['name'] = $couse_catalog->name;
                    
                    $graduation_requirement = GraduationProgrammeCourse::find()
                            ->where(['programmecatalogid' => $programmecatalog_id, 
                                            'coursecatalogid' => $course->coursecatalogid,
                                           'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $record_exists = $graduation_requirement;
                    
                    // attempts creation of graduation requirement until it is successful
                    while ($record_exists == false)
                    {
                        $graduation_requirement = new GraduationProgrammeCourse();
                        $graduation_requirement->coursecatalogid = $course->coursecatalogid;
                        $graduation_requirement->programmecatalogid = $programmecatalog_id;
                        $graduation_requirement->save();
                        $record_exists = GraduationProgrammeCourse::find()
                            ->where(['programmecatalogid' => $programmecatalog_id, 
                                            'coursecatalogid' => $course->coursecatalogid,
                                            'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    }
                    $course_container[] = $course_info;
                }
                
                $course_catalog_dataprovider  = new ArrayDataProvider([
                            'allModels' => $course_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['coursecode' => SORT_ASC],
                                'attributes' => ['coursecode', 'name'],
                            ]
                    ]);  
                
                return $this->render('course_catalog_for_programme', 
                    ['division_id' => $division_id,
                        'programme_name' => $programme_name,
                        'course_catalog_dataprovider' => $course_catalog_dataprovider]);
            }
        }
        
        
   
         // (laurence_charles) - Facilitates generation of graduation_reports for a particular programme
        public function actionGenerateGraduationReports($division_id = NULL, $academic_year_id = NULL, $programmecatalog_id = NULL)
        {
            $academic_years = array();
            $programmes = array();
            $programme_name = NULL;
            $graduation_reports_dataprovider = array();
            
            if ($division_id != NULL)
            {
                if ($division_id == 4 || $division_id == 5)
                {
                    $years = AcademicYear::find()
                            ->where(['applicantintentid' => 1, 'isactive' => 1, 'isdeleted' => 0])
                            ->all();
                    foreach ($years as $year)
                    {
                        $academic_years[$year->academicyearid] = $year->title;
                    }
                }
            } 
            
            if ($academic_year_id != NULL)
            {
                $programme_records = ProgrammeCatalog::find()
                        ->joinWith('department')
                        ->where(['department.divisionid' => $division_id, 'programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0])
                        ->andWhere(['not', ['programme_catalog.name' => 'CAPE']])
                        ->all();
                if ($programme_records)
                {
                    foreach ($programme_records as $programme)
                    {
                        $programme_name = $programme->name;
                        if ($programme->specialisation)
                        {
                             $programme_name .= " (" . $programme->specialisation . ")";
                        }

                        if ($programme->programmetypeid == 1)
                        {
                             $programme_name .=  " - (Full Time)";
                        }
                        if ($programme->programmetypeid == 2)
                        {
                            $programme_name .=  " - (Part Time)";
                        }
                        $programmes[$programme->programmecatalogid] = $programme_name;
                    }
                }
            } 
            
            if ($programmecatalog_id != NULL)
            {
                $reports_container = array();
                $reports_info = array();
                $programme_name = ProgrammeCatalog::find()->where(['programmecatalogid' => $programmecatalogid])->one()->name;
                
                // retreive all registrations belonging to students who have not graduated that are not graduated (i.e. student_status = 'current')
                $possible_registrations = StudentRegistration::find()
                        ->innerJoin('academic_offering', '`student_registration`.`academicofferingid` = `academic_offering`.`academicofferingid`')
                        ->where(['student_registration.studentstatusid' => 1, 'student_registration.isactive' => $academic_year_ids, 'student_registration.isdeleted' => 0,
                                        'academic_offering.programmecatalogid' => $programmecatalogid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                        ->all();
                
                // filter for students who were enrolled at minimum two year before current year
                $academic_year_title = AcademicYear::find()->where(['academicyearid' => $academic_year_id])->one()->title;
                $current_year = (int) substr($academic_year_title, -4 , 4);
                foreach ($possible_registrations as $key => $possible_registration)
                {
                    $academic_year_entry = AcademicYear()
                         ->innerJoin('academic_offering', '`academic_year`.`academiyearid` = `academic_offering`.`academicyearid`')
                        ->where(['academic_year.isactive' => $academic_year_ids, 'academic_year.isdeleted' => 0,
                                        'academic_offering.academicofferingid' => $possible_registration->academicofferingid, 'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0])
                        ->one()
                        ->title;
                    $entry_year = (int) substr($academic_year_entry, 0, 4);
                    if ($current_year  - $entry_year < 2)
                    {
                        unset($possible_registrations[$key]);
                    }
                }
                
                // look for students graduation report and if it doesn't exist, if must be created
                foreach ($possible_registrations as $possible_registration)
                {
                    $graduation_report = GraduationReport::find()
                            ->where(['studentregistrationid' => $possible_registration->studentregistrationid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $existing_report = $graduation_report;
                    while ($existing_report == false)
                    {
                        /**** construct graduation report *****/
                        $existing_report = $this->createGraduationReport($possible_registration->studentregistrationid, $possible_registration->personid, $possible_registration->academicofferingid);
                    }
                    
                    /********************************* package existing_reports in dataprovider *********************************/
                    $reports_info["graduationreportid"] = $existing_report->graduationreportid;
                    $reports_info["personid"] = $existing_report->personid;
                    $reports_info["studentregistrationid"] = $existing_report->studentregistrationid;
                    $reports_info["title"] = $existing_report->title;
                    $reports_info["firstname"] = $existing_report->firstname;
                    $reports_info["middlenames"] = $existing_report->middlenames;
                    $reports_info["lastname"] = $existing_report->lastname;
                    $reports_info["programme"] = $existing_report->programme;
                    $reports_info["total_credits"] = $existing_report->total_credits;
                    $reports_info["total_courses_passed"] = $existing_report->total_passes;
                    $reports_info["is_eligble"] = $existing_report->iseligible == 1 ? "Eligible" : "Not Eligible";
                    
                    // if approvedby = 2184 => System Generated
                    if ($existing_report->approvedby == 2184)
                    {
                        $reports_info["approvedby"] = "System Generated" ;
                    }
                    else
                    {
                        $reports_info["approvedby"] = User::getFullName($existing_report->approvedby) ;
                    }
                    
                    $reports_container[] = $reports_info;
                }
                
                $graduation_reports_dataprovider = new ArrayDataProvider([
                            'allModels' => $reports_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => [ 'name' => SORT_ASC],
                                'attributes' => ['programmetype', 'name'],
                            ]
                    ]);
            } 
            
            return $this->render('generate_graduation_reports', 
                    ['division_id' => $division_id,
                        'academic_year_id' => $academic_year_id,
                        'academic_years' => $academic_years,
                        'programmecatalog_id' => $programmecatalog_id,
                        'programme_name' => $programme_name,
                        'programmes' => $programmes,
                        'graduation_reports_dataprovider' => $graduation_reports_dataprovider]);
        }
        
        
        // (laurence_charles) - View prospective graduant report
        public function actionReviewStudenGraduationReport($division_id, $programmecatalog_id,  $graduation_report_id)
        {
            
            return $this->render('review_student_graduation_reports', 
                    ['division_id' => $division_id,
                        'programme_name' => $programme_name,
                        'graduation_report_id' => $graduation_report_id,
                        'graduation_course_items' => $graduation_course_items]);
        }
        
        
        
        private function createGraduationReport($studentregistrationid, $personid, $academicofferingid)
        {
            $graduation_report = new GraduationReport();
            $graduation_report->personid = $personid;
            $graduation_report->studentregistrationid = $studentregistrationid;
            
            $employee = Employee::find()->where(['personid' => $personid])->one();
            $graduation_report->title = $employee->title;
            $graduation_report->firstname = $employee->firstname;
            $graduation_report->middlenames = $employee->middlename;
            $graduation_report->lastname = $employee->lastname;
            
            $graduation_report->programme = ProgrammeCatalog::getProgrammeName($academicofferingid);
            
            // courses that are on the list of required courses for graduation from programme student is enrolled in
            $academic_offering = AcademicOffering::find()
                    ->where(['academicofferingid' => $academicofferingid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            
            $total_credits = 0;
            $total_passes = 0;
            
            $courses_required_for_graduation = GraduationProgrammeCourse::find()
                    ->where(['programmecatalogid' => $academic_offering->programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                    ->all();
            $total_courses = count($courses_required_for_graduation); 
            foreach ($courses_required_for_graduation as $course_required)
            {
                // there may be more than one BatchStudent record as student may have resat course numerous times
                $course_sittings_for_students = BatchStudent::find()
                        ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                        ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                         ->where(['batch_students.studentregistrationid' => $studentregistrationid, 'batch_students.isactive' => 1, 'batch_students.isdeleted'=> 0,
                                        'batch.isactive' => 1, 'batch.isdeleted'=> 0,
                                        'course_offering.coursecatalogid' =>  $course_required->coursecatalogid, 'course_offering.isactive' => 1, 'course_offering.isdeleted'=> 0])
                        ->all();
                
                // iterate through sittings to try to find a sitting that was passed
                foreach ($course_sittings_for_students as $course_sitting)
                {
                    if ($course_sitting->grade == true && $course_sitting->grade != "F" && $course_sitting->grade != "INC")
                    {
                        $total_passes += 1;
                        $course_details = CourseOffering::find()
                                ->innerJoin('batch', '`course_offering`.`courseofferingid` = `batch`.`courseofferingid`')
                                ->where(['course_offering.isactive' => 1, 'course_offering.isdeleted' => 0,
                                               'batch.batchid' =>  $course_sitting->batchid, 'batch.isactive' => 1, 'batch.isdeleted' => 0])
                                ->one();
                        if ($course_details == true)
                        {
                            $total_credits += $course_details->credits;
                        }
                        break;
                    }
                }
            }
                
            $graduation_report->total_credits = $total_credits;
            $graduation_report->total_passes = $total_passes;
           
            if ($academic_offering->required_credits != NULL  && $total_credits >= $academic_offering->required_credits)
            {
                $graduation_report->iseligible =  1;
                $graduation_report->approvedby =  2184;
            }
            else
            {
                $graduation_report->iseligible =  0;
            }
            
            return $graduation_report->save();
        }
        
    }

