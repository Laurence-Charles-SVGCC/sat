<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    namespace app\subcomponents\legacy\controllers;
    
    use Yii;
    use yii\web\Controller;


    class YearController extends Controller
    {

        public function actionIndex()
        {
            if (true/*Yii::$app->user->can('manageLegacyYears') == false*/)
            {
                 return $this->render('unauthorized');
            }
           
            return $this->render('index',
                    [
                        
                    ]);
        }
        
        public function actionCreateYear()
        {
            if (true/*Yii::$app->user->can('createLegacyYear') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('create_year',
                    [
                        
                    ]);
        }
        
        
        public function actionDeleteYear()
        {
            if (true/*Yii::$app->user->can('deleteLegacyYear') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
           
        }
        
        
        public function actionViewYear()
        {
            if (true/*Yii::$app->user->can('viewLegacyYear') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('view_year',
                    [
                        
                    ]);
        }
    }
 

