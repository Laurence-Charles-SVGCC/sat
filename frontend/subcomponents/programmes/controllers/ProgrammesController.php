<?php

namespace app\subcomponents\programmes\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\base\ErrorException;

use common\models\User;
use frontend\models\ProgrammeCatalog;
use frontend\models\Division;
use frontend\models\Department;
use frontend\models\QualificationType;
use frontend\models\ExaminationBody;
use frontend\models\IntentType;
use frontend\models\AcademicOffering;
use frontend\models\Batch;
use frontend\models\BatchStudent;
use frontend\models\BatchStudentCape;
use frontend\models\CourseOffering;
use frontend\models\CourseCatalog;
use frontend\models\BatchCape;
use frontend\models\CapeCourse;
use frontend\models\CapeUnit;
use frontend\models\CapeSubject;
use frontend\models\Cordinator;
use frontend\models\Employee;
use frontend\models\EmployeeBatch;
use frontend\models\EmployeeBatchCape;
use frontend\models\CourseOutline;
use frontend\models\AcademicYear;
use frontend\models\BookletAttachment;
use frontend\models\Applicant;
use frontend\models\Offer;
use frontend\models\Application;
use frontend\models\ApplicationCapesubject;
use frontend\models\StudentRegistration;
use frontend\models\Semester;
use frontend\models\CourseType;
use frontend\models\PassCriteria;
use frontend\models\PassFailType;



