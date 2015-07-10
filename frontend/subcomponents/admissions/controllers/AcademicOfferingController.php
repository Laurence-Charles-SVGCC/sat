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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

        if ($model->load(Yii::$app->request->post()))
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
                    $ao_model->appliable = $model->appliable[$programme_id];
                    if (!$ao_model->save())
                    {
                        Yii::$app->getSession()->setFlash('error', 'Academic Offerign was not saved.');
                        return $this->render('create', [
                                'model' => $model,
                            ]);
                    }
                }
            }
            return $this->redirect(Url::toRoute('academic-offering/index'));
        } else {
            return $this->render('create', [
                'model' => $model,
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
            $academic_offering_name =  $pc_result->name . " " . $ay_result->title;
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
        $this->findModel($id)->delete();

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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
