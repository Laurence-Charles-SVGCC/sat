<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    namespace app\subcomponents\legacy\controllers;
    
    use Yii;


    class GradesController extends Controller
    {

        public function actionIndex()
        {
             if (true/*Yii::$app->user->can('manageLegacyGrades') == false*/)
            {
                 return $this->render('unauthorized');
            }
            

            return $this->render('index',
                    [
                        
                    ]);
        }
        
        
        public function actionViewTerms()
        {
             if (true/*Yii::$app->user->can('manageLegacyTerms') == false*/)
            {
                 return $this->render('unauthorized');
            }
            

            return $this->render('view_terms',
                    [
                        
                    ]);
        }
        
        
        public function actionViewTermBatches()
        {
             if (true/*Yii::$app->user->can('manageLegacyBatches') == false*/)
            {
                 return $this->render('unauthorized');
            }
            

            return $this->render('view_term_batches',
                    [
                        
                    ]);
        }
        
        
         public function actionBatchMarksheet()
        {
             if (true/*Yii::$app->user->can('manageLegacyMarksheet') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }

            return $this->render('batch_marksheet',
                    [
                        
                    ]);
        }
        
        
    }