class ProgrammesController extends Controller
{
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }
    
    
    /**
     * Renders the main programme control dashboard
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 01/06/2016
     * Date Last Modified: 07/06/2016 
     */
    public function actionIndex()
    {
        $info_string = "";
        $programme_dataprovider = array();
        $course_dataprovider = array();
        $divisionid = NULL;
        
        if (Yii::$app->request->post())
        {
            $request = Yii::$app->request;
            $divisionid = $request->post('division_search');
            $programme_name = $request->post('programme_field');
            $course_division = $request->post('course-division');
            $course_department = $request->post('course-department');
            $course_code = $request->post('course-code-field');
            $course_name = $request->post('course-name-field');
            
            //if user initiates search based on division
            if ($divisionid != NULL  && $divisionid != 0 && strcmp($divisionid, "0") != 0)
            {
                $division_name = Division::getDivisionAbbreviation($divisionid);
                $info_string .= " Division - " . $division_name;
                
                $programme_container = array();
                $programme_info = array();
                
                $programmes = ProgrammeCatalog::getProgrammes($divisionid);
                if ($programmes)
                {
                    foreach ($programmes as $programme)
                    {
                        $programme_info['programmecatalogid'] = $programme->programmecatalogid;
                        
                        $qualificationtype = QualificationType::find()
                                ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()->abbreviation;
                        $programme_info['qualificationtype'] = $qualificationtype;
                        
                        $programme_info['name'] = $programme->name;
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
                                ->one()->name;
                        $programme_info['programmetype'] = $programmetype;
                       
                        $programme_info['duration'] = $programme->duration;
                        $programme_info['creationdate'] = $programme->creationdate;
 
                        $programme_container[] = $programme_info;
                    }
                }
                
                $programme_dataprovider = new ArrayDataProvider([
                            'allModels' => $programme_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['programmetype' =>SORT_ASC,  'name' => SORT_ASC],
                                'attributes' => ['programmetype', 'name'],
                            ]
                    ]);
            }
            
            //if user initiates search based on programme name
            elseif ($programme_name != NULL  && strcmp($programme_name, "") != 0)
            {
                $division_name = Division::getDivisionAbbreviation($divisionid);
                $info_string .= " Programme Name: " . $programme_name;
                
                $data_package = array();
                $programme_container = array();
                $programme_info = array();
                
                $programmes = ProgrammeCatalog::find()
                        ->where(['name' => $programme_name,'isactive' => 1, 'isdeleted' => 0])
                        ->all();
                if ($programmes)
                {
                    foreach ($programmes as $programme)
                    {
                        $programme_info['programmecatalogid'] = $programme->programmecatalogid;
                        
                        $qualificationtype = QualificationType::find()
                                ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                                ->one()->abbreviation;
                        $programme_info['qualificationtype'] = $qualificationtype;
                        
                        $programme_info['name'] = $programme->name;
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
                                ->one()->name;
                        $programme_info['programmetype'] = $programmetype;
                       
                        $programme_info['duration'] = $programme->duration;
                        $programme_info['creationdate'] = $programme->creationdate;
 
                        $programme_container[] = $programme_info;
                    }
                }
                
                $programme_dataprovider = new ArrayDataProvider([
                            'allModels' => $programme_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['programmetype' =>SORT_ASC,  'name' => SORT_ASC],
                                'attributes' => ['programmetype', 'name'],
                            ]
                    ]); 
            }
            
            
            //if user initiates search based on course division
            elseif ($course_division != NULL  && $course_division != 0 && strcmp($course_division, "0") != 0)
            {
                $division_name = Division::getDivisionAbbreviation($course_division);
                $info_string .= " Division - " . $division_name;

                $course_container = array();
                $course_info = array();

                $db = Yii::$app->db;
                $courses = $db->createCommand(
                        "SELECT course_catalog.coursecode AS 'code',"
                        ." course_catalog.name AS 'name',"
                        ." course_offering.academicofferingid AS 'academicofferingid'" 
                        ." FROM course_offering" 
                        ." JOIN course_catalog"
                        ." ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                        ." JOIN academic_offering"
                        ." ON course_offering.academicofferingid = academic_offering.academicofferingid"
                        ." JOIN programme_catalog"
                        ." ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                        ." JOIN department"
                        ." ON programme_catalog.departmentid = department.departmentid"
                        ." WHERE course_offering.isactive = 1"
                        ." AND course_offering.isdeleted = 0"
                        ." AND department.divisionid = " . $course_division
                        ." GROUP BY course_offering.coursecatalogid;"
                    )
                    ->queryAll();

                $cape_courses = $db->createCommand(
                        "SELECT cape_course.coursecode AS 'code',"
                        ." cape_course.name AS 'name',"
                        ." cape_subject.academicofferingid AS 'academicofferingid'" 
                        ." FROM cape_course" 
                        ." JOIN cape_unit"
                        ." ON cape_course.capeunitid = cape_unit.capeunitid"
                        ." JOIN cape_subject"
                        ." ON cape_unit.capesubjectid = cape_subject.capesubjectid"
                        ." JOIN academic_offering"
                        ." ON cape_subject.academicofferingid = academic_offering.academicofferingid"
                        ." JOIN programme_catalog"
                        ." ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                        ." JOIN department"
                        ." ON programme_catalog.departmentid = department.departmentid"
                        ." WHERE cape_course.isactive = 1"
                        ." AND cape_course.isdeleted = 0"
                        ." AND department.divisionid = " . $course_division
                        ." GROUP BY cape_course.coursecode;"
                    )
                    ->queryAll();

                if($courses)
                {
                    foreach ($courses as $course)
                    {
                        $course_info['code'] = $course['code'];
                        $course_info['name'] = $course['name'];
                        $course_info['academicofferingid'] = $course['academicofferingid'];
                        $course_info['type'] = 'associate';
                        $course_container[] = $course_info;
                    }
                }

                if($cape_courses)
                {
                    foreach ($cape_courses as $cape_course)
                    {
                        $course_info['code'] = $cape_course['code'];
                        $course_info['name'] = $cape_course['name'];
                        $course_info['academicofferingid'] = $cape_course['academicofferingid'];
                        $course_info['type'] = 'cape';
                        $course_container[] = $course_info;
                    }
                }

                $course_dataprovider = new ArrayDataProvider([
                            'allModels' => $course_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['type' => SORT_ASC, 'code' =>SORT_ASC],
                                'attributes' => ['code', 'type'],
                            ]
                    ]); 
            }
            
            //if user initiates search based on course department
            elseif ($course_department != NULL  && $course_department != 0 && strcmp($course_department, "0") != 0)
            {
                $info_string .= Department::getDeparmentName($course_department);

                $course_container = array();
                $course_info = array();

                $db = Yii::$app->db;
                $courses = $db->createCommand(
                        "SELECT course_catalog.coursecode AS 'code',"
                        ." course_catalog.name AS 'name',"
                        ." course_offering.academicofferingid AS 'academicofferingid'" 
                        ." FROM course_offering" 
                        ." JOIN course_catalog"
                        ." ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                        ." JOIN academic_offering"
                        ." ON course_offering.academicofferingid = academic_offering.academicofferingid"
                        ." JOIN programme_catalog"
                        ." ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                        ." WHERE course_offering.isactive = 1"
                        ." AND course_offering.isdeleted = 0"
                        ." AND programme_catalog.departmentid = " . $course_department
                        ." GROUP BY course_offering.coursecatalogid;"
                    )
                    ->queryAll();

                $cape_courses = $db->createCommand(
                        "SELECT cape_course.coursecode AS 'code',"
                        ." cape_course.name AS 'name',"
                        ." cape_subject.academicofferingid AS 'academicofferingid'" 
                        ." FROM cape_course" 
                        ." JOIN cape_unit"
                        ." ON cape_course.capeunitid = cape_unit.capeunitid"
                        ." JOIN cape_subject"
                        ." ON cape_unit.capesubjectid = cape_subject.capesubjectid"
                        ." JOIN academic_offering"
                        ." ON cape_subject.academicofferingid = academic_offering.academicofferingid"
                        ." JOIN programme_catalog"
                        ." ON academic_offering.programmecatalogid = programme_catalog.programmecatalogid"
                        ." WHERE cape_course.isactive = 1"
                        ." AND cape_course.isdeleted = 0"
                        ." AND programme_catalog.departmentid = " . $course_department
                        ." GROUP BY cape_course.coursecode;"
                    )
                    ->queryAll();

                if($courses)
                {
                    foreach ($courses as $course)
                    {
                        $course_info['code'] = $course['code'];
                        $course_info['name'] = $course['name'];
                        $course_info['academicofferingid'] = $course['academicofferingid'];
                        $course_info['type'] = 'associate';
                        $course_container[] = $course_info;
                    }
                }

                if($cape_courses)
                {
                    foreach ($cape_courses as $cape_course)
                    {
                        $course_info['code'] = $cape_course['code'];
                        $course_info['name'] = $cape_course['name'];
                        $course_info['academicofferingid'] = $cape_course['academicofferingid'];
                        $course_info['type'] = 'cape';
                        $course_container[] = $course_info;
                    }
                }

                $course_dataprovider = new ArrayDataProvider([
                            'allModels' => $course_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['type' => SORT_ASC, 'code' =>SORT_ASC],
                                'attributes' => ['code', 'type'],
                            ]
                    ]); 
            }
            
            //if user initiates search based on course code
            elseif ($course_code != NULL  && strcmp($course_code, "") != 0)
            {
                $info_string .= $course_code;

                $course_container = array();
                $course_info = array();

                $db = Yii::$app->db;
                $courses = $db->createCommand(
                        "SELECT course_catalog.coursecode AS 'code',"
                        ." course_catalog.name AS 'name',"
                        ." course_offering.academicofferingid AS 'academicofferingid'" 
                        ." FROM course_offering" 
                        ." JOIN course_catalog"
                        ." ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                        ." WHERE course_offering.isactive = 1"
                        ." AND course_offering.isdeleted = 0"
                        ." AND course_catalog.coursecode LIKE '%" . $course_code . "%'"
                        ." GROUP BY course_offering.coursecatalogid;"
                    )
                    ->queryAll();

                $cape_courses = $db->createCommand(
                        "SELECT cape_course.coursecode AS 'code',"
                        ." cape_course.name AS 'name',"
                        ." cape_subject.academicofferingid AS 'academicofferingid'" 
                        ." FROM cape_course" 
                        ." JOIN cape_unit"
                        ." ON cape_course.capeunitid = cape_unit.capeunitid"
                        ." JOIN cape_subject"
                        ." ON cape_unit.capesubjectid = cape_subject.capesubjectid"
                        ." WHERE cape_course.isactive = 1"
                        ." AND cape_course.isdeleted = 0"
                        ." AND cape_course.coursecode LIKE '%" . $course_code . "%'"
                        ." GROUP BY cape_course.coursecode;"
                    )
                    ->queryAll();

                if($courses)
                {
                    foreach ($courses as $course)
                    {
                        $course_info['code'] = $course['code'];
                        $course_info['name'] = $course['name'];
                        $course_info['academicofferingid'] = $course['academicofferingid'];
                        $course_info['type'] = 'associate';
                        $course_container[] = $course_info;
                    }
                }

                if($cape_courses)
                {
                    foreach ($cape_courses as $cape_course)
                    {
                        $course_info['code'] = $cape_course['code'];
                        $course_info['name'] = $cape_course['name'];
                        $course_info['academicofferingid'] = $cape_course['academicofferingid'];
                        $course_info['type'] = 'cape';
                        $course_container[] = $course_info;
                    }
                }
                
                $course_dataprovider = new ArrayDataProvider([
                            'allModels' => $course_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['type' => SORT_ASC, 'code' =>SORT_ASC],
                                'attributes' => ['code', 'type'],
                            ]
                    ]); 
            }
            
            
            //if user initiates search based on course name
            elseif ($course_name != NULL && strcmp($course_name, "") != 0)
            {
                $info_string .= $course_name;

                $course_container = array();
                $course_info = array();

                $db = Yii::$app->db;
                $courses = $db->createCommand(
                        "SELECT course_catalog.coursecode AS 'code',"
                        ." course_catalog.name AS 'name',"
                        ." course_offering.academicofferingid AS 'academicofferingid'" 
                        ." FROM course_offering" 
                        ." JOIN course_catalog"
                        ." ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                        ." WHERE course_offering.isactive = 1"
                        ." AND course_offering.isdeleted = 0"
                        ." AND course_catalog.name LIKE '%" . $course_name  . "%'"
                        ." GROUP BY course_offering.coursecatalogid;"
                    )
                    ->queryAll();

                $cape_courses = $db->createCommand(
                        "SELECT cape_course.coursecode AS 'code',"
                        ." cape_course.name AS 'name',"
                        ." cape_subject.academicofferingid AS 'academicofferingid'" 
                        ." FROM cape_course" 
                        ." JOIN cape_unit"
                        ." ON cape_course.capeunitid = cape_unit.capeunitid"
                        ." JOIN cape_subject"
                        ." ON cape_unit.capesubjectid = cape_subject.capesubjectid"
                        ." WHERE cape_course.isactive = 1"
                        ." AND cape_course.isdeleted = 0"
                        ." AND cape_course.name LIKE '%" . $course_name . "%'"
                        ." GROUP BY cape_course.coursecode;"
                    )
                    ->queryAll();

                if($courses)
                {
                    foreach ($courses as $course)
                    {
                        $course_info['code'] = $course['code'];
                        $course_info['name'] = $course['name'];
                        $course_info['academicofferingid'] = $course['academicofferingid'];
                        $course_info['type'] = 'associate';
                        $course_container[] = $course_info;
                    }
                }

                if($cape_courses)
                {
                    foreach ($cape_courses as $cape_course)
                    {
                        $course_info['code'] = $cape_course['code'];
                        $course_info['name'] = $cape_course['name'];
                        $course_info['academicofferingid'] = $cape_course['academicofferingid'];
                        $course_info['type'] = 'cape';
                        $course_container[] = $course_info;
                    }
                }
                
                $course_dataprovider = new ArrayDataProvider([
                            'allModels' => $course_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['type' => SORT_ASC, 'code' =>SORT_ASC],
                                'attributes' => ['code', 'type'],
                            ]
                    ]); 
            }
        }
        
        
        return $this->render('index',
            [
                'info_string' => $info_string,
                'programme_dataprovider' => $programme_dataprovider,
                'divisionid' => $divisionid,
                'course_dataprovider' => $course_dataprovider,
            ]);
    }
    
    
    
    
    public function actionProgramme($divisionid, $programmecatalogid = NULL)
    {
        if($programmecatalogid == NULL)     //if user is creating new programme catalog record
        {
            $programme = new ProgrammeCatalog();
        }
        else
        {
            $programme = ProgrammeCatalog::find()
                    ->where(['programmecatalogid' => $programecatalogid])
                    ->one();
            if(!$programme) //if programme catalog record was not found
            {
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to retrive programme record.');
                 return self::actionIndex();
            }
        }
        
        if ($post_data = Yii::$app->request->post())
        {
            $load_flag = false;
            $save_flag = false;
            
            $load_flag = $programme->load($post_data);
            if($load_flag == true)
            { 
                if($programmecatalogid == NULL)     
                    $programme->creationdate = date('Y-m-d');
                
                $save_flag = $programme->save();
                if($save_flag == true)
                    return self::actionIndex();
                else
                    Yii::$app->getSession()->setFlash('error', 'Error occured when trying to create programme record. Please try again.');
            }
            else
                Yii::$app->getSession()->setFlash('error', 'Error occured when trying to load programme record. Please try again.');              
        }
        
        return $this->render('add_programme', [
                'divisionid' => $divisionid,
                'programme' => $programme,
            ]);
    }
    
    
    
    /**
     * Renders Programme Catalog creation/update and processes associated requests
     * 
     * @param type $programmecatalogid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 09/06/2016
     * Date LastModified: 09/06/2016
     */
    public function actionProgrammeOverview($programmecatalogid)
    {
        $programme = ProgrammeCatalog::find()
                ->where(['programmecatalogid' =>$programmecatalogid])
                ->one();
        $programme_name =  ProgrammeCatalog::getProgrammeFullName($programme->programmecatalogid);
        $programme_info = NULL; 
        
        if ($programme)
        {
            $programme_info['programmecatalogid'] = $programme->programmecatalogid;

            $qualificationtype = QualificationType::find()
                    ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()->abbreviation;
            $programme_info['qualificationtype'] = $qualificationtype;

            $programme_info['name'] = $programme->name;
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
                    ->one()->name;
            $programme_info['programmetype'] = $programmetype;

            $programme_info['duration'] = $programme->duration;
            $programme_info['creationdate'] = $programme->creationdate;

            $programme_container[] = $programme_info;
            
            $cohort_array = array();
            $cohort_count = AcademicOffering::getCohortCount($programme->programmecatalogid);
            array_push( $cohort_array, $cohort_count);

            if ($cohort_count > 0)
            {
                $cohorts = AcademicOffering::getCohorts($programme->programmecatalogid); 
                for($i = 0 ; $i < $cohort_count ; $i++)
                {
                    array_push($cohort_array, $cohorts[$i]);
                }
            }
        }
        
        $course_outline_dataprovider = array();
        $cape_course_outline_dataprovider = array();
        $course_info = array();
        $course_container = array();
        
        if($programmecatalogid == 10)       //if CAPE
        {
            $courses = CapeCourse::find()
                    ->innerJoin('cape_unit', '`cape_course`.`capeunitid` = `cape_unit`.`capeunitid`')
                    ->innerJoin('cape_subject', ' `cape_unit`.`capesubjectid`=`cape_subject`.`capesubjectid`')
                    ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid`=`academic_offering`.`academicofferingid`')
                    ->groupBy('cape_course.capecourseid')
                    ->where(['academic_offering.programmecatalogid' => $programmecatalogid])
                    ->all();
            
            if($courses)
            {
                foreach($courses as $course)
                {
                    $course_info['capecourseid'] = $course->capecourseid;
                    $course_info['programmecatalogid'] = $programmecatalogid;
                    $course_info['coursecode'] = $course->coursecode;
                    $course_info['name'] = $course->name;
                    
                    $cape_subject = CapeSubject::find()
                            ->innerJoin('cape_unit', '`cape_subject`.`capesubjectid` = `cape_unit`.`capesubjectid`')
                            ->innerJoin('cape_course', ' `cape_unit`.`capeunitid`=`cape_course`.`capeunitid`')
                             ->where(['cape_course.capecourseid' => $course->capecourseid])
                            ->one()
                            ->subjectname;
                    $course_info['subject'] =  $cape_subject;
                    
                    if(CourseOutline::getOutlines(0,  $programmecatalogid, $course->capecourseid) == true)
                        $course_info['has_outline'] = true;
                    else
                        $course_info['has_outline'] = false;
                    $course_container[] = $course_info;
                }
            }
            
           $cape_course_outline_dataprovider  = new ArrayDataProvider([
                            'allModels' => $course_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['code' => SORT_ASC],
                                'attributes' => ['code', 'subject'],
                            ]
                    ]);            
        }
        
        else        //if !CAPE
        {
            $courses = CourseCatalog::find()
                    ->innerJoin('course_offering', '`course_catalog`.`coursecatalogid` = `course_offering`.`coursecatalogid`')
                    ->innerJoin('academic_offering', '`course_offering`.`academicofferingid`=`academic_offering`.`academicofferingid`')
                    ->groupBy('course_catalog.coursecatalogid')
                    ->where(['academic_offering.programmecatalogid' => $programmecatalogid])
                    ->all();
            
            if($courses)
            {
                foreach($courses as $course)
                {
                    $course_info['coursecatalogid'] = $course->coursecatalogid;
                    $course_info['programmecatalogid'] = $programmecatalogid;
                    $course_info['coursecode'] = $course->coursecode;
                    $course_info['name'] = $course->name;
                    
                    if(CourseOutline::getOutlines(0,  $programmecatalogid, $course->coursecatalogid) == true)
                        $course_info['has_outline'] = true;
                    else
                        $course_info['has_outline'] = false;
                    $course_container[] = $course_info;
                }
            }
            
           $course_outline_dataprovider  = new ArrayDataProvider([
                            'allModels' => $course_container,
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                            'sort' => [
                                'defaultOrder' => ['code' => SORT_ASC],
                                'attributes' => ['code'],
                            ]
                    ]);            
           }
        
           $cordinator_details = "";
           
           $offerings = AcademicOffering::find()
                   ->where(['programmecatalogid' => $programme->programmecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                   ->all();
           $offerids = array();
           foreach($offerings as $offering)
               array_push($offerids, $offering->academicofferingid);
              
          
           $cordinators = Cordinator::find()
                   ->where(['academicofferingid' => $offerids , 'isserving' => 1, 'isactive' => 1, 'isdeleted' => 0])
                   ->orderBy('cordinatorid DESC')
                   -> all();
           if($cordinators)
           {
               foreach($cordinators as $key => $cordinator)
               {
                   $name = "";
                   $name = Employee::getEmployeeName($cordinator[$key]->personid);
                   if(count($cordinators) - 1 == 0)
                    $cordinator_details .= $name;
                    else 
                        $cordinator_details .= $name . ", ";
               }
           }
           
        
            return $this->render('programme_overview',
                [
                   'programme' => $programme,
                    'programme_name' => $programme_name,
                    'programme_info' => $programme_info,
                    'cohort_array' => $cohort_array,
                   'cordinator_details' => $cordinator_details,
                    'course_outline_dataprovider' => $course_outline_dataprovider,
                    'cape_course_outline_dataprovider' => $cape_course_outline_dataprovider,
                ]);
    }
    
    
    /**
     * Downloads the programme booklet for a particular academic offering
     * 
     * @param type $divisionid
     * @param type $programmecatalogid
     * @param type $academicofferingid
     * 
     * Author: Laurence Charles
     * Date Created: 10/06/2016
     * Date Last Modified: 10/06/2016
     */
    public function actionDownloadBooklet($divisionid, $programmecatalogid, $academicofferingid)
    {
        if($divisionid == 4)
            $division = "dasgs";
        elseif($divisionid == 5)
            $division = "dtve";
        elseif($divisionid == 6)
            $division = "dte";
        elseif($divisionid == 7)
            $division = "dne";
        
        $dir =  Yii::getAlias('@frontend') . "/files/programme_booklets/" . $division . "/" . $programmecatalogid . "_" . $academicofferingid . "/";
        $files = FileHelper::findFiles($dir);
        Yii::$app->response->sendFile($files[0], "Download");
        Yii::$app->response->send();
    }
    
    
    /**
     * Deletes the programme booklet for a particular academic offering
     * 
     * @param type $divisionid
     * @param type $programmecatalogid
     * @param type $academicofferingid
     * 
     * Author: Laurence Charles
     * Date Created: 11/06/2016
     * Date Last Modified: 11/06/2016
     */
    public function actionDeleteBooklet($divisionid, $programmecatalogid, $academicofferingid)
    {
        if($divisionid == 4)
            $division = "dasgs";
        elseif($divisionid == 5)
            $division = "dtve";
        elseif($divisionid == 6)
            $division = "dte";
        elseif($divisionid == 7)
            $division = "dne";
        
        $dir =  Yii::getAlias('@frontend') . "/files/programme_booklets/" . $division . "/" . $programmecatalogid . "_" . $academicofferingid . "/";
        
        try
        {
            FileHelper::removeDirectory($dir);
        } catch (ErrorExceptionException $ex) {
            Yii::$app->getSession()->setFlash('error', 'Error occured when trying to delete programme booklet file from server.');
        }
        
        return self::actionGetAcademicOffering($programmecatalogid, $academicofferingid);
    }
    
    
    /**
     * Replaces the programme booklet for a particular academic offering
     * 
     * @param type $divisionid
     * @param type $programmecatalogid
     * @param type $academicofferingid
     * 
     * Author: Laurence Charles
     * Date Created: 11/06/2016
     * Date Last Modified: 11/06/2016
     */
    public function actionReplaceBooklet($divisionid, $programmecatalogid, $academicofferingid)
    {   
        $model = new BookletAttachment();
        $model->divisionid = $divisionid;
        $model->programmecatalogid = $programmecatalogid;
        $model->academicofferingid = $academicofferingid;

        if (Yii::$app->request->isPost) 
        {
             if($divisionid == 4)
                $division = "dasgs";
            elseif($divisionid == 5)
                $division = "dtve";
            elseif($divisionid == 6)
                $division = "dte";
            elseif($divisionid == 7)
                $division = "dne";

            $dir =  Yii::getAlias('@frontend') . "/files/programme_booklets/" . $division . "/" . $programmecatalogid . "_" . $academicofferingid . "/";
            $saved_files = FileHelper::findFiles($dir);
            $delete_status = unlink($saved_files[0]);
            
            if($delete_status)
            {
                $model->files = UploadedFile::getInstances($model, 'files');
                if ($model->upload())   // file is uploaded successfully
                    return self::actionGetAcademicOffering($programmecatalogid, $academicofferingid);       
                else
                    Yii::$app->getSession()->setFlash('error', 'File upload unsuccessful.');              
            }
            else
                Yii::$app->getSession()->setFlash('error', 'The deletion of previous booklet was unsiccessful.');  
        }

        return $this->render('upload_booklet', 
                            [
                                'model' => $model,
                                'programmecatalogid' => $programmecatalogid,
                                 'academicofferingid' => $academicofferingid,
                            ]
        );
    }
    
    
    /**
     * Replaces the programme booklet for a particular academic offering
     * 
     * @param type $divisionid
     * @param type $programmecatalogid
     * @param type $academicofferingid
     * 
     * Author: Laurence Charles
     * Date Created: 11/06/2016
     * Date Last Modified: 11/06/2016
     */
    public function actionUploadBooklet($divisionid, $programmecatalogid, $academicofferingid)
    {   
        $model = new BookletAttachment();
        $model->divisionid = $divisionid;
        $model->programmecatalogid = $programmecatalogid;
        $model->academicofferingid = $academicofferingid;

        if (Yii::$app->request->isPost) 
        {
            $model->files = UploadedFile::getInstances($model, 'files');
            if ($model->upload())   // file is uploaded successfully
                return self::actionGetAcademicOffering($programmecatalogid, $academicofferingid);       
            else
                Yii::$app->getSession()->setFlash('error', 'File upload unsuccessful.');              
         }

        return $this->render('upload_booklet', 
                            [
                                'model' => $model,
                                'programmecatalogid' => $programmecatalogid,
                                 'academicofferingid' => $academicofferingid,
                            ]
        );
    }
    
    
    
    
    
    //Code will be completed after feature to enter course outline has been created
    public function actionCourseDescription($iscape,  $programmecatalogid, $coursecatalogid)
    {
        if($iscape == 0)    //if !cape course
        {
            $course_outlines = CourseOutline::find()
                    ->innerJoin('course_offering', '`course_outline`.`courseid` = `course_offering`.`courseofferingid`')
                    ->where(['course_outline.isactive' => 1, 'course_outline.isdeleted' => 0,
                                    'course_offering.coursecatalogid' => $coursecatalogid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                ])
                     ->orderBy('course_offering.courseofferingid DESC')
                    ->all();
             $recent = $course_outlines[0];
        }
        else
        {
            
        }
    }
    
    /**
     * Render control panel for academic offering
     * 
     * @param type $programmecatalogid
     * @param type $academicofferingid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 11/06/2016
     * Date Last Modified: 11/06/2016
     */
    public function actionGetAcademicOffering($programmecatalogid, $academicofferingid)
    {
        $programme = ProgrammeCatalog::find()
                ->where(['programmecatalogid' =>$programmecatalogid])
                ->one();
        $programme_name =  ProgrammeCatalog::getProgrammeFullName($programme->programmecatalogid);
        $programme_info = NULL; 
        
        if ($programme)
        {
            $programme_info['programmecatalogid'] = $programme->programmecatalogid;

            $qualificationtype = QualificationType::find()
                    ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()->abbreviation;
            $programme_info['qualificationtype'] = $qualificationtype;

            $programme_info['name'] = $programme->name;
            $programme_info['specialisation'] = $programme->specialisation;

            $department_record = Department::find()
                    ->where(['departmentid' => $programme->departmentid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $department = $department_record->name;
            $programme_info['department'] = $department;
            
            $divisionid = Department::getDivisionID($department_record->departmentid);
            $programme_info['divisionid'] = $divisionid;

            $exambody = ExaminationBody::find()
                    ->where(['examinationbodyid' => $programme->examinationbodyid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()->abbreviation;
            $programme_info['exambody'] = $exambody;

            $programmetype = IntentType::find()
                    ->where(['intenttypeid' => $programme->programmetypeid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()->name;
            $programme_info['programmetype'] = $programmetype;

            $programme_info['duration'] = $programme->duration;
            $programme_info['creationdate'] = $programme->creationdate;

            $programme_container[] = $programme_info;
        }
        
        $academic_year = AcademicYear::find()
                 ->innerJoin('academic_offering', '`academic_year`.`academicyearid` = `academic_offering`.`academicyearid`')
                ->where(['academic_offering.academicofferingid' => $academicofferingid])
                ->one()
                ->title;
        
        $cordinator_details = "";
      
       $cordinators = Cordinator::find()
               ->where(['academicofferingid' => $academicofferingid , 'isserving' => 1, 'isactive' => 1, 'isdeleted' => 0])
               ->orderBy('cordinatorid DESC')
               -> all();
       if($cordinators)
       {
           foreach($cordinators as $key => $cordinator)
           {
               $name = "";
               $name = Employee::getEmployeeName($cordinator[$key]->personid);
               if(count($cordinators) - 1 == 0)
                $cordinator_details .= $name;
                else 
                    $cordinator_details .= $name . ", ";
           }
       }
        
        return $this->render('academic_offering_overview',
            [
                'programme' => $programme,
                'programme_name' => $programme_name,
                'programme_info' => $programme_info,
                'cordinator_details' => $cordinator_details,
                'cohort' => $academic_year,
                'academicofferingid' => $academicofferingid,
                'programmecatalogid' => $programmecatalogid,
             ]);
    }
    
    
    /**
     * Generates the intake report for an academic offering
     * 
     * @param type $academicofferingid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 12/06/2016
     * Date Last Modified: 12/06/2016
     */
    public function actionGenerateIntakeReport($academicofferingid)
    {
        $is_cape = AcademicOffering::isCape($academicofferingid);
        
        $summary_dataProvider = NULL;
        $accepted_dataProvider = NULL;
        $enrolled_dataProvider = NULL;
        
        $summary_data = array();
        $accepted_data = array();
        $enrolled_data = array();
        
        $academicoffering = AcademicOffering::find()
                ->where(['academicofferingid' => $academicofferingid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        $application_periodid =  $academicoffering->applicationperiodid;
        $programme_criteria =  ProgrammeCatalog::find()
                                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                ->where(['academicofferingid' => $academicofferingid])
                                ->one()
                                ->getFullName();
        
        $accepted_cond = array();
        $accepted_cond['application.isactive'] = 1;
        $accepted_cond['application.isdeleted'] = 0;
        $accepted_cond['academic_offering.isactive'] = 1;
        $accepted_cond['academic_offering.isdeleted'] = 0;
        $accepted_cond['academic_offering.applicationperiodid'] =  $application_periodid;
        $accepted_cond['application_period.isactive'] = 1;
        $accepted_cond['application_period.isdeleted'] = 0;
        $accepted_cond['application.applicationstatusid'] = 9;
        $accepted_cond['offer.isactive'] = 1;
        $accepted_cond['offer.isdeleted'] = 0;
        $accepted_cond['offer.offertypeid'] = 1;
        $accepted_cond['application.academicofferingid'] = $academicofferingid;

        $accepted_applicants = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                ->where($accepted_cond)
                ->groupby('applicant.personid')
                ->orderBy('applicant.lastname ASC')
                ->all();
      
        foreach ($accepted_applicants as $accepted_applicant) 
        {
            $offers = Offer::hasOffer($accepted_applicant->personid, $application_periodid);

            if($offers == true)
            {
                foreach ($offers as $offer) 
                {
                    $username = User::findOne(['personid' => $accepted_applicant->personid, 'isdeleted' => 0])->username;

                    $programme = "N/A";
                    $target_application = Application::find()
                            ->where(['applicationid' => $offer->applicationid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    if ($target_application) 
                    {
                        $programme_record = ProgrammeCatalog::find()
                                ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                                ->where(['academicofferingid' => $target_application->academicofferingid])
                                ->one();
                        $cape_subjects = ApplicationCapesubject::findAll(['applicationid' => $target_application->applicationid]);
                        foreach ($cape_subjects as $cs) 
                        {
                            $cape_subjects_names[] = $cs->getCapesubject()->one()->subjectname;
                        }
                        $programme = empty($cape_subjects) ? $programme_record->getFullName() : $programme_record->name . ": " . implode(' ,', $cape_subjects_names);
                    }
                    
                    $accepted_info = array();
                    $accepted_info['personid'] = $accepted_applicant->personid;
                    $accepted_info['applicantid'] = $accepted_applicant->applicantid;
                    $accepted_info['username'] = $username;
                    $accepted_info['title'] = $accepted_applicant->title;
                    $accepted_info['firstname'] = $accepted_applicant->firstname;
                    $accepted_info['middlename'] = $accepted_applicant->middlename;
                    $accepted_info['lastname'] = $accepted_applicant->lastname;
                    $accepted_info['offerid'] = $offer->offerid;
                    $accepted_info['applicationid'] = $offer->applicationid;
                    $accepted_info['programme'] = $programme;
                    $accepted_data[] = $accepted_info;

                    $has_enrolled = StudentRegistration::find()
                            ->where(['offerid' => $offer->offerid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();

                    if($has_enrolled == true)
                    {
                        $enrolled_info = array();
                        $enrolled_info['personid'] = $accepted_applicant->personid;
                        $enrolled_info['applicantid'] = $accepted_applicant->applicantid;
                        $enrolled_info['username'] = $username;
                        $enrolled_info['title'] = $accepted_applicant->title;
                        $enrolled_info['firstname'] = $accepted_applicant->firstname;
                        $enrolled_info['middlename'] = $accepted_applicant->middlename;
                        $enrolled_info['lastname'] = $accepted_applicant->lastname;
                        $enrolled_info['offerid'] = $offer->offerid;
                        $enrolled_info['applicationid'] = $offer->applicationid;
                        $enrolled_info['programme'] = $programme;
                        $enrolled_data[] = $enrolled_info;
                    }

                    $cape_subjects = NULL;
                    $cape_subjects_names = NULL;
                }
            }
        }

        $accepted_criteria = $programme_criteria;
        $enrolled_criteria = $programme_criteria;

        /*************************************** prepare programme *****************************************/
       $programme_record = ProgrammeCatalog::find()
                    ->innerJoin('academic_offering', '`academic_offering`.`programmecatalogid` = `programme_catalog`.`programmecatalogid`')
                    ->where(['programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0,
                            'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $academicofferingid
                            ])
                    ->one();
        $name = $programme_record->getFullName();

        $accepted_male_count = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['applicant.gender' => 'male',
                                'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 9,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $academicofferingid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                ])
                ->groupby('applicant.personid')
                ->count();

        $accepted_female_count = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['applicant.gender' => 'female',
                                'application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 9,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $academicofferingid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                ])
                ->groupby('applicant.personid')
                ->count();

        $accepted_count = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['application.isactive' => 1, 'application.isdeleted' => 0, 'application.applicationstatusid' => 9,
                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $academicofferingid,
                        'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                        ])
                ->groupby('applicant.personid')
                ->count();

        $enrolled_male_count = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['applicant.gender' => 'male',
                                'application.isactive' => 1, 'application.isdeleted' => 0,  'application.applicationstatusid' => 9,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $academicofferingid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                ])
                ->groupby('applicant.personid')
                ->count();

        $enrolled_female_count = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['applicant.gender' => 'female',
                                'application.isactive' => 1, 'application.isdeleted' => 0,  'application.applicationstatusid' => 9,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $academicofferingid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                ])
                ->groupby('applicant.personid')
                ->count();

        $enrolled_count = Applicant::find()
                ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                ->where(['application.isactive' => 1, 'application.isdeleted' => 0,  'application.applicationstatusid' => 9,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 'academic_offering.academicofferingid' => $academicofferingid,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0
                                ])
                ->groupby('applicant.personid')
                ->count();

        $summary_info['name'] = $name;
        $summary_info['accepted_males'] = $accepted_male_count;
        $summary_info['accepted_females'] = $accepted_female_count;
        $summary_info['accepted'] = $accepted_count;
        $summary_info['enrolled_males'] = $enrolled_male_count;
        $summary_info['enrolled_females'] = $enrolled_female_count;
        $summary_info['enrolled'] = $enrolled_count;
        $summary_data[] = $summary_info;

        /*************************************** prepare subjects  *****************************************/
       if($is_cape)
       {
            $subjects = CapeSubject::find()
                        ->innerJoin('application_capesubject', '`cape_subject`.`capesubjectid` = `application_capesubject`.`capesubjectid`')
                        ->innerJoin('application', '`application_capesubject`.`applicationid` = `application`.`applicationid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->where(['cape_subject.isactive' => 1, 'cape_subject.isdeleted' => 0,
                                'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0,
                                'application.isactive' => 1, 'application.isdeleted' => 0,
                                'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0,
                                'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1,
                                'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                ])
                        ->all();
            
            foreach($subjects as $subject)
            {
                $subject_name = CapeSubject::find()
                                    ->where(['capesubjectid' => $subject->capesubjectid])
                                    ->one()
                                    ->subjectname;
                
                $accepted_male_count =Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['applicant.gender' => 'male',
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                        'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                        ])
                        ->groupby('application.personid')
                        ->count();
                
                $accepted_female_count =Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['applicant.gender' => 'female',
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                        'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                        ])
                        ->groupby('application.personid')
                        ->count();
                
                $accepted_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->where(['application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                        'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                        ])
                        ->groupby('application.personid')
                        ->count();
                
                $enrolled_male_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                        ->where(['applicant.gender' => 'male',
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                        'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                        'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                        ])
                        ->groupby('application.personid')
                        ->count();
                
                $enrolled_female_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                        ->where(['applicant.gender' => 'female',
                                        'application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                        'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                        'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                        ])
                        ->groupby('application.personid')
                        ->count();
                
                $enrolled_count = Applicant::find()
                        ->innerJoin('application', '`applicant`.`personid` = `application`.`personid`')
                        ->innerJoin('academic_offering', '`academic_offering`.`academicofferingid` = `application`.`academicofferingid`')
                        ->innerJoin('application_capesubject', '`application`.`applicationid` = `application_capesubject`.`applicationid`')
                        ->innerJoin('offer', '`application`.`applicationid` = `offer`.`applicationid`')
                        ->innerJoin('application_period', '`application_period`.`applicationperiodid` = `academic_offering`.`applicationperiodid`')
                        ->innerJoin('student_registration', '`offer`.`offerid` = `student_registration`.`offerid`')
                        ->where(['application.isactive' => 1, 'application.isdeleted' => 0,
                                        'academic_offering.isactive' => 1, 'academic_offering.isdeleted' => 0, 
                                        'application_capesubject.isactive' => 1, 'application_capesubject.isdeleted' => 0, 'application_capesubject.capesubjectid' => $subject->capesubjectid,
                                        'offer.isactive' => 1, 'offer.isdeleted' => 0, 'offer.ispublished' => 1, 'offer.offertypeid' => 1,
                                        'student_registration.isactive' => 1, 'student_registration.isdeleted' => 0,
                                        'application_period.isactive' => 1, 'application_period.isdeleted' => 0, 'application_period.applicationperiodid' => $application_periodid,
                                        ])
                        ->groupby('application.personid')
                        ->count();
                
                $summary_info['name'] = $subject_name;
                $summary_info['accepted_males'] = $accepted_male_count;
                $summary_info['accepted_females'] = $accepted_female_count;
                $summary_info['accepted'] = $accepted_count;
                $summary_info['enrolled_males'] = $enrolled_male_count;
                $summary_info['enrolled_females'] = $enrolled_female_count;
                $summary_info['enrolled'] = $enrolled_count;
                $summary_data[] = $summary_info;
            }
       }
            
        $summary_dataProvider = new ArrayDataProvider([
            'allModels' => $summary_data,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        $accepted_dataProvider = new ArrayDataProvider([
            'allModels' => $accepted_data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $enrolled_dataProvider = new ArrayDataProvider([
            'allModels' => $enrolled_data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $summary_header = "Intake Overview";
        $summary_title = "Title: " . $programme . $summary_header;

        $accepted_header = "Accepted Applicants Report - " . $accepted_criteria;
        $accepted_title = "Title: " . $programme .  $accepted_header;

        $enrolled_header = "Enrolled Students Report - " . $enrolled_criteria;
        $enrolled_title = "Title: " . $programme .  $enrolled_header;
        ;

        $date = "Date Generated: " . date('Y-m-d') . "   ";
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);

        $summary_filename = $summary_title . $date . $generating_officer;
        $accepted_filename = $accepted_title . $date . $generating_officer;
        $enrolled_filename = $enrolled_title . $date . $generating_officer;

        $page_title = $programme . " Intake Report";
        
        
        return $this->render('display_academic_offering_intake', [
                    'summary_dataProvider' => $summary_dataProvider,
                    'accepted_dataProvider' => $accepted_dataProvider,
                    'enrolled_dataProvider' => $enrolled_dataProvider,

                    'summary_header' =>  $summary_header,
                    'accepted_header' => $accepted_header,
                    'enrolled_header' => $enrolled_header,

                    'summary_filename' => $summary_filename,
                    'accepted_filename' => $accepted_filename,
                    'enrolled_filename' => $enrolled_filename,

                    'programmecatalogid' => $academicoffering->programmecatalogid,
                    'academicofferingid' => $academicofferingid,
                    'programme_name' => $programme_criteria,
                    'page_title' => $page_title,
                   
            ]);
    }
    
    
    /**
     * Generates the intake report for an academic offering
     * 
     * @param type $academicofferingid
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 12/06/2016
     * Date Last Modified: 12/06/2016
     */
    public function actionGenerateProgrammeBroadsheet($academicofferingid)
    {
        $asc_dataprovider = NULL;
        $cape_dataprovider = NULL;
        
        $asc_data = array();
        $cape_data =array();
        
        $academicoffering = AcademicOffering::find()
                ->where(['academicofferingid' => $academicofferingid, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        $programmecatalogid = $academicoffering->programmecatalogid;
        
        $is_cape = AcademicOffering::isCape($academicofferingid);
        
        $programme_name = ProgrammeCatalog::getProgrammeName($academicofferingid);
   
        $course_info = array();
        
        if($programmecatalogid == 10)       //if CAPE
        {
            $courses = CapeCourse::find()
                    ->innerJoin('cape_unit', '`cape_course`.`capeunitid` = `cape_unit`.`capeunitid`')
                    ->innerJoin('cape_subject', ' `cape_unit`.`capesubjectid`=`cape_subject`.`capesubjectid`')
                    ->innerJoin('academic_offering', '`cape_subject`.`academicofferingid`=`academic_offering`.`academicofferingid`')
//                    ->groupBy('cape_course.capecourseid')
                    ->where(['academic_offering.programmecatalogid' => $programmecatalogid])
                    ->all();
            
            $total_courses = count($courses);
            $total_entered = 0;
            foreach($courses as $course)
            {
                $has_grades = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->count();
                if($has_grades>0)
                     $total_entered++;
            }
            $total_outstanding = $total_courses - $total_entered;
            
            if($courses)
            {
                foreach($courses as $course)
                {
                    $course_info['capecourseid'] = $course->capecourseid;
                    $course_info['programmecatalogid'] = $programmecatalogid;
                    $course_info['code'] = $course->coursecode;
                    $course_info['name'] = $course->name;
                    
                    $cape_subject = CapeSubject::find()
                            ->innerJoin('cape_unit', '`cape_subject`.`capesubjectid` = `cape_unit`.`capesubjectid`')
                            ->innerJoin('cape_course', ' `cape_unit`.`capeunitid`=`cape_course`.`capeunitid`')
                             ->where(['cape_course.capecourseid' => $course->capecourseid])
                            ->one()
                            ->subjectname;
                    $course_info['subject'] =  $cape_subject;
                    
                    $semester = Semester::find()
                            ->where(['semesterid' => $course->semesterid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                            ->title;
                    $course_info['semester'] = $semester;
                    
                    $lecs = EmployeeBatchCape::find()
                             ->innerJoin('batch_cape', '`employee_batch_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid`=`cape_course`.`capecourseid`')
                             ->where(['cape_course.capecourseid' => $course->capecourseid])
                            ->all();
                    if($lecs)
                        $has_lecs = true;
                    else
                        $has_lecs = false;
                    $lecturers = "";
                            
                     if($has_lecs)
                     {
                         $lec_count = count($lecs);
                         foreach($lecs as $key=>$lec)
                         {
                             if($lec_count - $key == 1 )     //if last lecturer
                             {
                                 $lecturers .= Employee::getEmployeeName($lec->personid);
                             }
                             else       //not last lecturer
                             {
                                 $lecturers .= Employee::getEmployeeName($lec->personid) . ",   ";
                             }
                         }
                          $course_info['lecturer'] = $lecturers;
                     }
                     else
                          $course_info['lecturer'] = "Unavailable"; 
                    
                    $course_info['coursework'] = $course->courseworkweight;
                    $course_info['exam'] = $course->examweight;
                    
                    $enrolled_count = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->count();
                    
                    $fail_count =  BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['<',  'batch_student_cape.final' , 40]) 
                            ->count();
                    $pass_count = $enrolled_count - $fail_count;
                    
                    $pass_percentage = 0;
                    if( $enrolled_count)
                            $pass_percentage = number_format(($pass_count/$enrolled_count)*100,1);
                    
                    $course_info['total'] = $enrolled_count;
                    $course_info['passes'] = $pass_count;
                    $course_info['fails'] = $fail_count;
                    $course_info['pass_percent'] =  $pass_percentage;
                    
                    $mode_count = 0;
                    $mode_val = "N/A";
                    
                     $ninty_plus = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['>=',  'batch_student_cape.final' ,  90]) 
                            ->count();
                     $course_info['ninety_plus'] =  $ninty_plus;
                     if($ninty_plus > $mode_count)
                     {
                        $mode_count = $ninty_plus;
                        $mode_val = "90+";
                     }
                     
                     $eighty_to_ninety = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['>=',  'batch_student_cape.final' ,  80]) 
                             ->andWhere(['<',  'batch_student_cape.final' ,  90]) 
                            ->count();
                     $course_info['eighty_to_ninety'] =  $eighty_to_ninety;
                     if($eighty_to_ninety > $mode_count)
                     {
                        $mode_count = $eighty_to_ninety;
                        $mode_val = "80-90";
                     }
                     
                     $seventy_to_eighty = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['>=',  'batch_student_cape.final' , 70]) 
                             ->andWhere(['<',  'batch_student_cape.final' ,  80]) 
                            ->count();
                     $course_info['seventy_to_eighty'] =  $seventy_to_eighty;
                     if($seventy_to_eighty > $mode_count)
                     {
                        $mode_count = $seventy_to_eighty;
                        $mode_val = "70-80";
                     }
                    
                     $sixty_to_seventy = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['>=',  'batch_student_cape.final' , 60]) 
                             ->andWhere(['<',  'batch_student_cape.final' ,  70]) 
                            ->count();
                     $course_info['sixty_to_seventy'] =  $sixty_to_seventy;
                     if($sixty_to_seventy > $mode_count)
                     {
                        $mode_count = $sixty_to_seventy;
                        $mode_val = "60-70";
                     }
                     
                     $fifty_to_sixty = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['>=',  'batch_student_cape.final' , 50]) 
                             ->andWhere(['<',  'batch_student_cape.final' ,  60]) 
                            ->count();
                     $course_info['fifty_to_sixty'] =  $fifty_to_sixty;
                     if($fifty_to_sixty > $mode_count)
                     {
                        $mode_count = $fifty_to_sixty;
                        $mode_val = "50-60";
                     }
                     
                      $forty_to_fifty = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['>=',  'batch_student_cape.final' , 40]) 
                             ->andWhere(['<',  'batch_student_cape.final' ,  50]) 
                            ->count();
                     $course_info['forty_to_fifty'] =  $forty_to_fifty;
                     if($forty_to_fifty > $mode_count)
                     {
                        $mode_count = $forty_to_fifty;
                        $mode_val = "40-50";
                     }
                     
                     $thirtyfive_to_forty = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                            ->andWhere(['>=',  'batch_student_cape.final' , 35]) 
                             ->andWhere(['<',  'batch_student_cape.final' ,  40]) 
                            ->count();
                     $course_info['thirtyfive_to_forty'] =  $thirtyfive_to_forty;
                     if($thirtyfive_to_forty > $mode_count)
                     {
                        $mode_count = $thirtyfive_to_forty;
                        $mode_val = "35-40";
                     }
                     
                     $minus_thirtyfive = BatchStudentCape::find()
                            ->innerJoin('batch_cape', '`batch_student_cape`.`batchcapeid` = `batch_cape`.`batchcapeid`')
                            ->innerJoin('cape_course', '`batch_cape`.`capecourseid` = `cape_course`.`capecourseid`')
                            ->where(['batch_student_cape.isactive' => 1, 'batch_student_cape.isdeleted' => 0,
                                            'batch_cape.isactive' => 1, 'batch_cape.isdeleted' => 0, 
                                            'cape_course.capecourseid' => $course->capecourseid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                            ])
                             ->andWhere(['<',  'batch_student_cape.final' ,  35]) 
                            ->count();
                     $course_info['minus_thirtyfive'] =  $minus_thirtyfive;
                     if($minus_thirtyfive > $mode_count)
                     {
                        $mode_count = $minus_thirtyfive;
                        $mode_val = " -35";
                     }
                     
                     $course_info['mode'] =  $mode_val;
                    
                    $cape_data[] = $course_info;
                }
                $cape_dataprovider = new ArrayDataProvider([
                        'allModels' => $cape_data,
                        'pagination' => [
                            'pageSize' => 25, 
                        ],
                        'sort' => [
                            'defaultOrder' => ['code' => SORT_ASC],
                            'attributes' => ['code'],
                        ]
                 ]);
            }
        }
        
        else        //if !CAPE
        {
            $courses = CourseOffering::find()
                    ->where(['academicofferingid' => $academicofferingid, 'isactive' => 1 , 'isdeleted' => 0])
                    ->all();
            
            $total_courses = count($courses);
            $total_entered = 0;
            foreach($courses as $course)
            {
                $has_grades = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                if($has_grades>0)
                     $total_entered++;
            }
            $total_outstanding = $total_courses - $total_entered;
            
            
            if($courses)
            {
                foreach($courses as $course)
                {
                    $course_info['courseofferingid'] = $course->courseofferingid;
                    $course_info['programmecatalogid'] = $programmecatalogid;
                    
                    $catalog = CourseCatalog::find()
                            ->where(['coursecatalogid' => $course->coursecatalogid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one();
                    $course_info['code'] = $catalog->coursecode;
                    $course_info['name'] = $catalog->name;
                    
                    $lecs = EmployeeBatch::find()
                             ->innerJoin('batch', '`employee_batch`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid`=`course_offering`.`courseofferingid`')
                             ->where(['course_offering.courseofferingid' => $course->courseofferingid])
                            ->all();
                    if($lecs)
                        $has_lecs = true;
                    else
                        $has_lecs = false;
                    $lecturers = "";
                            
                     if($has_lecs)
                     {
                         $lec_count = count($lecs);
                         foreach($lecs as $key=>$lec)
                         {
                             if($lec_count - $key == 1 )     //if last lecturer
                             {
                                 $lecturers .= Employee::getEmployeeName($lec->personid);
                             }
                             else       //not last lecturer
                             {
                                 $lecturers .= Employee::getEmployeeName($lec->personid) . ",   ";
                             }
                         }
                          $course_info['lecturer'] = $lecturers;
                     }
                     else
                          $course_info['lecturer'] = "Unavailable"; 
                     
                    $semester = Semester::find()
                            ->where(['semesterid' => $course->semesterid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                            ->title;
                    $course_info['semester'] = $semester;
                    
                    $coursetype =  CourseType::find()
                            ->where(['coursetypeid' => $course->coursetypeid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                            ->name;
                    $course_info['coursetype'] = $coursetype;
                    
                    $passcriteria = PassCriteria::find()
                            ->where(['passcriteriaid' => $course->passcriteriaid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                            ->name;
                    $course_info['passcriteria'] = $passcriteria;
                    
                    $passfailtype = PassFailType::find()
                            ->where(['passfailtypeid' => $course->passfailtypeid, 'isactive' => 1, 'isdeleted' => 0])
                            ->one()
                            ->description;
                    $course_info['passfailtype'] = $passfailtype;
                    
                    $course_info['credits'] = $course->credits;
                    $course_info['coursework'] = round($course->courseworkweight);
                    $course_info['exam'] = round($course->examweight);
                    
                    $enrolled_count = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                    
                    $fail_count =  BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'F', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                    $pass_count = $enrolled_count - $fail_count;
                    
                    $pass_percentage = 0;
                    if( $enrolled_count)
                            $pass_percentage = number_format(($pass_count/$enrolled_count)*100,1);
                    
                    $course_info['total'] = $enrolled_count;
                    $course_info['passes'] = $pass_count;
                    $course_info['fails'] = $fail_count;
                    $course_info['pass_percent'] =  $pass_percentage;
                    
                    $mode_count = 0;
                    $mode_val = "N/A";
                    
                    $a_plus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'A+', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['a_plus'] =  $a_plus;
                     if($a_plus > $mode_count)
                     {
                        $mode_count = $a_plus;
                        $mode_val = "A+";
                     }
                    
                    $a = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'A', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['a'] =  $a;
                     if($a > $mode_count)
                     {
                        $mode_count = $a;
                        $mode_val = "A";
                     }
                    
                    $a_minus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'A-', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['a_minus'] =  $a_minus;
                     if($a_minus > $mode_count)
                     {
                        $mode_count = $a_minus;
                        $mode_val = "A-";
                     }
                    
                    $b_plus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'B+', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['b_plus'] =  $b_plus;
                     if($b_plus > $mode_count)
                     {
                        $mode_count = $b_plus;
                        $mode_val = "B+";
                     }
                    
                    $b = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'B', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['b'] =  $b;
                     if($b > $mode_count)
                     {
                        $mode_count = $b;
                        $mode_val = "B";
                     }
                    
                    $b_minus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'B-', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['b_minus'] =  $b_minus;
                     if($b_minus > $mode_count)
                     {
                        $mode_count = $b_minus;
                        $mode_val = "B-";
                     }
                    
                    $c_plus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'C+', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['c_plus'] =  $c_plus;
                     if($c_plus > $mode_count)
                     {
                        $mode_count = $c_plus;
                        $mode_val = "C+";
                     }
                    
                    $c = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'C', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['c'] =  $c;
                     if($c > $mode_count)
                     {
                        $mode_count = $c;
                        $mode_val = "C";
                     }
                    
                    $c_minus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'C-', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['c_minus'] =  $c_minus;
                     if($c_minus > $mode_count)
                     {
                        $mode_count = $c_minus;
                        $mode_val = "C-";
                     }
                    
                    $d_plus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'D+', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['d_plus'] =  $d_plus;
                     if($d_plus > $mode_count)
                     {
                        $mode_count = $d_plus;
                        $mode_val = "D+";
                     }
                    
                    $d = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'D', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['d'] =  $d;
                     if($d > $mode_count)
                     {
                        $mode_count = $d;
                        $mode_val = "D";
                     }
                    
                    $d_minus = BatchStudent::find()
                            ->innerJoin('batch', '`batch_students`.`batchid` = `batch`.`batchid`')
                            ->innerJoin('course_offering', '`batch`.`courseofferingid` = `course_offering`.`courseofferingid`')
                            ->where(['batch_students.grade' => 'D-', 'batch_students.isactive' => 1, 'batch_students.isdeleted' => 0,
                                            'batch.isactive' => 1, 'batch.isdeleted' => 0, 
                                            'course_offering.courseofferingid' => $course->courseofferingid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                            ])
                            ->count();
                     $course_info['d_minus'] =  $d_minus;
                     if($d_minus > $mode_count)
                     {
                        $mode_count = $d_minus;
                        $mode_val = "D-";
                     }
                    
                     $course_info['mode'] =  $mode_val;
                    
                    $asc_data[] = $course_info;
                }
                
                $asc_dataprovider = new ArrayDataProvider([
                                'allModels' => $asc_data,
                                'pagination' => [
                                    'pageSize' => 25,
                            ],
                             'sort' => [
                                'defaultOrder' => ['code' => SORT_ASC],
                                'attributes' => ['code'],
                            ]
                    ]);
            }
        }
       
        $date = "Date Generated: " . date('Y-m-d') . "   ";
        $academic_year = AcademicYear::find()
                ->where(['academicyearid' => $academicoffering->academicyearid, 'isactive' => 1, 'isdeleted' => 0])
                ->one()
                ->title;
        $employeeid = Yii::$app->user->identity->personid;
        $generating_officer = "Generated By: " . Employee::getEmployeeName($employeeid);

        $filename = "Title: " . $programme_name. " (" . $academic_year . ") Performance Report " . $date ."  " .  $generating_officer;
        
        
        return $this->render('programme_broadsheet', [
                    'programme_name' => $programme_name,
                    'asc_dataprovider' => $asc_dataprovider,
                    'cape_dataprovider' => $cape_dataprovider,
                    'programmecatalogid' => $programmecatalogid,
                    'academicofferingid' => $academicofferingid,
                    'filename' => $filename,
                   'total_courses' => $total_courses,
                   'total_entered' => $total_entered,
                   'total_outstanding' => $total_outstanding,
                   'academic_year' => $academic_year,
        ]);
    }
    
    
    
//    public function actionUploadAttachments($recordid, $count, $action = NULL)
//    {
//        $package = Package::find()
//                        ->where(['packageid' => $recordid])
//                        ->one();
//
//        $model = new PackageAttachment();
//        $model->package_id = $package->packageid;
//        $model->package_name = $package->name;
//
//        $saved_documents = Package::getDocuments($recordid);
//        $model->limit = $package->documentcount - count($saved_documents);
//
//        if ($model->limit == 0)
//            $mandatory_delete = true;
//        else
//            $mandatory_delete = false;
//
//        if (Yii::$app->request->isPost) 
//        {
//            $model->files = UploadedFile::getInstances($model, 'files');
//            $pending_count = count($model->files);
//            $saved_count = count(Package::getDocuments($recordid));
//
//            /* 
//             * if summation of present files count and pending files <= stipulated document count,
//             * upload is allowed
//             */
//            if( ($saved_count+$pending_count) <= $package->documentcount)
//            {
//                if ($model->upload())   // file is uploaded successfully
//                {
//                    if (Package::hasAllDocuments($recordid) == true)
//                    {
//                        $package->packageprogressid = 2;
//                        $package->save();
//                    }
//
//                    if ($action == NULL)
//                        return self::actionInitiatePackage($package->packageid);
//                    else
//                        return self::actionIndex();
//                }
//            }
//            else
//            {
//                Yii::$app->getSession()->setFlash('error', 'You have exceeded you stipulated attachment count.');              
//            }
//
//        }
//
//        return $this->render('upload_attachments', 
//                            [
//                                'model' => $model,
//                                'recordid' => $recordid,
//                                'mandatory_delete' => $mandatory_delete,
//                                'saved_documents' => $saved_documents,
//                                'count' => $count,
//                            ]
//        );
//    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function actionViewAllProgrammes()
    {
        $info_string = "";
        $programme_dataprovider = array();
        $course_dataprovider = array();
        
        $info_string .= " All Programmes";

        $data_package = array();
        $programme_container = array();
        $programme_info = array();

        $programmes = ProgrammeCatalog::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        foreach ($programmes as $programme)
        {
            $programme_info['programmecatalogid'] = $programme->programmecatalogid;

            $qualificationtype = QualificationType::find()
                    ->where(['qualificationtypeid' => $programme->qualificationtypeid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()->abbreviation;
            $programme_info['qualificationtype'] = $qualificationtype;

            $programme_info['name'] = $programme->name;
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
                    ->one()->name;
            $programme_info['programmetype'] = $programmetype;

            $programme_info['duration'] = $programme->duration;
            $programme_info['creationdate'] = $programme->creationdate;

            $programme_container[] = $programme_info;
        }

        $programme_dataprovider = new ArrayDataProvider([
                    'allModels' => $programme_container,
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                    'sort' => [
                        'defaultOrder' => ['programmetype' =>SORT_ASC,  'name' => SORT_ASC],
                        'attributes' => ['programmetype', 'name'],
                    ]
            ]); 
        
       
        return $this->render('all_programme_results',
            [
                'info_string' => $info_string,
                'dataProvider' => $programme_dataprovider,
            ]);
    }
    
    
    public function actionViewAllCourses()
    {
        $info_string = "";
        $programme_dataprovider = array();
        $course_dataprovider = array();
        
        $info_string .= " All Courses";

        $course_container = array();
        $course_info = array();
        
        $db = Yii::$app->db;
        $courses = $db->createCommand(
                "SELECT course_catalog.coursecode AS 'code',"
                ." course_catalog.name AS 'name',"
                ." course_offering.academicofferingid AS 'academicofferingid'" 
                ." FROM course_offering" 
                ." JOIN course_catalog"
                ." ON course_offering.coursecatalogid = course_catalog.coursecatalogid"
                ." WHERE course_offering.isactive = 1"
                ." AND course_offering.isdeleted = 0"
                ." GROUP BY course_offering.coursecatalogid;"
            )
            ->queryAll();
        
        $cape_courses = $db->createCommand(
                "SELECT cape_course.coursecode AS 'code',"
                ." cape_course.name AS 'name',"
                ." cape_subject.academicofferingid AS 'academicofferingid'" 
                ." FROM cape_course" 
                ." JOIN cape_unit"
                ." ON cape_course.capeunitid = cape_unit.capeunitid"
                ." JOIN cape_subject"
                ." ON cape_unit.capesubjectid = cape_subject.capesubjectid"
                ." WHERE cape_course.isactive = 1"
                ." AND cape_course.isdeleted = 0"
                ." GROUP BY cape_course.coursecode;"
            )
            ->queryAll();
        
        if($courses)
        {
            foreach ($courses as $course)
            {
                $course_info['code'] = $course['code'];
                $course_info['name'] = $course['name'];
                $course_info['academicofferingid'] = $course['academicofferingid'];
                $course_info['type'] = 'associate';
                $course_container[] = $course_info;
            }
        }
        
        if($cape_courses)
        {
            foreach ($cape_courses as $cape_course)
            {
                $course_info['code'] = $cape_course['code'];
                $course_info['name'] = $cape_course['name'];
                $course_info['academicofferingid'] = $cape_course['academicofferingid'];
                $course_info['type'] = 'cape';
                $course_container[] = $course_info;
            }
        }

        $course_dataprovider = new ArrayDataProvider([
                    'allModels' => $course_container,
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                    'sort' => [
                        'defaultOrder' => ['type' => SORT_ASC, 'code' =>SORT_ASC],
                        'attributes' => ['code', 'type'],
                    ]
            ]); 
        
       
        return $this->render('all_course_results',
            [
                'info_string' => $info_string,
                'dataProvider' => $course_dataprovider,
            ]);
    }
}
