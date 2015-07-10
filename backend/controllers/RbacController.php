<?php

namespace backend\controllers;

class RbacController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionCreateRolesPermissions()
    {
        return $this->render('create-roles-permissions');
    }
    
    public function actionAssignChildren()
    {
        return $this->render('assign-children');
    }    

}
