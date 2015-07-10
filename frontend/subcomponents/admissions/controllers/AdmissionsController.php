<?php

namespace app\subcomponents\admissions\controllers;

use yii\web\Controller;

class AdmissionsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
}
