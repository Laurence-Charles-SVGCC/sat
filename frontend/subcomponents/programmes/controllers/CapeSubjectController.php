<?php

namespace app\subcomponents\programmes\controllers;

use Yii;
use frontend\models\ExaminationBody;
use frontend\models\Subject;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class CapeSubjectController extends \yii\web\Controller
{
    public function actionCreate()
    {
        $model = new Subject();

        if ($model->load(Yii::$app->request->post()))
        {
            $exam_body = ExaminationBody::findOne(['abbreviation' => 'cape']);
            if ($exam_body)
            {
                $model->examinationbodyid = $exam_body->examinationbodyid;
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->subjectid]);
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'CAPE subject could not be saved.');
                }
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Examination Body \'CAPE\' could not be found');
            }    
        } 
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model)
        {
            $model->isdeleted = 0;
            $model->isactive = 0;
            if (!$model->save())
            {
                Yii::$app->session->setFlash('error', 'CAPE Subject could not be deleted');
            }
        }

        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        $subjects = array();
        $exam_body = ExaminationBody::findOne(['abbreviation' => 'cape']);
        if ($exam_body)
        {
            $subjects = Subject::findAll(['examinationbodyid' => $exam_body->examinationbodyid]);
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $subjects,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        return $this->render('index',
                [
                    'dataProvider' => $dataProvider,
                ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->subjectid]);
            }
            else
            {
                Yii::$app->session->setFlash('error', 'CAPE Subject could not be updated');
            }
        }
        
        return $this->render('update', 
                [
                    'model' => $model,
                ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view',
                [
                    'model' => $model,
                ]);
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
        if (($model = Subject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
