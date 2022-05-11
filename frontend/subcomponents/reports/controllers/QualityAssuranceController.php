<?php

namespace app\subcomponents\reports\controllers;

class QualityAssuranceController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
