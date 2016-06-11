<?php

namespace app\subcomponents\programmes\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\base\ErrorException;

use frontend\models\ProgrammeCatalog;
use frontend\models\Division;
use frontend\models\Department;
use frontend\models\QualificationType;
use frontend\models\ExaminationBody;
use frontend\models\IntentType;
use frontend\models\AcademicOffering;
use frontend\models\Batch;
use frontend\models\CourseOffering;
use frontend\models\CourseCatalog;
use frontend\models\BatchCape;
use frontend\models\CapeCourse;
use frontend\models\CapeUnit;
use frontend\models\CapeSubject;
use frontend\models\Cordinator;
use frontend\models\Employee;
use frontend\models\CourseOutline;
use frontend\models\AcademicYear;
use frontend\models\BookletAttachment;


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
//                'cohort_array' => $cohort_array,
//                    'course_outline_dataprovider' => $course_outline_dataprovider,
//                    'cape_course_outline_dataprovider' => $cape_course_outline_dataprovider,
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
