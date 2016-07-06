<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    namespace app\subcomponents\legacy\controllers;
    
    use Yii;


    class StudentController extends Controller
    {

        public function actionIndex()
        {
            if (true/*Yii::$app->user->can('manageLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            return $this->render('index',
                    [
                        
                    ]);
        }
        
        
        public function actionCreateStudent()
        {
            if (true/*Yii::$app->user->can('studentLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('create_students',
                    [
                        
                    ]);
        }
        
        
        public function actionUpdateStudent()
        {
            if (true/*Yii::$app->user->can('updateLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('update_student',
                    [
                        
                    ]);
        }
        
        
        public function actionDeleteStudent()
        {
            if (true/*Yii::$app->user->can('deleteLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
           
        }
        
        
        public function actionFindAStudent()
        {
            if (true/*Yii::$app->user->can('findLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
                return $this->render('student_listing',
                    [
                        
                    ]);
            }
            
            return $this->render('find_a_student');
        }
        
        
        public function actionEnrollStudents()
        {
            if (true/*Yii::$app->user->can('enrollLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('enroll_students',
                    [
                        
                    ]);
        }
        
        
        
        public function actionViewStudent()
        {
            if (true/*Yii::$app->user->can('viewLegacyStudent') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            
            return $this->render('view_student',
                    [
                        
                    ]);
        }
        
        
        
        public function actionEnroll()
        {
            if (true/*Yii::$app->user->can('enrollLegacyStudents') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('enroll',
                    [
                        
                    ]);
        }
        
        
        
        
        
    }