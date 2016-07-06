<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace app\subcomponents\legacy\controllers;
    
    use Yii;


    class BatchController extends Controller
    {

        public function actionIndex()
        {
            if (true/*Yii::$app->user->can('manageLegacyBatches') == false*/)
            {
                 return $this->render('unauthorized');
            }
           
            return $this->render('index',
                    [
                        
                    ]);
        }
        
        public function actionCreateYear()
        {
            if (true/*Yii::$app->user->can('createLegacyBatch') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
            if ($post_data = Yii::$app->request->post())
            {
                
            }
            
            return $this->render('create_batch',
                    [
                        
                    ]);
        }
        
        
        public function actionDeleteYear()
        {
            if (true/*Yii::$app->user->can('deleteLegacyBatch') == false*/)
            {
                 return $this->render('unauthorized');
            }
            
           
        }
    }
