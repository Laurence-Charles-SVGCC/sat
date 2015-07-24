<?php

namespace app\subcomponents\programmes\controllers;

use yii\web\Controller;

class ProgrammesController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
