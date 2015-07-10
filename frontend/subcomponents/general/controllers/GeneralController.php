<?php

namespace app\subcomponents\general\controllers;

use yii\web\Controller;

class GeneralController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
