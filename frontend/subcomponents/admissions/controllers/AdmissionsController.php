<?php

namespace app\subcomponents\admissions\controllers;

use yii\web\Controller;

class AdmissionsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    /**
     * Renders the Application Period Summary view
     * 
     * @return type
     * 
     * Author: Laurence Charles
     * Date Created: 08/02/2016
     * Date Last Modified: 08/02/2016
     */
    public function actionManageApplicationPeriod()
    {
        return $this->render('period_summary');
    }
}
