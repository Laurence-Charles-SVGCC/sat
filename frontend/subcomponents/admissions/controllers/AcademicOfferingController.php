<?php

namespace app\subcomponents\admissions\controllers;

use Yii;
use frontend\models\AcademicOffering;
use frontend\models\AcademicOfferingSearch;
use frontend\models\ProgrammeCatalog;
use frontend\models\AcademicYear;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use frontend\models\ExaminationBody;
use frontend\models\Subject;
use frontend\models\CapeSubject;
use yii\data\ActiveDataProvider;
use frontend\models\CapeGroup;
use frontend\models\CapeSubjectGroup;

/**
 * AcademicOfferingController implements the CRUD actions for AcademicOffering model.
 */
class AcademicOfferingController extends Controller
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
     * Lists all AcademicOffering models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AcademicOfferingSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider = new ActiveDataProvider([
                'query' => AcademicOffering::find()->where(['isdeleted' => 0]),
            ]);
        $capeDataProvider = new ActiveDataProvider([
                'query' => CapeSubject::find()->where(['isdeleted' => 0]),
            ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'capeDataProvider' => $capeDataProvider,
        ]);
    }

    /**
     * Displays a single AcademicOffering model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AcademicOffering model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AcademicOffering();
        $capesubject = new CapeSubject();

        if ($model->load(Yii::$app->request->post()) && $capesubject->load(Yii::$app->request->post()))
         {
            foreach ($model->programmecatalogid as $programme_id=>$programme)
            {
                if ($programme == 1)
                {
                    //Checkbox for this programme is ticked
                    $ao_model = new AcademicOffering();
                    $ao_model->programmecatalogid = $programme_id;
                    $ao_model->academicyearid = $model->academicyearid;
                    $ao_model->applicationperiodid = $model->applicationperiodid;
                    $ao_model->spaces = $model->spaces[$programme_id];
                    $ao_model->interviewneeded = $model->interviewneeded[$programme_id];
                    if (!$ao_model->save())
                    {
                        Yii::$app->getSession()->setFlash('error', 'Academic Offering was not saved.');
                        return $this->render('create', [
                                'model' => $model,
                                'capesubject' => array(),
                                'capesubjects' => array(),
                            ]);
                    }
                }
            }
            $cape_selected = False;
            foreach ($capesubject->subjectname as $subjectname=>$subject)
            {
                if ($subject == 1)
                {
                    $cape_selected = True;
                    break;
                }
            }
            if ($cape_selected)
            {
                $capeprogramme = ProgrammeCatalog::findOne(['name' => 'cape']);
                if ($capeprogramme)
                {
                    $capeoffering = AcademicOffering::findOne(['programmecatalogid' => $capeprogramme->programmecatalogid,
                        'applicationperiodid' => $model->applicationperiodid, 'isdeleted' => 0]);
                    if ($capeoffering)
                    {
                        foreach ($capesubject->subjectname as $subjectname=>$subject)
                        {
                            if ($subject == 1)
                            {
                                //Checkbox for this subject is ticked
                                $cs_model = new CapeSubject();
                                $cs_model->academicofferingid = $capeoffering->academicofferingid;
                                $cs_model->subjectname = $subjectname;
                                $cs_model->capacity = $capesubject->capacity[$subjectname];
                                if (!$cs_model->save())
                                {
                                    Yii::$app->getSession()->setFlash('error', 'Academic Offering of CAPE Subject was not saved.');
                                    return $this->render('create', [
                                            'model' => $model,
                                            'capesubject' => $capesubject,
                                            'capesubjects' => array(),
                                        ]);
                                }
                                foreach (Yii::$app->request->post('capegroup')[$subjectname] as $key => $choice)
                                {
                                    $grp = CapeGroup::findOne(['name' => 'group' . intval($choice)+1]);
                                    $capesubjectgroup = new CapeSubjectGroup();
                                    if ($grp)
                                    {
                                        $capesubjectgroup->capegroupid = $grp->capegroupid;
                                        $capesubjectgroup->capesubjectid = $cs_model->capesubjectid;
                                    }
                                    if (!$capesubjectgroup->save())
                                    {
                                        Yii::$app->getSession()->setFlash('error', 'CAPE Subject Group not saved.');
                                        return $this->render('create', [
                                            'model' => $model,
                                            'capesubject' => $capesubject,
                                            'capesubjects' => array(),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        Yii::$app->getSession()->setFlash('error', 'No academic offering of CAPE found for specified period.');
                    }
                }
                else
                {
                    Yii::$app->getSession()->setFlash('error', 'No Programme called CAPE found.');
                }
            }
            return $this->redirect(Url::toRoute('academic-offering/index'));
        } 
        else 
        {
            $subjects = array();
            $exam_body = ExaminationBody::findOne(['abbreviation' => 'cape']);
            if ($exam_body)
            {
                $subjects = Subject::findAll(['examinationbodyid' => $exam_body->examinationbodyid]);
            }
    
            return $this->render('create', [
                'model' => $model,
                'capesubjects' => $subjects,
                'capesubject' => $capesubject,
            ]);
        }
    }

    /**
     * Updates an existing AcademicOffering model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->academicofferingid]);
        } 
        else 
        {
            $pc_result = ProgrammeCatalog::findOne(['programmecatalogid' => $model->programmecatalogid]);
            $ay_result = AcademicYear::findOne(['academicyearid' => $model->academicyearid]);
            $academic_offering_name =  $pc_result && $ay_result ? $pc_result->name . " " . $ay_result->title : "Undefined Programme Catalog";
            return $this->render('update', [
                'model' => $model,
                'academic_offering_name' => $academic_offering_name,
            ]);
        }
    }

    /**
     * Deletes an existing AcademicOffering model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model)
        {
            $model->isdeleted = 1;
            $model->isactive = 0;
            if (!$model->save())
            {
                Yii::$app->session->setFlash('error', 'Academic Offering could not be deleted');
            }
        }
        

        return $this->redirect(['index']);
    }

    /**
     * Finds the AcademicOffering model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AcademicOffering the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AcademicOffering::findOne($id)) !== null) {
            return $model;
        } 
        elseif (($model = CapeSubject::findOne(['capesubjectid' => $id])) !== null)
        {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
