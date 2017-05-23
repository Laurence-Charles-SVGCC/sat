<?php

namespace app\subcomponents\programmes\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

use frontend\models\Cordinator;
use frontend\models\CordinatorType;
use backend\models\AuthItem;
use frontend\models\Employee;
use frontend\models\AcademicYear;
use frontend\models\Department;
use frontend\models\CourseOffering;
use frontend\models\CourseCatalog;
use frontend\models\CapeSubject;
use frontend\models\AcademicOffering;
use frontend\models\ProgrammeCatalog;
use frontend\models\ApplicantIntent;

use backend\models\AuthAssignment;


/**
 * CordinatorController implements the CRUD actions for Cordinator model.
 */
class CordinatorController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    
    /**
     * Lists all Cordinator models.
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 21/06/216
     * Date Last Modified: 21/06/2016
     */
    public function actionIndex()
    {
        $dataProvider = NULL;
        $cordinator_container = array();
        $cordinator_info = array();
        
        $cordinators = Cordinator::find()
                ->where(['isactive' => 1, 'isdeleted' => 0])
                ->all();
        
        foreach ($cordinators as $cordinator)
        {
            $cordinator_info['cordinatorid'] = $cordinator->cordinatorid;
            $cordinator_info['cordinatortypeid'] = $cordinator->cordinatortypeid;
            
            $cordinator_type = CordinatorType::find()
                    ->where(['cordinatortypeid' => $cordinator->cordinatortypeid, 'isactive' => 1, 'isdeleted'=> 0])
                    ->one()
                    ->name;
            $cordinator_info['cordinatortype'] = $cordinator_type;
            $cordinator_info['cordinatorid'] = $cordinator->cordinatorid;
            $cordinator_info['personid'] = $cordinator->personid;
            
            $employee = Employee::find()
                    ->where(['personid' => $cordinator->personid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $cordinator_info['title'] = $employee->title;
            $cordinator_info['firstname'] = $employee->firstname;
            $cordinator_info['lastname'] = $employee->lastname;
            $cordinator_info['fullname'] = $employee->title . ". " . $employee->firstname . " " . $employee->lastname;
            
            $details = "unavailable";
             if ($cordinator->cordinatortypeid == 1)            //if department head
             {
                 $record = Department::find()
                         ->where(['departmentid' => $cordinator->departmentid, 'isactive' => 1, 'isdeleted' => 0])
                         ->one();
                 if($record)
                     $details = $record->name;
             }
             elseif ($cordinator->cordinatortypeid == 2)        //if programme head
             {
                 $record = ProgrammeCatalog::find()
                          ->innerJoin('academic_offering', '`programme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                         ->where(['programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0,
                                        'academic_offering.academicofferingid' => $cordinator->academicofferingid, 'academic_offering.isdeleted' => 0, 'academic_offering.isactive' => 1
                                        ])
                         ->one();
                 if($record)
                     $details = ProgrammeCatalog::getProgrammeFullName($record->programmecatalogid);
             }
             elseif ($cordinator->cordinatortypeid == 3)        //if course head
             {
                 $record = CourseCatalog::find()
                         ->innerJoin('course_offering', '`course_catalog`.`coursecatalogid` = `course_offering`.`coursecatalogid`')
                         ->where(['course_catalog.isactive' => 1, 'course_catalog.isdeleted' => 0,
                                        'course_offering.courseofferingid' => $cordinator->courseofferingid, 'course_offering.isdeleted' => 0, 'course_offering.isactive' => 1
                                        ])
                         ->one();
                 if($record)
                     $details = $record->coursecode . " - " . $record->name;
             }
             elseif ($cordinator->cordinatortypeid == 4)        //if capesubject head
             {
                 $record = CapeSubject::find()
                         ->where(['capesubjectid' => $cordinator->capesubjectid, 'isactive' => 1, 'isdeleted' => 0])
                         ->one();
                 if($record)
                     $details = $record->subjectname;
             }
            $cordinator_info['details'] = $details;
            
            $year = AcademicYear::find()
                    ->where(['academicyearid' => $cordinator->academicyearid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
            $year_title = $year->title;
            $year_division = ApplicantIntent::find()
                    ->where(['applicantintentid' => $year->applicantintentid, 'isactive' => 1, 'isdeleted' => 0])
                    ->one()->name;
            $year_label = $year_title . " (" . $year_division . ")";  
            $cordinator_info['academicyear'] = $year_label;
            
            $cordinator_info['isserving'] = $cordinator->isserving;
            
            $cordinator_container[] = $cordinator_info;
        }
        
        $dataProvider = new ArrayDataProvider([
                    'allModels' => $cordinator_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['isserving' => SORT_ASC, 'lastname' =>  SORT_ASC, 'firstname' =>  SORT_ASC],
                        'attributes' => ['isserving', 'firstname' , 'lastname', 'cordinatortype', 'details'],
                    ]
            ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    
    /**
     * Prepares listings for Cordinator assignment feature
     * 
     * @param type $listing_type
     * @param type $academicyearid
     * 
     * Author: Laurence Charles
     * Date Created: 24/06/2016
     * Date Last Modified: 24/06/2016
     */
    public function actionGetAcademicYearListings($listingtype, $academicyearid)
    {
        if ($listingtype == 'department') 
        {
            $found = 0;
            echo Json::encode(['found' => $found]);
        }
        
        elseif ($listingtype == 'academic_offering') 
        {
             $academic_offering_listing = AcademicOffering::prepareAcademicOfferingListing($academicyearid);
             if($academic_offering_listing)
            {
                $found = 1;
                echo Json::encode(['found' => $found, 'listingtype' => $listingtype, 'listing' => $academic_offering_listing]);
            }
            else
            {
                $found = 0;
                echo Json::encode(['found' => $found]);
            }
        } 
        
        elseif ($listingtype == 'course_offering')
        {
             $course_offering_listing = CourseOffering::prepareCourseOfferingListing($academicyearid);
             if($course_offering_listing)
            {
                $found = 1;
                echo Json::encode(['found' => $found, 'listingtype' => $listingtype, 'listing' => $course_offering_listing]);
            }
            else
            {
                $found = 0;
                echo Json::encode(['found' => $found]);
            }
        }
        
        elseif ($listingtype == 'cape_subject')
        {
             $cape_subject_listing = CapeSubject::prepareCapeSubjectListing($academicyearid);
             if($cape_subject_listing)
            {
                $found = 1;
                echo Json::encode(['found' => $found, 'listingtype' => $listingtype, 'listing' => $cape_subject_listing]);
            }
            else
            {
                $found = 0;
                echo Json::encode(['found' => $found]);
            }
        }
    }
    
    
    /**
     * Creates a new Cordinaotr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 22/06/216
     * Date Last Modified: 22/06/2016 | 03/00/2016
     */
    public function actionCreate()
    {
        $cordinator = new Cordinator();
    
        $employees = Employee::getEmployeeListing('Lecturer');
        $departments = Department::getAcademicDepartmentListing();
        $academicyears =  AcademicYear::getYearListing();     

        if ($post_data = Yii::$app->request->post())
        {
            $cordinator_load_flag = false;
            $cordinator_save_flag = false;
            
            $request = Yii::$app->request;
            $departmentid = $request->post('departmentid');
            $academicofferingid = $request->post('academicofferingid');
            $courseofferingid = $request->post('courseofferingid');
            $capesubjectid = $request->post('capesubjectid');
            
            $cordinator_load_flag = $cordinator->load($post_data);
            if($cordinator_load_flag == true)
            {
                $cordinator->dateassigned = date('Y-m-d');
                $cordinator->assignedby = Yii::$app->user->identity->personid;
               
               
               if ($departmentid)
               {
                   $cordinator->departmentid = $departmentid;
                   $duplicate_cordinator = Cordinator::find()
                           ->where(['cordinatortypeid' => 1, 'departmentid' => $departmentid, 'isactive' => 1, 'isdeleted' => 0])
                           ->one();
               }
               elseif ($academicofferingid)
               {
                   $cordinator->academicofferingid = $academicofferingid;
                   $duplicate_cordinator = Cordinator::find()
                           ->where(['cordinatortypeid' => 2, 'academicofferingid' => $academicofferingid, 'isactive' => 1, 'isdeleted' => 0])
                           ->one();
               }
               elseif ($courseofferingid)
               {
                   $cordinator->courseofferingid = $courseofferingid;
                   $duplicate_cordinator = Cordinator::find()
                           ->where(['cordinatortypeid' => 3, 'courseofferingid' => $courseofferingid, 'isactive' => 1, 'isdeleted' => 0])
                           ->one();
               }
               elseif ($capesubjectid)
               {
                   $cordinator->capesubjectid = $capesubjectid;
                   $duplicate_cordinator = Cordinator::find()
                           ->where(['cordinatortypeid' => 4, 'capesubjectid' => $capesubjectid, 'isactive' => 1, 'isdeleted' => 0])
                           ->one();
               }
               
               
               //ensures duplicate cordinator records are not created
               if($duplicate_cordinator)
               {
                   Yii::$app->getSession()->setFlash('error', 'That employee has already been assigned that cordinator role.');
                   return $this->render('create', [
                            'cordinator' => $cordinator,
                            'employees' => $employees,
                            'departments' => $departments,
                            'acaifdemicyears' => $academicyears,
                        ]);
               }
               
               
               $transaction = \Yii::$app->db->beginTransaction();
               try{
                   $cordinator_save_flag = $cordinator->save();
                   
                   $is_currently_cordinator =  AuthAssignment::find()
                               ->where(['user_id' => $cordinator->personid, 'item_name' => 'Cordinator'])
                               ->one();
                   $is_currently_registry =  AuthAssignment::find()
                               ->where(['user_id' => $cordinator->personid, 'item_name' => 'registry'])
                               ->one();
                   if ($cordinator_save_flag == true)
                   {
                       if($is_currently_cordinator == true  && $is_currently_registry == true)
                       {
                           $transaction->commit();
                           return self::actionIndex();
                       }
                       else
                       {
                            $cordinator_permission_save_flag = false;
                            $cordinator_permission = new AuthAssignment();
                            $cordinator_permission->created_at =  time();
                            $cordinator_permission->item_name = "Cordinator";
                            $cordinator_permission->user_id = $cordinator->personid;
                            $cordinator_permission_save_flag = $cordinator_permission->save();
                       
                            if($cordinator_permission_save_flag == true)
                            {
                                 $registry_permission_save_flag = false;
                                 $registry_permission = new AuthAssignment();
                                 $registry_permission->created_at =  time();
                                 $registry_permission->item_name = "registry";
                                 $registry_permission->user_id = $cordinator->personid;
                                 $registry_permission_save_flag = $registry_permission->save();
                                 if($registry_permission_save_flag == true)
                                 {
                                     $transaction->commit();
                                     return self::actionIndex();
                                 }
                                 else
                                 {
                                      $transaction->rollBack();
                                      Yii::$app->getSession()->setFlash('error', 'Error occured saving registry permission record.');
                                  }
                             }
                            else
                            {
                                 $transaction->rollBack();
                                 Yii::$app->getSession()->setFlash('error', 'Error occured saving cordinator  permission record.');
                             }
                       }
                   }
                   else
                   {
                       $transaction->rollBack();
                       Yii::$app->getSession()->setFlash('error', 'Error occured saving co-ordinator record.');
                   }
                   
               } catch (Exception $ex) {
                   Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
               }
            }
            else
           {
               Yii::$app->getSession()->setFlash('error', 'Error occured loading co-ordinator record.');
           }
        }
        
        return $this->render('create', [
            'cordinator' => $cordinator,
            'employees' => $employees,
            'departments' => $departments,
            'academicyears' => $academicyears,
        ]);
    }

    /**
     * Updates an existing Cordinator model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 22/06/2016
     * Date Last Modified: 22/06/2016 | 07/11/2016
     */
    public function actionUpdate($action, $id)
    {
         $cordinator_save_flag = false;
         $cordinator = Cordinator::find()
                ->where(['cordinatorid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
         
         if($action == 'revoke')
         {
            $cordinator->isserving = 0;
            
            $other_cordinator_roles = Cordinator::find()
                ->where(['personid' => $cordinator->personid, 'isactive' => 1, 'isdeleted' => 0])
                ->andWhere(['<>' , 'cordinatorid', $id])
                ->all();
        
            //permission is only deleted if the user has no other active cordinator roles
            if($other_cordinator_roles == false)
            {
                $cordinator_permission = AuthAssignment::find()
                            ->where(['item_name' => 'Cordinator', 'user_id' => $cordinator->personid])
                            ->one();
                if ($cordinator_permission)
                    $cordinator_permission->delete();
                
                 $registry_permission = AuthAssignment::find()
                        ->where(['item_name' => 'registry', 'user_id' => $cordinator->personid])
                        ->one();
                if ($registry_permission)
                    $registry_permission->delete();
            }
           
            $cordinator_save_flag = $cordinator->save();
            if($cordinator_save_flag == false)
           {
               Yii::$app->getSession()->setFlash('error', 'Error occured saving co-ordinator record.');
           }
         }
         
         elseif ($action == 'reassign')
         {
            $cordinator->isserving = 1;
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $cordinator_save_flag = $cordinator->save();
                if($cordinator_save_flag == true)
                {
                    //new permission is only created if the user does not maintain the Cordinator 'permission' because of another active "Cordinator" role
                    $cordinator_permission = AuthAssignment::find()
                            ->where(['item_name' => 'Cordinator', 'user_id' => $cordinator->personid])
                            ->one();
                     $registry_permission = AuthAssignment::find()
                        ->where(['item_name' => 'registry', 'user_id' => $cordinator->personid])
                        ->one();
                    if ($cordinator_permission == true  && $registry_permission == true)
                    {
                        $transaction->commit();
                    }
                    else
                    {
                        $cordinator_permission_save_flag = false;
                        $cordinator_permission = new AuthAssignment();
                        $cordinator_permission->created_at =  time();
                        $cordinator_permission->item_name = "Cordinator";
                        $cordinator_permission->user_id = $cordinator->personid;
                        $cordinator_permission_save_flag = $cordinator_permission->save();
                        if($cordinator_permission_save_flag == true)
                        {
                            $registry_permission_save_flag = false;
                            $registry_permission = new AuthAssignment();
                            $registry_permission->created_at =  time();
                            $registry_permission->item_name = "registry";
                            $registry_permission->user_id = $cordinator->personid;
                            $registry_permission_save_flag = $registry_permission->save();
                            if($registry_permission_save_flag == true)
                            {
                                 $transaction->commit();
                            }
                            else
                            {
                                 $transaction->rollBack();
                                 Yii::$app->getSession()->setFlash('error', 'Error occured saving registry permission record.');
                             }
                        }
                        else
                        {
                             $transaction->rollBack();
                             Yii::$app->getSession()->setFlash('error', 'Error occured saving cordinator permission record.');
                         }
                    }
                }
                else
                {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', 'Error occured saving co-ordinator record.');
                }
            } catch (Exception $ex) {
                Yii::$app->getSession()->setFlash('error', 'Error occured processing request.');
            }
         }
         return self::actionIndex();
    }

    
    /**
     * Deletes an existing ProgrammeCatalog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 26/06/216
     * Date Last Modified: 26/06/2016 | 07/11/2016
     */
    public function actionDeleteCordinator($id)
    {
        $cordinator = Cordinator::find()
                ->where(['cordinatorid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        $other_cordinator_roles = Cordinator::find()
                ->where(['personid' => $cordinator->personid, 'isactive' => 1, 'isdeleted' => 0])
                ->andWhere(['<>' , 'cordinatorid', $id])
                ->all();
        
        //permissions only deleted if the user has no other active cordinator roles
        if($other_cordinator_roles == false)
        {
            $cordinator_permission = AuthAssignment::find()
                        ->where(['item_name' => 'Cordinator', 'user_id' => $cordinator->personid])
                        ->one();
             if ($cordinator_permission)
                    $cordinator_permission->delete();
             
             $registry_permission = AuthAssignment::find()
                        ->where(['item_name' => 'registry', 'user_id' => $cordinator->personid])
                        ->one();
             if ($registry_permission)
                    $registry_permission->delete();
        }
        
        
        $cordinator->isserving = 0;
        $cordinator->isactive = 0;
        $cordinator->isdeleted = 1;
        $cordinator_save_flag = false;
        $cordinator_save_flag = $cordinator->save();
        if($cordinator_save_flag == false)
       {
           Yii::$app->getSession()->setFlash('error', 'Error occured saving co-ordinator record.');
       }
       
       return self::actionIndex();
    }

    
}
