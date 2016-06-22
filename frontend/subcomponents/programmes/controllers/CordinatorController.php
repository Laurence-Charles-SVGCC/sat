<?php

namespace app\subcomponents\programmes\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

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
use backend\models\AuthItemChild;


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
                          ->innerJoin('academic_offering', '`prgoramme_catalog`.`programmecatalogid` = `academic_offering`.`programmecatalogid`')
                         ->where(['programme_catalog.isactive' => 1, 'programme_catalog.isdeleted' => 0,
                                        'academic_offering' => $cordinator->academicofferingid, 'academic_offering.isdeleted' => 0, 'academic_offering.isactive' => 1
                                        ])
                         ->one();
                 if($record)
                     $details = ProgrammeCatalog::getProgrammeFullName($record->programmecatalogid);
             }
             elseif ($cordinator->cordinatortypeid == 3)        //if course head
             {
                 $record = CourseCatlog::find()
                         ->innerJoin('course_offering', '`course_catalog`.`coursecatalogid` = `course_offering`.`courseofferingid`')
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
                    ->one()
                    ->title;
            $cordinator_info['academicyear'] = $year;
            $cordinator_info['isserving'] = $cordinator->isserving;
            
            $cordinator_container[] = $cordinator_info;
        }
        
        $dataProvider = new ArrayDataProvider([
                    'allModels' => $cordinator_container,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => [
                        'defaultOrder' => ['isserving' => SORT_ASC],
                        'attributes' => ['isserving', 'firstname' , 'lastname', 'cordinatortype', 'details'],
                    ]
            ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    
    /**
     * Displays a single Cordinator model.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 22/06/216
     * Date Last Modified: 22/06/2016
     */
    public function actionView($id)
    {
        $cordinator = Cordinator::find()
                ->where(['cordinatorid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->one();
        
        return $this->render('view', [
            'cordinator' => $cordinator,
        ]);
    }

    
    /**
     * Creates a new Cordinaotr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 22/06/216
     * Date Last Modified: 22/06/2016
     */
    public function actionCreate()
    {
        $cordinator = new Cordinator();
    
        if (Yii::$app->request->post())
        {
            
        }

        return $this->render('create', [
            'cordinator' => $cordinator,
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
     * Date Created: 22/06/216
     * Date Last Modified: 22/06/2016
     */
    public function actionUpdate($id)
    {
         $cordinator = Cordinator::find()
                ->where(['cordinatorid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

        if (Yii::$app->request->post())
        {
            
        }
        
        return $this->render('update', [
            'cordinator' => $cordinator,
        ]);
    }

    /**
     * Deletes an existing ProgrammeCatalog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @param string $id
     * @return mixed
     * 
     * Author: Laurence Charles
     * Date Created: 22/06/216
     * Date Last Modified: 22/06/2016
     */
    public function actionDelete($id)
    {
        $cordinator = Cordinator::find()
                ->where(['cordinatorid' => $id, 'isactive' => 1, 'isdeleted' => 0])
                ->one();

        if (Yii::$app->request->post())
        {
            
        }

        return $this->redirect(['index']);
    }

    
}
