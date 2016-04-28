<?php

namespace app\subcomponents\programmes\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;

use frontend\models\ProgrammeCatalog;
use frontend\models\Division;
use frontend\models\Department;
use frontend\models\QualificationType;
use frontend\models\ExaminationBody;
use frontend\models\IntentType;
use frontend\models\AcademicOffering;


class ProgrammesController extends Controller
{
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }
    
    
    public function actionIndex()
    {
        $info_string = "";
        $programme_dataprovider = array();
        $course_dataprovider = array();
        
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
                'course_dataprovider' => $course_dataprovider,
            ]);
    }
    
    
    
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
