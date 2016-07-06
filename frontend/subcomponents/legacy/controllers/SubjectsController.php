<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    namespace app\subcomponents\legacy\controllers;
    
    use Yii;


    class SubjectsController extends Controller
    {

        public function actionIndex()
        {
            if (true/*Yii::$app->user->can('manageLegacySubjects') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            return $this->render('index',
                    [
                        
                    ]);
        }
        
        
        public function actionCreateSubject()
        {
            if (true/*Yii::$app->user->can('createLegacySubjects') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('create_subject',
                    [
                        
                    ]);
        }
        
        
        public function actionDeleteSubject()
        {
            if (true/*Yii::$app->user->can('deleteLegacySubjects') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            
        }
        
        
        
    }

